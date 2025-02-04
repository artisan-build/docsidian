<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DocsidianAction;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Str;

class StylePlainTextWithFlux implements DocsidianAction
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $page->html = Str::replace('<p ', '<flux:text ', $page->html);
        $page->html = Str::replace('<p>', '<flux:text>', $page->html);
        $page->html = Str::replace('</p>', '</flux:text>', $page->html);

        return $next($page);
    }
}
