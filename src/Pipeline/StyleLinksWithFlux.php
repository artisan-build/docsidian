<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DocsidianAction;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Str;

class StyleLinksWithFlux implements DocsidianAction
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $page->html = Str::replace('<a href="http://', '<flux:link external="true" href="http://', $page->html);
        $page->html = Str::replace('</a>', '</flux:link>', $page->html);

        $page->html = Str::replace('<a href="https://', '<flux:link external="true" href="https://', $page->html);
        $page->html = Str::replace('</a>', '</flux:link>', $page->html);

        $page->html = Str::replace('<a ', '<flux:link ', $page->html);
        $page->html = Str::replace('</a>', '</flux:link>', $page->html);

        return $next($page);
    }
}
