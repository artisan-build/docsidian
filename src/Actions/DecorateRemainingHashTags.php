<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class DecorateRemainingHashTags
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->each(function ($page) {
            $page->lines = $page->lines->map(function ($line) {
                $html = '<span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">#{{ tag }}</span>';
                preg_match_all('/#(\w+)/', $line->content, $matches);

                foreach ($matches[1] as $tag) {
                    $line->content = str_replace(
                        "#{$tag}",
                        str_replace('{{ tag }}', $tag, $html),
                        $line->content
                    );
                }

                return $line;
            });
        });

        return $next($site);
    }
}
