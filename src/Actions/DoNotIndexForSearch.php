<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;

class DoNotIndexForSearch
{
    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        return $next($site);
    }
}
