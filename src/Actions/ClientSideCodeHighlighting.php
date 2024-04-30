<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class ClientSideCodeHighlighting
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        return $next($site);
    }
}
