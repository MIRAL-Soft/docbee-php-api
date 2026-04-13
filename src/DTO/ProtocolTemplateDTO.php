<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplate record.
 */
final class ProtocolTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?int $component,
        private readonly ?bool $released,
        private readonly ?string $releasedDate,
        private readonly ?int $revision,
        private ?array $actions,
        private ?string $additionalEmails,
        private ?string $additionalFaxes,
        private ?bool $allowInheritData,
        private ?string $color,
        private ?array $entryStyles,
        private ?string $finishText,
        private ?array $groupContainers,
        private ?array $groupStyles,
        private ?array $groups,
        private ?bool $hideDocumentViewInList,
        private ?string $inheritCriterion,
        private ?bool $instantFinish,
        private ?int $maxLifeTime,
        private ?int $messageTemplate,
        private ?string $mode,
        private ?string $name,
        private ?int $pdfLayout,
        private ?string $pdfTitle,
        private ?bool $sendMessageToCustomer,
        private ?bool $sendOnDocBeeDocumentFinished,
        private ?int $sortingNumber,
        private ?int $type,
        private ?bool $validateFinishTiles,
        private ?bool $withFinishTiles,
        private ?bool $withPlanningTimes
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            component: self::toInt($data['component'] ?? null),
            released: isset($data['released']) ? self::toBool($data['released']) : null,
            releasedDate: self::toString($data['releasedDate'] ?? null),
            revision: self::toInt($data['revision'] ?? null),
            actions: isset($data['actions']) && is_array($data['actions']) ? $data['actions'] : null,
            additionalEmails: self::toString($data['additionalEmails'] ?? null),
            additionalFaxes: self::toString($data['additionalFaxes'] ?? null),
            allowInheritData: isset($data['allowInheritData']) ? self::toBool($data['allowInheritData']) : null,
            color: self::toString($data['color'] ?? null),
            entryStyles: isset($data['entryStyles']) && is_array($data['entryStyles']) ? $data['entryStyles'] : null,
            finishText: self::toString($data['finishText'] ?? null),
            groupContainers: isset($data['groupContainers']) && is_array($data['groupContainers']) ? $data['groupContainers'] : null,
            groupStyles: isset($data['groupStyles']) && is_array($data['groupStyles']) ? $data['groupStyles'] : null,
            groups: isset($data['groups']) && is_array($data['groups']) ? $data['groups'] : null,
            hideDocumentViewInList: isset($data['hideDocumentViewInList']) ? self::toBool($data['hideDocumentViewInList']) : null,
            inheritCriterion: self::toString($data['inheritCriterion'] ?? null),
            instantFinish: isset($data['instantFinish']) ? self::toBool($data['instantFinish']) : null,
            maxLifeTime: self::toInt($data['maxLifeTime'] ?? null),
            messageTemplate: self::toInt($data['messageTemplate'] ?? null),
            mode: self::toString($data['mode'] ?? null),
            name: self::toString($data['name'] ?? null),
            pdfLayout: self::toInt($data['pdfLayout'] ?? null),
            pdfTitle: self::toString($data['pdfTitle'] ?? null),
            sendMessageToCustomer: isset($data['sendMessageToCustomer']) ? self::toBool($data['sendMessageToCustomer']) : null,
            sendOnDocBeeDocumentFinished: isset($data['sendOnDocBeeDocumentFinished']) ? self::toBool($data['sendOnDocBeeDocumentFinished']) : null,
            sortingNumber: self::toInt($data['sortingNumber'] ?? null),
            type: self::toInt($data['type'] ?? null),
            validateFinishTiles: isset($data['validateFinishTiles']) ? self::toBool($data['validateFinishTiles']) : null,
            withFinishTiles: isset($data['withFinishTiles']) ? self::toBool($data['withFinishTiles']) : null,
            withPlanningTimes: isset($data['withPlanningTimes']) ? self::toBool($data['withPlanningTimes']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'actions' => $this->actions,
            'additionalEmails' => $this->additionalEmails,
            'additionalFaxes' => $this->additionalFaxes,
            'allowInheritData' => $this->allowInheritData,
            'color' => $this->color,
            'entryStyles' => $this->entryStyles,
            'finishText' => $this->finishText,
            'groupContainers' => $this->groupContainers,
            'groupStyles' => $this->groupStyles,
            'groups' => $this->groups,
            'hideDocumentViewInList' => $this->hideDocumentViewInList,
            'inheritCriterion' => $this->inheritCriterion,
            'instantFinish' => $this->instantFinish,
            'maxLifeTime' => $this->maxLifeTime,
            'messageTemplate' => $this->messageTemplate,
            'mode' => $this->mode,
            'name' => $this->name,
            'pdfLayout' => $this->pdfLayout,
            'pdfTitle' => $this->pdfTitle,
            'sendMessageToCustomer' => $this->sendMessageToCustomer,
            'sendOnDocBeeDocumentFinished' => $this->sendOnDocBeeDocumentFinished,
            'sortingNumber' => $this->sortingNumber,
            'type' => $this->type,
            'validateFinishTiles' => $this->validateFinishTiles,
            'withFinishTiles' => $this->withFinishTiles,
            'withPlanningTimes' => $this->withPlanningTimes
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getComponent(): ?int { return $this->component; }
    public function getReleased(): ?bool { return $this->released; }
    public function getReleasedDate(): ?string { return $this->releasedDate; }
    public function getRevision(): ?int { return $this->revision; }
    public function getActions(): ?array { return $this->actions; }
    public function getAdditionalEmails(): ?string { return $this->additionalEmails; }
    public function getAdditionalFaxes(): ?string { return $this->additionalFaxes; }
    public function getAllowInheritData(): ?bool { return $this->allowInheritData; }
    public function getColor(): ?string { return $this->color; }
    public function getEntryStyles(): ?array { return $this->entryStyles; }
    public function getFinishText(): ?string { return $this->finishText; }
    public function getGroupContainers(): ?array { return $this->groupContainers; }
    public function getGroupStyles(): ?array { return $this->groupStyles; }
    public function getGroups(): ?array { return $this->groups; }
    public function getHideDocumentViewInList(): ?bool { return $this->hideDocumentViewInList; }
    public function getInheritCriterion(): ?string { return $this->inheritCriterion; }
    public function getInstantFinish(): ?bool { return $this->instantFinish; }
    public function getMaxLifeTime(): ?int { return $this->maxLifeTime; }
    public function getMessageTemplate(): ?int { return $this->messageTemplate; }
    public function getMode(): ?string { return $this->mode; }
    public function getName(): ?string { return $this->name; }
    public function getPdfLayout(): ?int { return $this->pdfLayout; }
    public function getPdfTitle(): ?string { return $this->pdfTitle; }
    public function getSendMessageToCustomer(): ?bool { return $this->sendMessageToCustomer; }
    public function getSendOnDocBeeDocumentFinished(): ?bool { return $this->sendOnDocBeeDocumentFinished; }
    public function getSortingNumber(): ?int { return $this->sortingNumber; }
    public function getType(): ?int { return $this->type; }
    public function getValidateFinishTiles(): ?bool { return $this->validateFinishTiles; }
    public function getWithFinishTiles(): ?bool { return $this->withFinishTiles; }
    public function getWithPlanningTimes(): ?bool { return $this->withPlanningTimes; }
}