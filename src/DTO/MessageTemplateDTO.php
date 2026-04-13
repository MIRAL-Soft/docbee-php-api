<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MessageTemplate record.
 */
final class MessageTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific messageTemplate */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** Unique identifier representing a specific messageTemplate */
        private readonly ?int $layout,
        /** Body of the messageTemplate, may contain HTML */
        private ?string $body,
        /** create html from text */
        private ?bool $createHtmlFromText,
        /** is default */
        private ?bool $isDefault,
        /** Type of the messageTemplate */
        private ?string $messageFormat,
        /** Name of the messageTemplate */
        private ?string $name,
        /** Subject of the messageTemplate, required for all Email-Formats */
        private ?string $subject,
        /** target link */
        private ?string $targetLink,
        /** Body of the messageTemplate */
        private ?string $textBody,
        /** Type of the messageTemplate */
        private ?string $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            layout: self::toInt($data['layout'] ?? null),
            body: self::toString($data['body'] ?? null),
            createHtmlFromText: isset($data['createHtmlFromText']) ? self::toBool($data['createHtmlFromText']) : null,
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null,
            messageFormat: self::toString($data['messageFormat'] ?? null),
            name: self::toString($data['name'] ?? null),
            subject: self::toString($data['subject'] ?? null),
            targetLink: self::toString($data['targetLink'] ?? null),
            textBody: self::toString($data['textBody'] ?? null),
            type: self::toString($data['type'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'body' => $this->body,
            'createHtmlFromText' => $this->createHtmlFromText,
            'isDefault' => $this->isDefault,
            'messageFormat' => $this->messageFormat,
            'name' => $this->name,
            'subject' => $this->subject,
            'targetLink' => $this->targetLink,
            'textBody' => $this->textBody,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getLayout(): ?int { return $this->layout; }
    public function getBody(): ?string { return $this->body; }
    public function getCreateHtmlFromText(): ?bool { return $this->createHtmlFromText; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
    public function getMessageFormat(): ?string { return $this->messageFormat; }
    public function getName(): ?string { return $this->name; }
    public function getSubject(): ?string { return $this->subject; }
    public function getTargetLink(): ?string { return $this->targetLink; }
    public function getTextBody(): ?string { return $this->textBody; }
    public function getType(): ?string { return $this->type; }
}