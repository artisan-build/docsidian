<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;

class WrapEachFileInTheLayoutComponent
{
    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        $layout = $site->configuration['layout'];
        $site->blade_files->map(fn ($file) => $file->lines->prepend(['content' => "<x-{$layout}>"]));
        $site->blade_files->map(fn ($file) => $file->lines->push(['content' => "</x-{$layout}>"]));

        return $next($site);
    }
}
