<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;

// TODO: I really want to find a better solution for this. Perhaps run it through view() and have
// a template defined in the config, with a good default set.
class AddNavigationToAllRenderedFiles
{
    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        $navigation = json_decode(file_get_contents($site->configuration['folio_path'] . '/navigation.json'), true, 512, JSON_THROW_ON_ERROR);

        $content = '<x-slot name="navigation"><ul><li><a href="/' . $site->configuration['folio_uri'] .'">Documentation Home</a></li>';

        foreach ($navigation as $item) {
            $content .= '<li><a href="' . $item['uri'] . '">' . $item['title'] . '</a>';
        }

        $content .= '</ul></x-slot>';

        $site->blade_files->map(fn($page) => $page->lines->push([
            'content' => $content
        ]));

        return $next($site);
    }
}
