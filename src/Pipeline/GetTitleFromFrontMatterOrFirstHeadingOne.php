<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\GetsTitle;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use DOMDocument;

class GetTitleFromFrontMatterOrFirstHeadingOne implements GetsTitle
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        if (data_get($page->front_matter, 'title')) {
            $page->title = data_get($page->front_matter, 'title');
        } else {
            $dom = new DOMDocument;
            @$dom->loadHTML($page->html);
            $page->title = $dom->getElementsByTagName('h1')->item(0)->textContent ?? 'Untitled Documentation';
        }

        return $next($page);
    }
}
