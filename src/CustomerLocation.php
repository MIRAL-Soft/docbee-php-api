<?php

namespace miralsoft\docbee\api;

class CustomerLocation extends DocbeeAPICall
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
     * Gives the count of all locations
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
     * Gives the location
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
        $data = array('customer' => $this->customerId, 'changedSince' => $changedSince);

        // Only if the fields are set, take them in the request
        if ($limit != -1) $data['limit'] = $limit;
        if ($offset != -1) $data['offset'] = $offset;
        if ($fields != '') $data['fields'] = $fields;
        if ($changedSince != '') $data['changedSince'] = $changedSince;
        if ($sortings != '') $data['sortings'] = $sortings;

        $result = $this->call($data);

        // Gets only the customers without other fields
        return is_array($result) && isset($result['customerLocation']) && is_array($result['customerLocation']) ? $result['customerLocation'] : [];
    }

    /**
     * Get the location with this ID
     *
     * @param string $id The ID from the location
     * @return array The location
     */
    public function getLocation(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Gets the location from the given informations
     *
     * @param string $name The name from the location
     * @param string $city The city from the location
     * @param string $street The street from the location
     * @param string $zipcode The zipcode from the location
     * @return array The location data if exists
     */
    public function searchLocation(string $name = '', string $city = '', string $street = '', string $zipcode = ''): array
    {
        $this->subFunction = 'guess';

        // makes the parameters
        $data = ['customer' => $this->customerId];
        $result = [];

        // search address if fields are set
        $searchAddress = false;
        if (isset($city) && $city != '') {
            $data['city'] = $city;
            $searchAddress = true;
        }
        if (isset($street) && $street != '') {
            $data['street'] = $street;
            $searchAddress = true;
        }
        if (isset($zipcode) && $zipcode != '') {
            $data['zipcode'] = $zipcode;
            $searchAddress = true;
        }
        if ($searchAddress) $result = $this->call([['eid' => $this->customerId, 'data' => $data]], RequestType::POST);

        // reset fields
        if (isset($data['city'])) unset($data['city']);
        if (isset($data['street'])) unset($data['street']);
        if (isset($data['zipcode'])) unset($data['zipcode']);

        // Search name
        if ((!is_array($result) || count($result) <= 0 || !isset($result[0]['id'])) && isset($name) && $name != '') {
            $data['name'] = $name;
            $result = $this->call([['eid' => $this->customerId, 'data' => $data]], RequestType::POST);

            unset($data['name']);
        }

        if (is_array($result) && count($result) > 0 && isset($result[0]['id'])) {
            return $this->getLocation($result[0]['id']);
        }

        return [];
    }

    /**
     * Creates a location with the given fields
     *
     * @param array $data The data to create (see https://pcs.docbee.com/restApi/v1/documentation#post-/v1/customerLocation)
     * @return array
     */
    public function create(array $data): array
    {
        $this->subFunction = '';

        // Search existing contact
        $location = null;

        // first try with address
        if (isset($data['city']) && $data['city'] != '' && isset($data['street']) && $data['street'] != '' && isset($data['zipcode']) && $data['zipcode'] != '') $location = $this->searchLocation('', $data['city'], $data['street'], $data['zipcode']);

        // if no location found, next try with name
        if (!is_array($location) || (count($location) <= 0 && isset($data['name']) && $data['name'] != '')) $location = $this->searchLocation($data['name']);

        // if contact is found, no creation necessary
        if (is_array($location) && count($location) > 0 && isset($location['id']) && $location['id'] > 0) {
            return ['error' => 'Contact already exists', 'errorCode' => 401, 'contact' => $location];
        }

        // Create the contact
        $data['customer'] = $this->customerId;
        return $this->call($data, RequestType::POST);
    }

    /**
     * Edits the location with the given data
     *
     * @param array $data the data to change in location
     * @param string $actName The actual name from this location
     * @param string $actCity The actual city from this location
     * @param string $actStreet The actual street from this location
     * @param string $actZipcode The actual zipcode from this location
     * @return array The changed location
     */
    public function edit(array $data, string $actName = '', string $actCity = '', string $actStreet = '', string $actZipcode = ''): array
    {
        // Search the location
        $location = $this->searchLocation($actName, $actCity, $actStreet, $actZipcode);

        if (is_array($location) && count($location) > 0 && isset($location['id']) && $location['id'] > 0) {
            return $this->editLocation($location['id'], $data);
        }

        return ['error' => 'Contact not found', 'errorCode' => 405];
    }

    /**
     * Edits the given location
     *
     * @param int $id The id from location
     * @param array $data the data to change
     * @return array The changed location
     */
    public function editLocation(int $id, array $data): array
    {
        $this->subFunction = $id;

        // Search existing contact
        $contact = $this->getLocation($id);
        if (!is_array($contact) || count($contact) <= 0) {
            return ['error' => 'Contact not exists', 'errorCode' => 405];
        }

        // Create the contact
        $data['customer'] = $this->customerId;
        return $this->call($data, RequestType::PUT);
    }
}