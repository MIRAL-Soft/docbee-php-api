<?php

namespace miralsoft\docbee\api;

class Tag extends DocbeeAPICall
{
    /**
     * Returns a list of tags
     *
     * @param int $limit Limit the max items for the query
     * @param int $offset Offset
     * @param string $fields The fields, which should be loaded from the tags (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param bool $deactivated deactivated tag?
     * @return array The result
     */
    public function get(int $limit = 0, int $offset = 0, string $fields = '', bool $deactivated = false): array
    {
        $this->subFunction = '';
        $data = array('limit' => $limit, 'offset' => $offset, 'fields' => $fields, 'deactivated' => $deactivated);
        $result = $this->call($data);

        // Gets only the tags without other fields
        return is_array($result) && isset($result['tag']) && is_array($result['tag']) ? $result['tag'] : [];
    }

    /**
     * Get the Tag with this ID
     *
     * @param string $id The ID from the Tag
     * @return array The tag
     */
    public function getTag(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * TODO: Should be correct, but the function does not work correctly
     *
     * Get the data from the tag
     *
     * @param string $name The name from the tag
     * @return array The Data from Tag
     */
    public function getTagFromName(string $name): array
    {
        /* This is the correct version for getting the correct data from tag, but still not work correctly
        $this->subFunction = 'guess';
        $result = $this->call([['eid' => $name, 'data' => ['name' => $name]]], RequestType::POST);

        if (is_array($result) && count($result) == 1 && isset($result[0]['id'])) {
            return $this->getTag($result[0]['id']);
        }
        */

        $this->subFunction = '';
        $tags = $this->get();

        if (is_array($tags) && count($tags) > 0) {
            foreach ($tags as $tag) {
                if (isset($tag['name']) && $tag['name'] == $name && isset($tag['name']) && $tag['id'] > 0) return $tag;
            }
        }

        return [];
    }

    /**
     * Get the ID from Tag
     *
     * @param string $name The name from tag
     * @return int the id
     */
    public function getTagId(string $name): int
    {
        $tag = $this->getTagFromName($name);
        if(is_array($tag) && count($tag) > 0 && isset($tag['id'])){
            return $tag['id'];
        }

        return -1;
    }
}