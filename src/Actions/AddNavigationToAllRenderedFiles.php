<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

// TODO: I really want to find a better solution for this. Perhaps run it through view() and have
// a template defined in the config, with a good default set.
class AddNavigationToAllRenderedFiles
{
    public Collection $content;

    public function __construct()
    {
        $this->content = collect();
    }

    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        $navigation = collect(json_decode(file_get_contents($site->configuration['folio_path'].'/navigation.json'), true, 512, JSON_THROW_ON_ERROR));

        $this->content->push('<x-slot name="navigation"><ul><li><a href="/'.$site->configuration['folio_uri'].'">'.$site->configuration['name'].'</a></li>');

        $navigation->where('parent', '')->each(function ($item) use ($navigation) {
            $this->content->push('<li><a href="'.$item['uri'].'">'.$item['title'].'</a>');
            $uri = explode('/', $item['uri']);
            $parent = '/'.last($uri);
            $navigation->where('parent', $parent)->each(function ($child) {
                $this->content->push('<li><a class="ml-4" href="'.$child['uri'].'">&raquo; '.$child['title'].'</a>');
            });
        });
        /* $navigation->each(function($item) {
             $parent = Str::uuid();
             if ($item['parent'] === '') {
                 $uri = explode('/', $item['uri']);
                 array_pop($uri);
                 $parent = '/'.last($uri);
                 dump($parent);

             }

             if ($item['parent'] === $parent) {
                 $this->content->push('<li><a class="ml-4" href="'.$item['uri'].'">'.$item['title'].'</a>');
             }
         });*/

        $this->content->push('</ul></x-slot>');

        $site->blade_files->map(fn ($page) => $page->lines->push([
            'content' => $this->content->implode("\n"),
        ]));

        return $next($site);
    }
}
