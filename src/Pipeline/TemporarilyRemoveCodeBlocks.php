<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\TemporarilyRemovesCodeBlocks;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use DOMDocument;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class TemporarilyRemoveCodeBlocks implements TemporarilyRemovesCodeBlocks
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $html = $page->html;

        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        @$dom->loadHTML($page->html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $pres = $dom->getElementsByTagName('pre');

        $replacements = [];
        foreach ($pres as $pre) {
            $key = 'CODE_BLOCK_'.Str::uuid()->toString();

            // Store contents in the Laravel cache or context
            $replacements[$key] = $pre->ownerDocument->saveHTML($pre);

            // Replace <pre> tag with placeholder
            $newNode = $dom->createTextNode($key);
            $pre->parentNode->replaceChild($newNode, $pre);
        }
        Context::add('code_block_replacements', $replacements);

        $page->html = $dom->saveHTML();

        return $next($page);
    }
}
