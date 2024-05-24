<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class RemoveEmptyParagraphs
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->map(function ($page) {
            $page->lines = $page->lines->filter(fn ($line) => $line->content != '<p></p>');
        });

        return $next($site);
    }
}
