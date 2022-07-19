<?php

namespace miralsoft\docbee\api;

class Priority extends DocbeeAPICall
{
    /**
     * Returns a list of priorities
     *
     * @param int $limit Limit the max items for the query
     * @param int $offset Offset
     * @param string $fields The fields, which should be loaded from the priorities (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param bool $deactivated deactivated priority?
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', bool $deactivated = false): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'deactivated' => $deactivated);
        $result = $this->call($data);

        // Gets only the priorities without other fields
        return is_array($result) && isset($result['priority']) && is_array($result['priority']) ? $result['priority'] : [];
    }

    /**
     * Get the priority with this ID
     *
     * @param string $id The ID from the priority
     * @return array The priority
     */
    public function getPriority(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get the data from the priority
     *
     * @param string $name The name from the priority
     * @return array The Data from priority
     */
    public function getPriorityFromName(string $name): array
    {

        $this->subFunction = 'guess';
        $result = $this->call([['eid' => $name, 'data' => ['name' => $name]]], RequestType::POST);

        if (is_array($result) && count($result) == 1 && isset($result[0]['id'])) {
            return $this->getPriority($result[0]['id']);
        }

        return [];
    }

    /**
     * Get the ID from priority
     *
     * @param string $name The name from priority
     * @return int the id
     */
    public function getPriorityId(string $name): int
    {
        $priority = $this->getPriorityFromName($name);
        if(is_array($priority) && count($priority) > 0 && isset($priority['id'])){
            return $priority['id'];
        }

        return -1;
    }
}