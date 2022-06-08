<?php

namespace miralsoft\docbee\api;

class CustomerContact extends DocbeeAPICall
{
    /** @var int The id from the customer */
    protected int $customerId;

    /**
     * @param Config $config The config
     * @param int $customerId The id for the customer for this calls
     */
    public function __construct(Config $config, int $customerId)
    {
        parent::__construct($config);
        $this->customerId = $customerId;
    }

    /**
     * Gives the count of all customers
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
     * Gives the Customers
     *
     * @param int $limit The page
     * @param int $offset Pagesize
     * @param string $fields The fields, which should be loaded from the customer (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param string $changedSince load all entries after given date
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', string $changedSince = ''): array
    {
        $this->subFunction = '';
        $data = array('customer' => $this->customerId, 'limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'changedSince' => $changedSince);
        $result = $this->call($data);

        // Gets only the customers without other fields
        return is_array($result) && isset($result['customerContact']) && is_array($result['customerContact']) ? $result['customerContact'] : [];
    }

    /**
     * Get the contact with this ID
     *
     * @param string $id The ID from the contact
     * @return array The contact
     */
    public function getContact(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Gets the contact from the given informations
     *
     * @param string $name The name from the contact
     * @param string $mail The mail from the contact
     * @return array The contact data if exists
     */
    public function searchContact(string $name = '', string $mail = ''): array
    {
        $this->subFunction = 'guess';

        // makes the parameters
        $data = ['customer' => $this->customerId];
        if (isset($name) && $name != '') $data['name'] = $name;
        if (isset($mail) && $mail != '') $data['email'] = $mail;

        $result = $this->call([['eid' => $this->customerId, 'data' => $data]], RequestType::POST);

        if (is_array($result) && count($result) > 0 && isset($result[0]['id'])) {
            return $this->getContact($result[0]['id']);
        }

        return [];
    }

    /**
     * Creates a customer with the given fields
     *
     * @param array $data The data to create (see https://pcs.docbee.com/restApi/v1/documentation#!/customerContact/addCustomerContact)
     * @return array
     */
    public function create(array $data): array
    {
        $this->subFunction = '';

        // Search existing contact
        $contact = null;
        if (isset($data['email']) && $data['email'] != '') $contact = $this->searchContact('', $data['email']);
        elseif (isset($data['name']) && $data['name'] != '') $contact = $this->searchContact($data['name']);

        if (is_array($contact) && count($contact) > 0 && isset($contact['id']) && $contact['id'] > 0) {
            return ['error' => 'Contact already exists', 'errorCode' => 401, 'contact' => $contact];
        }

        // Create the contact
        $data['customer'] = $this->customerId;
        return $this->call($data, RequestType::POST);
    }

    /**
     * Edits the contact with the given data
     *
     * @param array $data the data to change in contact
     * @param string $actName The actual name from this contact
     * @param string $actMail The actual mail from this contact
     * @return array The changed contact
     */
    public function edit(array $data, string $actName = '', string $actMail = ''): array
    {
        // Search the contact
        $contact = $this->searchContact($actName, $actMail);

        if (is_array($contact) && count($contact) > 0 && isset($contact['id']) && $contact['id'] > 0) {
            return $this->editContact($contact['id'], $data);
        }

        return ['error' => 'Contact not found', 'errorCode' => 405];
    }

    /**
     * Edits the given contact
     *
     * @param int $id The id from contact
     * @param array $data the data to change
     * @return array The changed contact
     */
    public function editContact(int $id, array $data): array
    {
        $this->subFunction = $id;

        // Search existing contact
        $contact = $this->getContact($id);
        if (!is_array($contact) || count($contact) <= 0) {
            return ['error' => 'Contact not exists', 'errorCode' => 405];
        }

        // Create the contact
        $data['customer'] = $this->customerId;
        return $this->call($data, RequestType::PUT);
    }
}