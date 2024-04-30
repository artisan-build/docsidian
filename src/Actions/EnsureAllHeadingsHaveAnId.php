<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EnsureAllHeadingsHaveAnId
{
    public Collection $ids;

    public function __construct()
    {
        $this->ids = collect();
    }

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $section = null;
        $site->blade_files->each(fn ($page) => $page->lines = $page->lines->map(function ($line) use (&$section) {
            if ($line->token === 'h2') {
                if (blank($line->id)) {
                    $line->id = Str::slug($line->text);
                }
                $line->content = str_replace('<h2>', '<h2 id="'.$line->id.'">', $line->content);
                $section = $line->id;
            }
            if ($line->token === 'h3') {
                if (blank($line->id)) {
                    $line->id = implode('-', array_filter([$section, Str::slug($line->text)]));
                }
                $line->content = str_replace('<h3>', '<h3 id="'.$line->id.'">', $line->content);
            }

            return $line;
        }));

        return $next($site);
    }
}
