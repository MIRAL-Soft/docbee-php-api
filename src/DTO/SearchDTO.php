<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee search result response.
 */
final class SearchDTO extends AbstractDTO
{
    public function __construct(
        /** agreements search results */
        private readonly ?array $agreements,
        /** cost estimations search results */
        private readonly ?array $costEstimations,
        /** SLA profiles search results */
        private readonly ?array $slaProfiles,
        /** object categories search results */
        private readonly ?array $objectCategories,
        /** objects search results */
        private readonly ?array $objects,
        /** customer objects search results */
        private readonly ?array $customerObjects,
        /** customer contacts search results */
        private readonly ?array $customerContacts,
        /** customer locations search results */
        private readonly ?array $customerLocations,
        /** customer profiles search results */
        private readonly ?array $customerProfiles,
        /** customers search results */
        private readonly ?array $customers,
        /** DocBee scripts search results */
        private readonly ?array $docBeeScripts,
        /** contingents search results */
        private readonly ?array $contingents,
        /** DocBee documents search results */
        private readonly ?array $docBeeDocuments,
        /** service types search results */
        private readonly ?array $serviceTypes,
        /** priorities search results */
        private readonly ?array $priorities,
        /** service providers search results */
        private readonly ?array $serviceProviders,
        /** protocols search results */
        private readonly ?array $protocols,
        /** protocol templates search results */
        private readonly ?array $protocolTemplates,
        /** protocol template types search results */
        private readonly ?array $protocolTemplateTypes,
        /** status search results */
        private readonly ?array $status,
        /** ticket categories search results */
        private readonly ?array $ticketCategories,
        /** ticket link types search results */
        private readonly ?array $ticketLinkTypes,
        /** ticket messages search results */
        private readonly ?array $ticketMessages,
        /** tickets search results */
        private readonly ?array $tickets,
        /** users search results */
        private readonly ?array $users
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            agreements: isset($data['agreements']) && is_array($data['agreements']) ? $data['agreements'] : null,
            costEstimations: isset($data['costEstimations']) && is_array($data['costEstimations']) ? $data['costEstimations'] : null,
            slaProfiles: isset($data['slaProfiles']) && is_array($data['slaProfiles']) ? $data['slaProfiles'] : null,
            objectCategories: isset($data['objectCategories']) && is_array($data['objectCategories']) ? $data['objectCategories'] : null,
            objects: isset($data['objects']) && is_array($data['objects']) ? $data['objects'] : null,
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            customerContacts: isset($data['customerContacts']) && is_array($data['customerContacts']) ? $data['customerContacts'] : null,
            customerLocations: isset($data['customerLocations']) && is_array($data['customerLocations']) ? $data['customerLocations'] : null,
            customerProfiles: isset($data['customerProfiles']) && is_array($data['customerProfiles']) ? $data['customerProfiles'] : null,
            customers: isset($data['customers']) && is_array($data['customers']) ? $data['customers'] : null,
            docBeeScripts: isset($data['docBeeScripts']) && is_array($data['docBeeScripts']) ? $data['docBeeScripts'] : null,
            contingents: isset($data['contingents']) && is_array($data['contingents']) ? $data['contingents'] : null,
            docBeeDocuments: isset($data['docBeeDocuments']) && is_array($data['docBeeDocuments']) ? $data['docBeeDocuments'] : null,
            serviceTypes: isset($data['serviceTypes']) && is_array($data['serviceTypes']) ? $data['serviceTypes'] : null,
            priorities: isset($data['priorities']) && is_array($data['priorities']) ? $data['priorities'] : null,
            serviceProviders: isset($data['serviceProviders']) && is_array($data['serviceProviders']) ? $data['serviceProviders'] : null,
            protocols: isset($data['protocols']) && is_array($data['protocols']) ? $data['protocols'] : null,
            protocolTemplates: isset($data['protocolTemplates']) && is_array($data['protocolTemplates']) ? $data['protocolTemplates'] : null,
            protocolTemplateTypes: isset($data['protocolTemplateTypes']) && is_array($data['protocolTemplateTypes']) ? $data['protocolTemplateTypes'] : null,
            status: isset($data['status']) && is_array($data['status']) ? $data['status'] : null,
            ticketCategories: isset($data['ticketCategories']) && is_array($data['ticketCategories']) ? $data['ticketCategories'] : null,
            ticketLinkTypes: isset($data['ticketLinkTypes']) && is_array($data['ticketLinkTypes']) ? $data['ticketLinkTypes'] : null,
            ticketMessages: isset($data['ticketMessages']) && is_array($data['ticketMessages']) ? $data['ticketMessages'] : null,
            tickets: isset($data['tickets']) && is_array($data['tickets']) ? $data['tickets'] : null,
            users: isset($data['users']) && is_array($data['users']) ? $data['users'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return [];
    }

    public function getId(): ?int { return null; }
    public function getAgreements(): ?array { return $this->agreements; }
    public function getCostEstimations(): ?array { return $this->costEstimations; }
    public function getSlaProfiles(): ?array { return $this->slaProfiles; }
    public function getObjectCategories(): ?array { return $this->objectCategories; }
    public function getObjects(): ?array { return $this->objects; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getCustomerContacts(): ?array { return $this->customerContacts; }
    public function getCustomerLocations(): ?array { return $this->customerLocations; }
    public function getCustomerProfiles(): ?array { return $this->customerProfiles; }
    public function getCustomers(): ?array { return $this->customers; }
    public function getDocBeeScripts(): ?array { return $this->docBeeScripts; }
    public function getContingents(): ?array { return $this->contingents; }
    public function getDocBeeDocuments(): ?array { return $this->docBeeDocuments; }
    public function getServiceTypes(): ?array { return $this->serviceTypes; }
    public function getPriorities(): ?array { return $this->priorities; }
    public function getServiceProviders(): ?array { return $this->serviceProviders; }
    public function getProtocols(): ?array { return $this->protocols; }
    public function getProtocolTemplates(): ?array { return $this->protocolTemplates; }
    public function getProtocolTemplateTypes(): ?array { return $this->protocolTemplateTypes; }
    public function getStatus(): ?array { return $this->status; }
    public function getTicketCategories(): ?array { return $this->ticketCategories; }
    public function getTicketLinkTypes(): ?array { return $this->ticketLinkTypes; }
    public function getTicketMessages(): ?array { return $this->ticketMessages; }
    public function getTickets(): ?array { return $this->tickets; }
    public function getUsers(): ?array { return $this->users; }
}
