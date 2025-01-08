<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Str;

class StylePlainTextWithFlux
{
    public function __invoke(DocsidianPage $page, Closure $next)
    {
        $page->html = Str::replace('<p ', '<flux:text ', $page->html);
        $page->html = Str::replace('<p>', '<flux:text>', $page->html);
        $page->html = Str::replace('</p>', '</flux:text>', $page->html);

        return $next($page);
    }
}
