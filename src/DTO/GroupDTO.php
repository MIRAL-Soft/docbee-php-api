<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Group record.
 */
final class GroupDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific group */
        private readonly ?int $id,
        /** modified timestamp */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** component */
        private readonly ?int $component,
        /** @var array|null list of entryMappings */
        private ?array $entryMappings,
        /** name */
        private ?string $name,
        /** internalName */
        private ?string $internalName,
        /** pdfName */
        private ?string $pdfName,
        /** avoidBreak */
        private ?bool $avoidBreak,
        /** pdfHide */
        private ?bool $pdfHide,
        /** isMultiGroup */
        private ?bool $isMultiGroup,
        /** showMultiGroupAsTable */
        private ?bool $showMultiGroupAsTable,
        /** multiGroupMinSize */
        private ?int $multiGroupMinSize,
        /** multiGroupMaxSize */
        private ?int $multiGroupMaxSize,
        /** multiGroupAddButtonLabel */
        private ?string $multiGroupAddButtonLabel,
        /** position */
        private ?int $position,
        /** groupContainer */
        private ?int $groupContainer,
        /** groupStyle */
        private ?int $groupStyle,
        /** pdfTableLayout */
        private ?bool $pdfTableLayout,
        /** orientation */
        private ?string $orientation,
        /** pdfOrientation */
        private ?string $pdfOrientation,
        /** pdfStyle */
        private ?string $pdfStyle
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            component: self::toInt($data['component'] ?? null),
            entryMappings: isset($data['entryMappings']) && is_array($data['entryMappings']) ? $data['entryMappings'] : null,
            name: self::toString($data['name'] ?? null),
            internalName: self::toString($data['internalName'] ?? null),
            pdfName: self::toString($data['pdfName'] ?? null),
            avoidBreak: isset($data['avoidBreak']) ? self::toBool($data['avoidBreak']) : null,
            pdfHide: isset($data['pdfHide']) ? self::toBool($data['pdfHide']) : null,
            isMultiGroup: isset($data['isMultiGroup']) ? self::toBool($data['isMultiGroup']) : null,
            showMultiGroupAsTable: isset($data['showMultiGroupAsTable']) ? self::toBool($data['showMultiGroupAsTable']) : null,
            multiGroupMinSize: self::toInt($data['multiGroupMinSize'] ?? null),
            multiGroupMaxSize: self::toInt($data['multiGroupMaxSize'] ?? null),
            multiGroupAddButtonLabel: self::toString($data['multiGroupAddButtonLabel'] ?? null),
            position: self::toInt($data['position'] ?? null),
            groupContainer: self::toInt($data['groupContainer'] ?? null),
            groupStyle: self::toInt($data['groupStyle'] ?? null),
            pdfTableLayout: isset($data['pdfTableLayout']) ? self::toBool($data['pdfTableLayout']) : null,
            orientation: self::toString($data['orientation'] ?? null),
            pdfOrientation: self::toString($data['pdfOrientation'] ?? null),
            pdfStyle: self::toString($data['pdfStyle'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'entryMappings' => $this->entryMappings,
            'name' => $this->name,
            'internalName' => $this->internalName,
            'pdfName' => $this->pdfName,
            'avoidBreak' => $this->avoidBreak,
            'pdfHide' => $this->pdfHide,
            'isMultiGroup' => $this->isMultiGroup,
            'showMultiGroupAsTable' => $this->showMultiGroupAsTable,
            'multiGroupMinSize' => $this->multiGroupMinSize,
            'multiGroupMaxSize' => $this->multiGroupMaxSize,
            'multiGroupAddButtonLabel' => $this->multiGroupAddButtonLabel,
            'position' => $this->position,
            'groupContainer' => $this->groupContainer,
            'groupStyle' => $this->groupStyle,
            'pdfTableLayout' => $this->pdfTableLayout,
            'orientation' => $this->orientation,
            'pdfOrientation' => $this->pdfOrientation,
            'pdfStyle' => $this->pdfStyle
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getComponent(): ?int { return $this->component; }
    public function getEntryMappings(): ?array { return $this->entryMappings; }
    public function getName(): ?string { return $this->name; }
    public function getInternalName(): ?string { return $this->internalName; }
    public function getPdfName(): ?string { return $this->pdfName; }
    public function getAvoidBreak(): ?bool { return $this->avoidBreak; }
    public function getPdfHide(): ?bool { return $this->pdfHide; }
    public function getIsMultiGroup(): ?bool { return $this->isMultiGroup; }
    public function getShowMultiGroupAsTable(): ?bool { return $this->showMultiGroupAsTable; }
    public function getMultiGroupMinSize(): ?int { return $this->multiGroupMinSize; }
    public function getMultiGroupMaxSize(): ?int { return $this->multiGroupMaxSize; }
    public function getMultiGroupAddButtonLabel(): ?string { return $this->multiGroupAddButtonLabel; }
    public function getPosition(): ?int { return $this->position; }
    public function getGroupContainer(): ?int { return $this->groupContainer; }
    public function getGroupStyle(): ?int { return $this->groupStyle; }
    public function getPdfTableLayout(): ?bool { return $this->pdfTableLayout; }
    public function getOrientation(): ?string { return $this->orientation; }
    public function getPdfOrientation(): ?string { return $this->pdfOrientation; }
    public function getPdfStyle(): ?string { return $this->pdfStyle; }
}
