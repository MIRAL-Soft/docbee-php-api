<?php

namespace miralsoft\docbee\api;

class User extends DocbeeAPICall
{
    /**
     * Returns a list of user
     *
     * @param int $limit Limit the max items for the query
     * @param int $offset Offset
     * @param string $fields The fields, which should be loaded from the user (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param bool $deactivated deactivated user?
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', bool $deactivated = false): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'deactivated' => $deactivated);
        $result = $this->call($data);

        // Gets only the user without other fields
        return is_array($result) && isset($result['user']) && is_array($result['user']) ? $result['user'] : [];
    }

    /**
     * Get the user with this ID
     *
     * @param string $id The ID from the user
     * @return array The user
     */
    public function getUser(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get the data from the user
     *
     * @param string $name The name from the user
     * @param string $mail The mail from user
     * @return array The Data from user
     */
    public function getUserFromName(string $name = '', string $mail = ''): array
    {
        $this->subFunction = '';
        $users = $this->get(0,0,'*');

        if (is_array($users) && count($users) > 0) {
            foreach ($users as $user) {
                if (((isset($user['name']) && $user['name'] == $name) || (isset($user['email']) && $user['email'] == $mail)) && $user['id'] > 0) return $user;
            }
        }

        return [];
    }

    /**
     * Get the ID from user
     *
     * @param string $name The name from user
     * @param string $mail The mail from user
     * @return int the id
     */
    public function getUserId(string $name = '', string $mail = ''): int
    {
        $user = $this->getUserFromName($name, $mail);
        if (is_array($user) && count($user) > 0 && isset($user['id'])) {
            return $user['id'];
        }

        return -1;
    }

    /**
     * Checks if the user exists
     *
     * @param array $data the data for the user (for example to create)
     * @return bool true = user exists already
     */
    public function exists(array $data): bool
    {
        // First look after the number
        if (isset($data['email']) && $data['email'] != '') {
            $user = $this->getUserFromName('', $data['email']);

            // If it exists
            if (is_array($user) && count($user) > 0 && isset($user['id']) && $user['id'] > 0) {
                return true;
            }
        }

        // When the name exists
        if (isset($data['name']) && $data['name'] != '') {
            $user = $this->getUserFromName($data['name']);

            // If it exists
            if (is_array($user) && count($user) > 0 && isset($user['id']) && $user['id'] > 0) {
                return true;
            }
        }

        return false;
    }
}