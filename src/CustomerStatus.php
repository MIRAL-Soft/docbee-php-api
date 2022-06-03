<?php

namespace miralsoft\docbee\api;

class CustomerStatus extends DocbeeAPICall
{
    /**
     * Returns a list of customer status
     *
     * @param int $limit Limit the max items for the query
     * @param int $offset Offset
     * @param string $fields The fields, which should be loaded from the customer (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param string $changedSince load all entries after given date
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = ''): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields);
        $result = $this->call($data);

        // Gets only the customers without other fields
        return is_array($result) && isset($result['customerStatus']) && is_array($result['customerStatus']) ? $result['customerStatus'] : [];
    }

    /**
     * Get the ID from the status
     *
     * @param string $name The name from the status
     * @return int The ID from status
     */
    public function getStatusID(string $name): int
    {
        $this->subFunction = '';
        $status = $this->get();

        if (is_array($status) && count($status) > 0) {
            foreach ($status as $st) {
                if (isset($st['name']) && $st['name'] == $name && isset($st['name']) && $st['id'] > 0) return $st['id'];
            }
        }

        return -1;
    }
}