<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;

class WriteEachBladeFileToTheFolioPath
{
    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        $site->blade_files->each(fn ($file) => $file->write());

        return $next($site);
    }
}
