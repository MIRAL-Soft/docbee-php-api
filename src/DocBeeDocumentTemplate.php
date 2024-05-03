<?php

namespace miralsoft\docbee\api;

class DocBeeDocumentTemplate extends DocbeeAPICall
{

    /**
     * Gives the count of all templates
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
     * Gives the Template
     *
     * @param int $limit The page
     * @param int $offset Pagesize
     * @param string $fields The fields, which should be loaded from the template (all fields = * | seperate fields seperate with , (f.e. 'created,customerId')
     * @param string $sortings Sort the response by field asc or desc (Examples: id asc)
     * @param string $search This string will searched in the template
     * @return array The result
     */
    public function get(int $limit = -1, int $offset = -1, string $fields = '', string $sortings = '', string $search = ''): array
    {
        $this->subFunction = '';
        $data = array();

        // Only if the fields are set, take them in the request
        if ($limit != -1) $data['limit'] = $limit;
        if ($offset != -1) $data['offset'] = $offset;
        if ($fields != '') $data['fields'] = $fields;
        if ($sortings != '') $data['sortings'] = $sortings;
        if ($search != '') $data['search'] = $search;

        $result = $this->call($data);

        // Gets only the templates without other fields
        return is_array($result) && isset($result['docBeeDocumentTemplate']) && is_array($result['docBeeDocumentTemplate']) ? $result['docBeeDocumentTemplate'] : [];
    }

    /**
     * Get the template with this ID
     *
     * @param string $id The ID from the template
     * @return array The Customer
     */
    public function getTemplate(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Creates a customer with the given fields
     *
     * @param array $data The data for the new template (see https://pcs.docbee.com/restApi/v1/documentation#post-/v1/docBeeDocumentTemplate)
     * @return array
     */
    public function create(array $data): array
    {
        $this->subFunction = '';
        return $this->call($data, RequestType::POST);
    }

    /**
     * Edits the given template
     *
     * @param int $id The id from template
     * @param array $data the data to change
     * @return array The changed template
     */
    public function editTemplate(int $id, array $data): array
    {
        $this->subFunction = $id;

        // Search existing contact
        $contact = $this->getTemplate($id);
        if (!is_array($contact) || count($contact) <= 0) {
            return ['error' => 'Template not exists', 'errorCode' => 405];
        }

        // Create the template
        return $this->call($data, RequestType::PUT);
    }
}