<?php

namespace miralsoft\docbee\api;

class TicketStatus extends DocbeeAPICall
{
    /**
     * Returns a list of ticket status
     *
     * @param int $limit Limit the max items for the query
     * @param int $offset Offset
     * @param string $fields The fields, which should be loaded from the ticket status (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param bool $deactivated deactivated ticket status?
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', bool $deactivated = false): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'deactivated' => $deactivated);
        $result = $this->call($data);

        // Gets only the ticket status without other fields
        return is_array($result) && isset($result['ticketStatus']) && is_array($result['ticketStatus']) ? $result['ticketStatus'] : [];
    }

    /**
     * Get the ticket status with this ID
     *
     * @param string $id The ID from the ticket status
     * @return array The ticket status
     */
    public function getTicketStatus(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get the data from the ticket status
     *
     * @param string $name The name from the ticket status
     * @return array The Data from ticket status
     */
    public function getTicketStatusFromName(string $name): array
    {
        $this->subFunction = 'guess';
        $result = $this->call([['eid' => $name, 'data' => ['name' => $name]]], RequestType::POST);

        if (is_array($result) && count($result) == 1 && isset($result[0]['id'])) {
            return $this->getTicketStatus($result[0]['id']);
        }

        return [];
    }

    /**
     * Get the ID from ticket status
     *
     * @param string $name The name from ticket status
     * @return int the id
     */
    public function getTicketStatusId(string $name): int
    {
        $ticketStatus = $this->getticketStatusFromName($name);
        if(is_array($ticketStatus) && count($ticketStatus) > 0 && isset($ticketStatus['id'])){
            return $ticketStatus['id'];
        }

        return -1;
    }
}