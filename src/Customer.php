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
        $result = $this->call(['limit' => 0]);
        return is_array($result) && isset($result['totalCount']) ? $result['totalCount'] : 0;
    }

    /**
     * Gives the Customers
     *
     * @param int $limit The page
     * @param int $offset Pagesize
     * @param string $fields The fields, which should be loaded from the customer (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param string $changedSince load all entries after given date
     * @param string $sortings Sort the response by field asc or desc (Examples: id asc)
     * @return array The result
     */
    public function get(int $limit = -1, int $offset = -1, string $fields = '', string $changedSince = '', string $sortings = ''): array
    {
        $this->subFunction = '';
        $data = array('changedSince' => $changedSince);

        // Only if the fields are set, take them in the request
        if ($limit != -1) $data['limit'] = $limit;
        if ($offset != -1) $data['offset'] = $offset;
        if ($fields != '') $data['fields'] = $fields;
        if ($changedSince != '') $data['changedSince'] = $changedSince;
        if ($sortings != '') $data['sortings'] = $sortings;

        $result = $this->call($data);

        // Gets only the customers without other fields
        return is_array($result) && isset($result['customer']) && is_array($result['customer']) ? $result['customer'] : [];
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
        $result = $this->call([['eid' => $customerId, 'data' => ['customerId' => $customerId]]], RequestType::POST);

        if (is_array($result) && count($result) == 1 && isset($result[0]['id'])) {
            return $this->getCustomer($result[0]['id']);
        }

        return [];
    }

    /**
     * Creates a customer with the given fields
     *
     * @param array $data The data for the new customer (see https://pcs.docbee.com/restApi/v1/documentation#/customer)
     * @return array
     */
    public function create(array $data): array
    {
        $this->subFunction = '';

        if (isset($data['customerId'])) {
            // Search if the customer exists and skip it if exists
            $customer = $this->getCustomerFromCustomerId($data['customerId']);
            if (is_array($customer) && count($customer) > 0) return ['error' => 'Customer allready exists', 'errorCode' => 401, 'customer' => $customer];

            return $this->call($data, RequestType::POST);
        }

        return ['error' => 'Parameter error', 'errorCode' => 400];
    }
}