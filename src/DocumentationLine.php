<?php

namespace ArtisanBuild\Docsidian;

use Exception;
use SimpleXMLElement;

class DocumentationLine
{
    public string $content;

    public readonly string $token;

    public ?string $id;

    public array $tags;

    public string $text;

    public function __construct(
        public DocumentationPage $page,
        public readonly string $original_content,
    ) {
        $this->content = $this->original_content;
        $this->token = $this->getToken();
        $this->id = $this->getId();
        $this->text = strip_tags($this->original_content);
        $this->getTags();
    }

    public function getTags(): void
    {
        preg_match_all('/#(\w+)/', $this->original_content, $matches);

        $this->tags = $matches[1];
    }

    public function hasTag(string $tag): bool
    {
        return str_contains($this->content, "#{$tag}");
    }

    public function stripTag(string $tag): void
    {
        if (blank($tag)) {
            return;
        }

        $this->content = str_replace("#{$tag}", '', $this->content);
    }

    public function getId()
    {
        try {
            return data_get(current((array) new SimpleXMLElement($this->original_content)), 'id');
        } catch (Exception $e) {
            return '';
        }
    }

    public function getToken(): string
    {
        $result = explode(' ', ltrim(current(explode('>', $this->original_content)), '<'));

        return current($result);
    }
}
