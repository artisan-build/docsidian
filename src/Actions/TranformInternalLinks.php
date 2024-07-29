<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class TranformInternalLinks
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->each(function ($page) {
            $page->lines = $page->lines->map(function ($line) {
                $line->content = str_replace('index.md"', '"', $line->content);
                $line->content = str_replace('.md"', '"', $line->content);

                return $line;
            });
        });

        return $next($site);
    }
}
