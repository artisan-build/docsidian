<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DocsidianAction;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Str;

class StyleHeadingsWithFlux implements DocsidianAction
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $page->html = Str::replace('<h1 ', '<flux:heading size="xl" level="1" ', $page->html);
        $page->html = Str::replace('<h1>', '<flux:heading size="xl" level="1" >', $page->html);
        $page->html = Str::replace('</h1>', '</flux:heading>', $page->html);

        $page->html = Str::replace('<h2 ', '<flux:heading size="lg"  level="2" ', $page->html);
        $page->html = Str::replace('<h2>', '<flux:heading size="lg" level="2" >', $page->html);
        $page->html = Str::replace('</h2>', '</flux:heading>', $page->html);

        $page->html = Str::replace('<h3 ', '<flux:heading level="3" ', $page->html);
        $page->html = Str::replace('<h3>', '<flux:heading level="3" >', $page->html);
        $page->html = Str::replace('</h3>', '</flux:heading>', $page->html);

        $page->html = Str::replace('<h4 ', '<flux:heading  level="4" ', $page->html);
        $page->html = Str::replace('<h4>', '<flux:heading level="4" >', $page->html);
        $page->html = Str::replace('</h4>', '</flux:heading>', $page->html);

        $page->html = Str::replace('<h5 ', '<flux:heading  level="5" ', $page->html);
        $page->html = Str::replace('<h5>', '<flux:heading level="5" >', $page->html);
        $page->html = Str::replace('</h5>', '</flux:heading>', $page->html);

        return $next($page);
    }
}
