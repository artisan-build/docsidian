<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\RestoresCodeBlocks;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class RestoreCodeBlocks implements RestoresCodeBlocks
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $replacements = Context::get('code_block_replacements', []);
        $page->html = Str::replace(array_keys($replacements), array_values($replacements), $page->html);

        return $next($page);
    }
}
