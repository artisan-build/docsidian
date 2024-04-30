<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationPage;
use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;
use Illuminate\Support\Facades\File;

class BuildNavigation
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $navigation = collect();

        $site->blade_files->each(function ($page) use ($navigation, $site) {

            $weight = $this->getWeight($page);

            $navigation->push([
                'title' => $page->title,
                'uri' => $page->uri,
                'weight' => $weight,
            ]);

            $site->blade_files->each(fn ($page) => $page->lines->each(fn ($line) => $line->stripTag((string) $weight)));
        });

        File::put($site->configuration['folio_path'].'/navigation.json', $navigation->sortBy('weight')->filter(fn ($item) => $item['weight'] !== null)->toJson());

        return $next($site);
    }

    protected function getWeight(DocumentationPage $page)
    {
        $weight = (int) $page->lines->map(function ($line) {
            return collect($line->tags)->filter(fn ($tag) => is_numeric($tag));
        })->max()->first();

        return $weight > 0 ? $weight : null;
    }
}
