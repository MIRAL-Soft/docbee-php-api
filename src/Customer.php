<?php

namespace miralsoft\docbee\api;

class Customer extends DocbeeAPICall
{
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
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'changedSince' => $changedSince);
        $result = $this->call($data);

        // Gets only the customers without other fields
        return is_array($result) && isset($result['customer']) && is_array($result['customer']) ? $result['customer'] : [];
    }

    /**
     * Gives all Customers
     *
     * @return array The result
     */
    public function getAll(): array
    {
        // Only 1000 users per request are possible
        //$customersPerPage = 900;
        //$count = $this->count();

        return $this->get();
    }

    /**
     * Get the Customer with this ID
     *
     * @param string $id The ID from the customer
     * @return array The Customer
     */
    public function getCustomer(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Gets the customer from the customerID from erp
     *
     * @param string $customerId The id from erp
     * @return array The customer data if exists
     */
    public function getCustomerFromCustomerId(string $customerId): array
    {
        $this->subFunction = 'guess';
        $result = $this->call([['eid' => $customerId, 'data' => ['customerId' => $customerId]]], true);

        if (is_array($result) && count($result) == 1 && isset($result[0]['id'])) {
            return $this->getCustomer($result[0]['id']);
        }

        return [];
    }

    /**
     * Creates a customer with the given fields
     *
     * @param string $customerId Unique customer id for a customer. Mostly definied by ERP
     * @param int $customerStatus The status for the customer
     * @param string $name Display name of customer
     * @param string $shortName Customer short name
     * @param string $info
     * @param string $wildcardAddress Wildcard email address. For Example "docbee.com" to automaticly assign tickets to customer
     * @param bool $inhouse If these customer is an inhouse customer
     * @param int $companyData companyData identifier
     * @param array $customFields Additional information about the customer
     * @return bool
     */
    public function createCustomer(string $customerId, int $customerStatus, string $name = '', string $shortName = '',
                                   string $info = '', string $wildcardAddress = '', bool $inhouse = false, int $companyData = 0,
                                   array  $customFields = []): array
    {
        $this->subFunction = '';

        // Search if the customer exists and skip it if exists
        $customer = $this->getCustomerFromCustomerId($customerId);
        if (is_array($customer) && count($customer) > 0) return [];

        $data = [
            'customerId' => $customerId,
            'customerStatus' => $customerStatus
        ];

        // Sets optional data if set
        if ($name != '') $data['name'] = $name;
        if ($shortName != '') $data['$shortName'] = $shortName;
        if ($info != '') $data['info'] = $info;
        if ($wildcardAddress != '') $data['wildcardAddress'] = $wildcardAddress;
        if ($inhouse != false) $data['inhouse'] = $inhouse;
        if ($companyData > 0) $data['companyData'] = $companyData;
        if (is_array($customFields) && count($customFields) > 0) $data['customFields'] = $customFields;

        return $this->call($data, true);
    }
}