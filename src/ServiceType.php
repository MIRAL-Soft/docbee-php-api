<?php

namespace miralsoft\docbee\api;

class ServiceType extends DocbeeAPICall
{
    /**
     * Returns a list of service types
     *
     * @param int $limit Limit the max items for the query
     * @param int $offset Offset
     * @param string $fields The fields, which should be loaded from the service types (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param bool $deactivated deactivated service type?
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', bool $deactivated = false): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'deactivated' => $deactivated);
        $result = $this->call($data);

        // Gets only the service types without other fields
        return is_array($result) && isset($result['serviceType']) && is_array($result['serviceType']) ? $result['serviceType'] : [];
    }

    /**
     * Get the service type with this ID
     *
     * @param string $id The ID from the service type
     * @return array The service type
     */
    public function getServiceType(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get the data from the service type
     *
     * @param string $name The name from the service type
     * @return array The Data from service type
     */
    public function getServiceTypeFromName(string $name = '', string $number = ''): array
    {
        $data = [];
        $eid = '';

        // Fill the values for search
        if ($name != '') {
            $data['name'] = $name;
            $eid = $name;
        }
        if ($number != '') {
            $data['number'] = $number;
            $eid = $number;
        }

        $this->subFunction = 'guess';
        $result = $this->call([['eid' => $eid, 'data' => $data]], RequestType::POST);

        if (is_array($result) && count($result) == 1 && isset($result[0]['id'])) {
            return $this->getServiceType($result[0]['id']);
        }

        return [];
    }

    /**
     * Get the ID from service type
     *
     * @param string $name The name from service type
     * @param string $number the number from the service
     * @return int the id
     */
    public function getServiceTypeId(string $name = '', string $number = ''): int
    {
        $serviceType = $this->getServiceTypeFromName($name, $number);
        if (is_array($serviceType) && count($serviceType) > 0 && isset($serviceType['id'])) {
            return $serviceType['id'];
        }

        return -1;
    }

    /**
     * Checks if the service type exists
     *
     * @param array $data the data for the service type (for example to create)
     * @return bool true = service type exists already
     */
    public function exists(array $data): bool
    {
        // First look after the number
        if (isset($data['number']) && $data['number'] != '') {
            $serviceType = $this->getServiceTypeFromName('', $data['number']);

            // If it exists
            if (is_array($serviceType) && count($serviceType) > 0 && isset($serviceType['id']) && $serviceType['id'] > 0) {
                return true;
            }
        }

        // When the name exists
        if (isset($data['name']) && $data['name'] != '') {
            $serviceType = $this->getServiceTypeFromName($data['name']);

            // If it exists
            if (is_array($serviceType) && count($serviceType) > 0 && isset($serviceType['id']) && $serviceType['id'] > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Creates a Ticket with the given fields
     *
     * @param array $data The data for the new Ticket (see https://pcs.docbee.com/restApi/v1/documentation#/ticket)
     * @return array
     */
    public function create(array $data): array
    {
        $this->subFunction = '';

        // The name must be given and if it not exists already
        if (isset($data['name']) && $data['name'] != '') {
            if($this->exists($data))  return ['error' => 'ServiceType allready exists', 'errorCode' => 401, 'serviceType' => $this->getServiceTypeFromName($data)];

            return $this->call($data, RequestType::POST);
        }

        return ['error' => 'Parameter error', 'errorCode' => 400];
    }
}