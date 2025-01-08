<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\GeneratesOnPageNavigation;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use DOMDocument;
use Illuminate\Support\Str;

class GenerateOnPageNavigationUsingHeaders implements GeneratesOnPageNavigation
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $dom = new DOMDocument;
        @$dom->loadHTML($page->html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        foreach (range(2, 6) as $level) {
            $tags = $dom->getElementsByTagName("h{$level}");
            foreach ($tags as $tag) {
                $slug = Str::slug($tag->textContent);
                if (! $tag->hasAttribute('id')) {
                    $tag->setAttribute('id', $slug);
                }
            }
        }

        $page->html = $dom->saveHTML();

        $dom = new DOMDocument;
        @$dom->loadHTML($page->html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $sections = [];
        $currentH2 = null;

        foreach ($dom->getElementsByTagName('*') as $node) {
            if ($node->tagName === 'h2') {
                $currentH2 = Str::slug($node->textContent);
                $sections[$currentH2] = [];
            } elseif ($node->tagName === 'h3' && $currentH2 !== null) {
                $sections[$currentH2][] = Str::slug($node->textContent);
            }
        }

        $navigation = view('docsidian::on-page-navigation', ['navigation' => $sections])->toHtml();

        $dom = new DOMDocument;
        @$dom->loadHTML($page->html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Make DomDocument happy
        $navigation = str_replace(['data-flux-link', 'data-flux-icon'], ['data-flux-link="true"', 'data-flux-icon="true"'], $navigation);

        $page->html = preg_replace(
            '/(<h1\b[^>]*>.*?<\/h1>)/is',
            '$1'.$navigation,
            $page->html,
            1,
        );

        return $next($page);
    }
}
