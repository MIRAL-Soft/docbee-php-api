<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateEntry record.
 */
final class ProtocolTemplateEntryDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateEntry */
        private readonly ?int $id,
        /** modified timestamp */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** released */
        private readonly ?bool $released,
        /** releasedDate */
        private readonly ?string $releasedDate,
        /** revision */
        private readonly ?int $revision,
        /** @var array|null list of elements */
        private readonly ?array $elements,
        /** name */
        private ?string $name,
        /** columnName */
        private ?string $columnName,
        /** description */
        private ?string $description,
        /** type */
        private ?string $type,
        /** required */
        private ?bool $required,
        /** requiredForComponentScript */
        private ?bool $requiredForComponentScript,
        /** deactivated */
        private ?bool $deactivated,
        /** caption */
        private ?string $caption,
        /** pdfCaption */
        private ?string $pdfCaption,
        /** changeMode */
        private ?string $changeMode,
        /** sendMessageToObservers */
        private ?bool $sendMessageToObservers,
        /** infoUrl */
        private ?string $infoUrl,
        /** value */
        private ?string $value,
        /** pdfValue */
        private ?string $pdfValue,
        /** dataFieldName */
        private ?string $dataFieldName,
        /** file */
        private ?int $file,
        /** allowedFileExtensions */
        private ?string $allowedFileExtensions,
        /** maxFileSizeInBytes */
        private ?int $maxFileSizeInBytes,
        /** maxImageResolution */
        private ?int $maxImageResolution,
        /** templateIdAsSetting */
        private ?int $templateIdAsSetting,
        /** inputHide */
        private ?bool $inputHide,
        /** inputType */
        private ?string $inputType,
        /** unit */
        private ?string $unit,
        /** customUnit */
        private ?string $customUnit,
        /** optionalFilter */
        private ?string $optionalFilter,
        /** forcedFilter */
        private ?string $forcedFilter,
        /** minValue */
        private ?float $minValue,
        /** maxValue */
        private ?float $maxValue,
        /** observerCategory */
        private ?int $observerCategory,
        /** selectionCategory */
        private ?int $selectionCategory,
        /** pdfStyle */
        private ?string $pdfStyle
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            released: isset($data['released']) ? self::toBool($data['released']) : null,
            releasedDate: self::toString($data['releasedDate'] ?? null),
            revision: self::toInt($data['revision'] ?? null),
            elements: isset($data['elements']) && is_array($data['elements']) ? $data['elements'] : null,
            name: self::toString($data['name'] ?? null),
            columnName: self::toString($data['columnName'] ?? null),
            description: self::toString($data['description'] ?? null),
            type: self::toString($data['type'] ?? null),
            required: isset($data['required']) ? self::toBool($data['required']) : null,
            requiredForComponentScript: isset($data['requiredForComponentScript']) ? self::toBool($data['requiredForComponentScript']) : null,
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            caption: self::toString($data['caption'] ?? null),
            pdfCaption: self::toString($data['pdfCaption'] ?? null),
            changeMode: self::toString($data['changeMode'] ?? null),
            sendMessageToObservers: isset($data['sendMessageToObservers']) ? self::toBool($data['sendMessageToObservers']) : null,
            infoUrl: self::toString($data['infoUrl'] ?? null),
            value: self::toString($data['value'] ?? null),
            pdfValue: self::toString($data['pdfValue'] ?? null),
            dataFieldName: self::toString($data['dataFieldName'] ?? null),
            file: self::toInt($data['file'] ?? null),
            allowedFileExtensions: self::toString($data['allowedFileExtensions'] ?? null),
            maxFileSizeInBytes: self::toInt($data['maxFileSizeInBytes'] ?? null),
            maxImageResolution: self::toInt($data['maxImageResolution'] ?? null),
            templateIdAsSetting: self::toInt($data['templateIdAsSetting'] ?? null),
            inputHide: isset($data['inputHide']) ? self::toBool($data['inputHide']) : null,
            inputType: self::toString($data['inputType'] ?? null),
            unit: self::toString($data['unit'] ?? null),
            customUnit: self::toString($data['customUnit'] ?? null),
            optionalFilter: self::toString($data['optionalFilter'] ?? null),
            forcedFilter: self::toString($data['forcedFilter'] ?? null),
            minValue: self::toFloat($data['minValue'] ?? null),
            maxValue: self::toFloat($data['maxValue'] ?? null),
            observerCategory: self::toInt($data['observerCategory'] ?? null),
            selectionCategory: self::toInt($data['selectionCategory'] ?? null),
            pdfStyle: self::toString($data['pdfStyle'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'columnName' => $this->columnName,
            'description' => $this->description,
            'type' => $this->type,
            'required' => $this->required,
            'requiredForComponentScript' => $this->requiredForComponentScript,
            'deactivated' => $this->deactivated,
            'caption' => $this->caption,
            'pdfCaption' => $this->pdfCaption,
            'changeMode' => $this->changeMode,
            'sendMessageToObservers' => $this->sendMessageToObservers,
            'infoUrl' => $this->infoUrl,
            'value' => $this->value,
            'pdfValue' => $this->pdfValue,
            'dataFieldName' => $this->dataFieldName,
            'file' => $this->file,
            'allowedFileExtensions' => $this->allowedFileExtensions,
            'maxFileSizeInBytes' => $this->maxFileSizeInBytes,
            'maxImageResolution' => $this->maxImageResolution,
            'templateIdAsSetting' => $this->templateIdAsSetting,
            'inputHide' => $this->inputHide,
            'inputType' => $this->inputType,
            'unit' => $this->unit,
            'customUnit' => $this->customUnit,
            'optionalFilter' => $this->optionalFilter,
            'forcedFilter' => $this->forcedFilter,
            'minValue' => $this->minValue,
            'maxValue' => $this->maxValue,
            'observerCategory' => $this->observerCategory,
            'selectionCategory' => $this->selectionCategory,
            'pdfStyle' => $this->pdfStyle
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getReleased(): ?bool { return $this->released; }
    public function getReleasedDate(): ?string { return $this->releasedDate; }
    public function getRevision(): ?int { return $this->revision; }
    public function getElements(): ?array { return $this->elements; }
    public function getName(): ?string { return $this->name; }
    public function getColumnName(): ?string { return $this->columnName; }
    public function getDescription(): ?string { return $this->description; }
    public function getType(): ?string { return $this->type; }
    public function getRequired(): ?bool { return $this->required; }
    public function getRequiredForComponentScript(): ?bool { return $this->requiredForComponentScript; }
    public function getDeactivated(): ?bool { return $this->deactivated; }
    public function getCaption(): ?string { return $this->caption; }
    public function getPdfCaption(): ?string { return $this->pdfCaption; }
    public function getChangeMode(): ?string { return $this->changeMode; }
    public function getSendMessageToObservers(): ?bool { return $this->sendMessageToObservers; }
    public function getInfoUrl(): ?string { return $this->infoUrl; }
    public function getValue(): ?string { return $this->value; }
    public function getPdfValue(): ?string { return $this->pdfValue; }
    public function getDataFieldName(): ?string { return $this->dataFieldName; }
    public function getFile(): ?int { return $this->file; }
    public function getAllowedFileExtensions(): ?string { return $this->allowedFileExtensions; }
    public function getMaxFileSizeInBytes(): ?int { return $this->maxFileSizeInBytes; }
    public function getMaxImageResolution(): ?int { return $this->maxImageResolution; }
    public function getTemplateIdAsSetting(): ?int { return $this->templateIdAsSetting; }
    public function getInputHide(): ?bool { return $this->inputHide; }
    public function getInputType(): ?string { return $this->inputType; }
    public function getUnit(): ?string { return $this->unit; }
    public function getCustomUnit(): ?string { return $this->customUnit; }
    public function getOptionalFilter(): ?string { return $this->optionalFilter; }
    public function getForcedFilter(): ?string { return $this->forcedFilter; }
    public function getMinValue(): ?float { return $this->minValue; }
    public function getMaxValue(): ?float { return $this->maxValue; }
    public function getObserverCategory(): ?int { return $this->observerCategory; }
    public function getSelectionCategory(): ?int { return $this->selectionCategory; }
    public function getPdfStyle(): ?string { return $this->pdfStyle; }
}
