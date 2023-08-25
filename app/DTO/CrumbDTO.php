<?php

namespace App\DTO;

use JsonSerializable;

class CrumbDTO implements JsonSerializable
{
    protected string $title;
    protected string $url;
    protected bool $isActive;

    public function __construct(string $title, string $url, bool $isActive = true)
    {
        $this->title = $title;
        $this->url = $url;
        $this->isActive = $isActive;
    }

    public static function make(string $title, string $url, bool $isActive = true): static
    {
        return new static($title, $url, $isActive);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function deactivate(): static
    {
        $this->isActive = false;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'is_active' => $this->isActive,
        ];
    }
}
