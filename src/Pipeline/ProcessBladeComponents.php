<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DocsidianAction;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class ProcessBladeComponents implements DocsidianAction
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $lines = explode("\n", $page->html);
        $tranformed = [];

        foreach ($lines as $line) {
            if (Str::contains($line, '<a href="x-')) {
                $line = Blade::render('<'.strip_tags($line).'>');
            }
            $transformed[] = $line;
        }

        $page->html = implode("\n", $transformed);

        return $next($page);
    }
}
