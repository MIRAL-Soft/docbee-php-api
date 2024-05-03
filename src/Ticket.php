<?php

namespace miralsoft\docbee\api;

class Ticket extends DocbeeAPICall
{
    /** @var Customer The customer class for api requests */
    protected Customer $customerC;

    public function __construct(Config $config)
    {
        parent::__construct($config);

        // Customer object
        $this->customerC = new Customer($config);
    }

    /**
     * Gives the count of all tickets
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
     * Gives the tickets
     *
     * @param int $limit The page
     * @param int $offset Pagesize
     * @param string $fields The fields, which should be loaded from the ticket (all fields = * | seperate fields seperate with , (f.e. 'created,Ticket')
     * @param string $changedSince load all entries after given date
     * @return array The result
     */
    public function get(int $limit = -1, int $offset = -1, string $fields = '', string $changedSince = ''): array
    {
        $this->subFunction = '';
        $data = array();

        // Only if the fields are set, take them in the request
        if($limit != -1)    $data['limit'] = $limit;
        if($offset != -1)    $data['offset'] = $offset;
        if($fields != '')    $data['fields'] = $fields;
        if($changedSince != '') $data['changedSince'] = $changedSince;

        $result = $this->call($data);

        // Gets only the Tickets without other fields
        return is_array($result) && isset($result['ticket']) && is_array($result['ticket']) ? $result['ticket'] : [];
    }

    /**
     * Get the Ticket with this ID
     *
     * @param string $id The ID from the Ticket
     * @return array The Ticket
     */
    public function getTicket(string $id): array
    {
        $this->subFunction = $id;
        return $this->call(['fields' => '*']);
    }

    /**
     * Get Tickets from customer
     *
     * @param int $customerId
     * @return array the tickets
     */
    public function getTicketsFromCustomer(int $customerId): array
    {
        $this->subFunction = '';
        $tickets = $this->call(['customer' => $customerId, 'fields' => '*']);

        return is_array($tickets) && count($tickets) > 0 && isset($tickets['ticket']) ? $tickets['ticket'] : [];
    }

    /**
     * Gets Tickets from customer with the customer number from erp
     *
     * @param string $customerNumber The customer number from erp
     * @return array the tickets
     */
    public function getTicketsFromCustomerNumber(string $customerNumber): array
    {
        // Get the customer
        $customer = $this->customerC->getCustomerFromCustomerId($customerNumber);

        // If customer is found
        if (is_array($customer) && count($customer) > 0 && isset($customer['id'])) {
            return $this->getTicketsFromCustomer($customer['id']);
        }

        // otherwise try if the number is the id
        return $this->getTicketsFromCustomer($customerNumber);
    }

    /**
     * Gets tickets from given data
     *
     * @param array $data the filter
     * @return array the tickets
     */
    public function filterTickets(array $data): array
    {
        $this->subFunction = '';
        $result = $this->call($data);

        if (is_array($result) && isset($result['ticket']) && is_array($result['ticket']) && count($result['ticket']) > 0) {
            return $result['ticket'];
        }

        return [];
    }

    /**
     * Gets the ticket from the orderID from erp
     *
     * @param string $orderId The id from erp
     * @return array The ticket data if exists
     */
    public function getTicketsFromOrderId(string $customerNumber, string $orderId): array
    {
        $orderTickets = [];
        $tickets = $this->getTicketsFromCustomerNumber($customerNumber);

        // If tickets from customer exists
        if (is_array($tickets) && count($tickets) > 0) {
            foreach ($tickets as $ticket) {
                // Is this ticket the ticket for the order (find out with referenceNumber or erpReferenceNumber
                if ((isset($ticket['referenceNumber']) && $ticket['referenceNumber'] == $orderId) ||
                    (isset($ticket['erpReferenceNumber']) && $ticket['erpReferenceNumber'] == $orderId)) {
                    $orderTickets[] = $ticket;
                }
            }

        }

        return $orderTickets;
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
        return $this->call($data, RequestType::POST);
    }

    /**
     * Creates a ticket from a order in erp
     *
     * @param array $data The data fro new ticket
     * @return array
     */
    public function createFromOrder(array $data): array
    {
        if (isset($data['customer']) && isset($data['erpReferenceNumber'])) {
            // Search if the Ticket exists and skip it if exists
            $ticket = $this->getTicketsFromOrderId($data['customer'], $data['erpReferenceNumber']);
            if (count($ticket) > 0) return ['error' => 'ticket allready exists', 'errorCode' => 401, 'ticket' => $ticket[0]];

            return $this->create($data);
        }

        return ['error' => 'Parameter error', 'errorCode' => 400];
    }
}