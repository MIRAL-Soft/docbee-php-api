<?php

namespace miralsoft\docbee\api;

class DocBeeDocumentTask extends DocbeeAPICall
{
    protected string $templateMainFunction = 'docBeeDocument/{DOCID}/task/';

    /** @var Customer The customer class for api requests */
    protected Customer $customerC;

    /** @var string This id will be used for all calls */
    protected string $documentId = '';

    public function __construct(Config $config)
    {
        parent::__construct($config);

        // API object
        $this->customerC = new Customer($config);
    }

    /**
     * Gives the count of all tasks
     *
     * @return int The json result
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function count(): int
    {
        $this->checkCall();

        // Get only one entry because we need only the total count data
        $result = $this->call(['limit' => 0]);
        return is_array($result) && isset($result['totalCount']) ? $result['totalCount'] : 0;
    }

    /**
     * Gives the tasks
     *
     * @param int $limit The page
     * @param int $offset Pagesize
     * @param string $fields The fields, which should be loaded from the ticket (all fields = * | seperate fields seperate with , (f.e. 'created,Ticket')
     * @return array The result
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function get(int $limit = -1, int $offset = -1, string $fields = ''): array
    {
        $this->checkCall();

        $this->subFunction = '';
        $data = array();

        // Only if the fields are set, take them in the request
        if($limit != -1)    $data['limit'] = $limit;
        if($offset != -1)    $data['offset'] = $offset;
        if($fields != '')    $data['fields'] = $fields;

        $result = $this->call($data);

        // Gets only the Tickets without other fields
        return is_array($result) && isset($result['task']) && is_array($result['task']) ? $result['task'] : [];
    }

    /**
     * Get the task with this ID
     *
     * @param string $id The ID from the task
     * @return array The Ticket
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function getTask(string $id): array
    {
        $this->checkCall();

        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get tasks from customer
     *
     * @param int $customerId
     * @return array the tasks
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function getTasksFromCustomer(int $customerId): array
    {
        $this->checkCall();

        $this->subFunction = '';
        $tasks = $this->call(['customer' => $customerId, 'fields' => '*']);

        return is_array($tasks) && count($tasks) > 0 && isset($tasks['task']) ? $tasks['task'] : [];
    }

    /**
     * Gets tasks from customer with the customer number from erp
     *
     * @param string $customerNumber The customer number from erp
     * @return array the tasks
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function getTasksFromCustomerNumber(string $customerNumber): array
    {
        $this->checkCall();

        // Get the customer
        $customer = $this->customerC->getCustomerFromCustomerId($customerNumber);

        // If customer is found
        if (is_array($customer) && count($customer) > 0 && isset($customer['id'])) {
            return $this->getTasksFromCustomer($customer['id']);
        }

        // otherwise try if the number is the id
        return $this->getTasksFromCustomer($customerNumber);
    }

    /**
     * Gets tasks from given data
     *
     * @param array $data the filter
     * @return array the tasks
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function filterTasks(array $data): array
    {
        $this->checkCall($data);

        $this->subFunction = '';
        $result = $this->call($data);

        if (is_array($result) && isset($result['task']) && is_array($result['task']) && count($result['task']) > 0) {
            return $result['task'];
        }

        return [];
    }

    /**
     * Creates a task with the given fields
     *
     * @param array $data The data for the new Ticket (see https://pcs.docbee.com/restApi/v1/documentation#!/docBeeDocumentTask/addDocBeeDocumentTask)
     * @return array
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function create(array $data): array
    {
        $this->checkCall($data);

        $this->subFunction = '';
        return $this->call($data, RequestType::POST);
    }

    /**
     * Check if the call is be prepared sucessfull
     *
     * @return bool true = the call can be run
     * @throws \InvalidArgumentException When the documentId is not set
     */
    public function checkCall(array $data = []): bool
    {
        // If the document Id is set, add it before check
        if(isset($data['documentId'])){
            $this->setDocumentId($data['documentId']);
            unset($data['documentId']);
        }

        if ($this->getDocumentId() != null && $this->getDocumentId() != '') {
            return true;
        }

        throw new \InvalidArgumentException('The documentId for this task is not set!');
    }

    /**
     * Get the actual DocBeeDocument-ID is set
     *
     * @return string the documentid
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    /**
     * Sets the actual document Id which is used for calls
     *
     * @param string $documentId the document id
     */
    public function setDocumentId(string $documentId): void
    {
        $this->documentId = $documentId;
        $this->mainFunction = str_replace('{DOCID}', $this->documentId, $this->templateMainFunction);
    }
}