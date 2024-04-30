<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SetBlockVisibility
{
    public Collection $abilities;

    public string $visibility = '';

    public function __construct()
    {
        $this->abilities = app(GetDefinedAbilities::class)();
    }

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $this->abilities = $this->abilities->filter(fn ($ability) => $ability !== $site->configuration['default_visibility']);

        $site->blade_files->map(function ($page) {
            $page->lines = $page->lines->map(function ($line) {
                foreach ($this->abilities->toArray() as $ability) {
                    if ($line->hasTag("end-{$ability}")) {
                        $this->visibility = '';
                    }
                }
                if ($this->visibility !== '') {
                    $line->content = implode(' ', [
                        "@can('docsidian-{$ability}')",
                        $line->content,
                        '@endcan',
                    ]);
                }
                foreach ($this->abilities->toArray() as $ability) {
                    if ($line->hasTag("start-{$ability}")) {
                        $this->visibility = $ability;
                    }
                }
                $line->content = Str::replace(["#start-{$ability}", "#end-{$ability}"], '', $line->content);

                return $line;
            });
        });

        return $next($site);
    }
}
