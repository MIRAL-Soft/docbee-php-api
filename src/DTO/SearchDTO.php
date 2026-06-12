<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee search result response.
 */
final class SearchDTO extends AbstractDTO
{
    public function __construct(
        /** @var list<mixed>|null agreements search results */
        private readonly ?array $agreements,
        /** @var list<mixed>|null cost estimations search results */
        private readonly ?array $costEstimations,
        /** @var list<mixed>|null SLA profiles search results */
        private readonly ?array $slaProfiles,
        /** @var list<mixed>|null object categories search results */
        private readonly ?array $objectCategories,
        /** @var list<mixed>|null objects search results */
        private readonly ?array $objects,
        /** @var list<mixed>|null customer objects search results */
        private readonly ?array $customerObjects,
        /** @var list<mixed>|null customer contacts search results */
        private readonly ?array $customerContacts,
        /** @var list<mixed>|null customer locations search results */
        private readonly ?array $customerLocations,
        /** @var list<mixed>|null customer profiles search results */
        private readonly ?array $customerProfiles,
        /** @var list<mixed>|null customers search results */
        private readonly ?array $customers,
        /** @var list<mixed>|null DocBee scripts search results */
        private readonly ?array $docBeeScripts,
        /** @var list<mixed>|null contingents search results */
        private readonly ?array $contingents,
        /** @var list<mixed>|null DocBee documents search results */
        private readonly ?array $docBeeDocuments,
        /** @var list<mixed>|null service types search results */
        private readonly ?array $serviceTypes,
        /** @var list<mixed>|null priorities search results */
        private readonly ?array $priorities,
        /** @var list<mixed>|null service providers search results */
        private readonly ?array $serviceProviders,
        /** @var list<mixed>|null protocols search results */
        private readonly ?array $protocols,
        /** @var list<mixed>|null protocol templates search results */
        private readonly ?array $protocolTemplates,
        /** @var list<mixed>|null protocol template types search results */
        private readonly ?array $protocolTemplateTypes,
        /** @var list<mixed>|null status search results */
        private readonly ?array $status,
        /** @var list<mixed>|null ticket categories search results */
        private readonly ?array $ticketCategories,
        /** @var list<mixed>|null ticket link types search results */
        private readonly ?array $ticketLinkTypes,
        /** @var list<mixed>|null ticket messages search results */
        private readonly ?array $ticketMessages,
        /** @var list<mixed>|null tickets search results */
        private readonly ?array $tickets,
        /** @var list<mixed>|null users search results */
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
    /** @return list<mixed>|null */
    public function getAgreements(): ?array { return $this->agreements; }
    /** @return list<mixed>|null */
    public function getCostEstimations(): ?array { return $this->costEstimations; }
    /** @return list<mixed>|null */
    public function getSlaProfiles(): ?array { return $this->slaProfiles; }
    /** @return list<mixed>|null */
    public function getObjectCategories(): ?array { return $this->objectCategories; }
    /** @return list<mixed>|null */
    public function getObjects(): ?array { return $this->objects; }
    /** @return list<mixed>|null */
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    /** @return list<mixed>|null */
    public function getCustomerContacts(): ?array { return $this->customerContacts; }
    /** @return list<mixed>|null */
    public function getCustomerLocations(): ?array { return $this->customerLocations; }
    /** @return list<mixed>|null */
    public function getCustomerProfiles(): ?array { return $this->customerProfiles; }
    /** @return list<mixed>|null */
    public function getCustomers(): ?array { return $this->customers; }
    /** @return list<mixed>|null */
    public function getDocBeeScripts(): ?array { return $this->docBeeScripts; }
    /** @return list<mixed>|null */
    public function getContingents(): ?array { return $this->contingents; }
    /** @return list<mixed>|null */
    public function getDocBeeDocuments(): ?array { return $this->docBeeDocuments; }
    /** @return list<mixed>|null */
    public function getServiceTypes(): ?array { return $this->serviceTypes; }
    /** @return list<mixed>|null */
    public function getPriorities(): ?array { return $this->priorities; }
    /** @return list<mixed>|null */
    public function getServiceProviders(): ?array { return $this->serviceProviders; }
    /** @return list<mixed>|null */
    public function getProtocols(): ?array { return $this->protocols; }
    /** @return list<mixed>|null */
    public function getProtocolTemplates(): ?array { return $this->protocolTemplates; }
    /** @return list<mixed>|null */
    public function getProtocolTemplateTypes(): ?array { return $this->protocolTemplateTypes; }
    /** @return list<mixed>|null */
    public function getStatus(): ?array { return $this->status; }
    /** @return list<mixed>|null */
    public function getTicketCategories(): ?array { return $this->ticketCategories; }
    /** @return list<mixed>|null */
    public function getTicketLinkTypes(): ?array { return $this->ticketLinkTypes; }
    /** @return list<mixed>|null */
    public function getTicketMessages(): ?array { return $this->ticketMessages; }
    /** @return list<mixed>|null */
    public function getTickets(): ?array { return $this->tickets; }
    /** @return list<mixed>|null */
    public function getUsers(): ?array { return $this->users; }
}
