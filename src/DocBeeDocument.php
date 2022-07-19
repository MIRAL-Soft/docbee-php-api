<?php

namespace miralsoft\docbee\api;

class DocBeeDocument extends DocbeeAPICall
{
    /** @var Customer The customer class for api requests */
    protected Customer $customerC;

    public function __construct(Config $config)
    {
        parent::__construct($config);

        // API object
        $this->customerC = new Customer($config);
    }

    /**
     * Gives the count of all documents
     *
     * @return int The json result
     */
    public function count(): int
    {
        // Get only one entry because we need only the total count data
        $result = $this->call(['limit' => 1]);
        return is_array($result) && isset($result['totalCount']) ? $result['totalCount'] : 0;
    }

    /**
     * Gives the documents
     *
     * @param int $limit The page
     * @param int $offset Pagesize
     * @param string $fields The fields, which should be loaded from the ticket (all fields = * | seperate fields seperate with , (f.e. 'created,Ticket')
     * @param string $changedSince load all entries after given date
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', string $changedSince = ''): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'changedSince' => $changedSince);
        $result = $this->call($data);

        // Gets only the Tickets without other fields
        return is_array($result) && isset($result['docBeeDocument']) && is_array($result['docBeeDocument']) ? $result['docBeeDocument'] : [];
    }

    /**
     * Get the document with this ID
     *
     * @param string $id The ID from the Document
     * @return array The Ticket
     */
    public function getDocument(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get documents from customer
     *
     * @param int $customerId
     * @return array the documents
     */
    public function getDocumentsFromCustomer(int $customerId): array
    {
        $this->subFunction = '';
        $documents = $this->call(['customer' => $customerId, 'fields' => '*']);

        return is_array($documents) && count($documents) > 0 && isset($documents['docBeeDocument']) ? $documents['docBeeDocument'] : [];
    }

    /**
     * Gets documents from customer with the customer number from erp
     *
     * @param string $customerNumber The customer number from erp
     * @return array the documents
     */
    public function getDocumentsFromCustomerNumber(string $customerNumber): array
    {
        // Get the customer
        $customer = $this->customerC->getCustomerFromCustomerId($customerNumber);

        // If customer is found
        if (is_array($customer) && count($customer) > 0 && isset($customer['id'])) {
            return $this->getDocumentsFromCustomer($customer['id']);
        }

        // otherwise try if the number is the id
        return $this->getDocumentsFromCustomer($customerNumber);
    }

    /**
     * Gets documents from given data
     *
     * @param array $data the filter
     * @return array the documents
     */
    public function filterDocuments(array $data): array
    {
        $this->subFunction = '';
        $result = $this->call($data);

        if (is_array($result) && isset($result['docBeeDocument']) && is_array($result['docBeeDocument']) && count($result['docBeeDocument']) > 0) {
            return $result['docBeeDocument'];
        }

        return [];
    }

    /**
     * Creates a document with the given fields
     *
     * @param array $data The data for the new Ticket (see https://pcs.docbee.com/restApi/v1/documentation#!/docBeeDocument/addDocBeeDocument)
     * @return array
     */
    public function create(array $data): array
    {
        $this->subFunction = '';
        return $this->call($data, RequestType::POST);
    }
}