<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;

class GetPageTitles
{
    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        $site->blade_files->map(function ($page) {
            $page->title = data_get($page->lines->where('token', 'h1')->first(), 'text', 'Untitled Page');
        });

        return $next($site);
    }
}
