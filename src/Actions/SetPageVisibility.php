<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationLine;
use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;
use Illuminate\Support\Collection;

class SetPageVisibility
{
    public Collection $abilities;

    public string $visibility = '';

    public function __construct()
    {
        $this->abilities = app(GetDefinedAbilities::class)();
    }

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $this->visibility = $site->configuration['default_visibility'];

        $site->blade_files->map(function ($page) {
            $page->lines->each(fn ($line) => $this->setVisibility($line));
            $page->lines->prepend(new DocumentationLine(
                page: $page, original_content: implode('', ['@can("docsidian-', $this->visibility, '")'])
            ));

            $page->lines->push(new DocumentationLine(
                page: $page, original_content: '@endcan'
            ));

            $this->abilities->each(fn ($ability) => $page->lines->map(fn ($line) => $line->stripTag($ability)));

            return $page;
        });

        return $next($site);
    }

    protected function setVisibility(DocumentationLine $line)
    {
        foreach ($line->tags as $tag) {

            if (in_array($tag, $this->abilities->toArray())) {
                $this->visibility = $tag;
            }
        }
    }
}
