<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MessageTemplate record.
 */
final class MessageTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private readonly ?int $layout,
        private ?string $body,
        private ?bool $createHtmlFromText,
        private ?bool $isDefault,
        private ?string $messageFormat,
        private ?string $name,
        private ?string $subject,
        private ?string $targetLink,
        private ?string $textBody,
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