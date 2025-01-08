<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DecoratesHashTags;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class DecorateHashTagsAsFluxBadges implements DecoratesHashTags
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $pattern = '/#\b[a-zA-Z0-9]+/';

        preg_match_all($pattern, $page->markdown, $matches, PREG_SET_ORDER);

        $replace = collect($matches)->mapWithKeys(fn ($match, $key) => [$match[0] => '<flux:badge color="'.config('docsidian.hashtag_color', 'lime').'">'.$match[0].'</flux:badge>'])->toArray();

        $page->markdown = Blade::render(Str::replace(array_keys($replace), array_values($replace), $page->markdown));

        return $next($page);
    }
}
