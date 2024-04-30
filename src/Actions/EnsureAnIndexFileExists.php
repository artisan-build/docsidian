<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationPage;
use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class EnsureAnIndexFileExists
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        if ($site->blade_files->filter(fn ($file) => $file->file_name === implode('/', [$site->configuration['folio_path'], 'index.blade.php']))->isEmpty()) {
            $site->blade_files->push(new DocumentationPage(
                // TODO: Figure out how to create a blade file without a corresponding markdown file
            ));
        }

        return $next($site);
    }
}
