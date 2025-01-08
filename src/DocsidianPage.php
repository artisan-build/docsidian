<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian;

class DocsidianPage
{
    public string $html = '';

    public string $title = '';

    public array $navigation = [];

    public array $front_matter = [];

    public function __construct(public string $markdown) {}
}
