<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationLine;
use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;
use Tempest\Highlight\Highlighter;

class HighlightCodeWithTempest
{
    public string $code_block = '';

    public ?string $language = null;

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->each(
            fn ($file) => $file->lines = $file->lines->map(fn ($line) => $this->enableCodeHighlighting($line)));

        return $next($site);
    }

    protected function enableCodeHighlighting(DocumentationLine $line): DocumentationLine
    {
        if (str_contains($line->content, 'class="language-')) {
            preg_match('/class="([^"]+)"/', $line->content, $matches);
            $this->language = str_replace('language-', '', $matches[1]);
        }

        if ($this->language === 'live' || $this->language === 'mermaid') {
            $this->language = null;
        }

        if ($this->language !== null) {
            $this->code_block .= $line->content.PHP_EOL;
            if (str_contains($line->content, '</pre>')) {
                $highlighter = new Highlighter();

                $this->code_block = str_replace([
                    '<pre>',
                    '</pre>',
                    //'</code>',
                    '<code class="language-'.$this->language.'">',
                ], '', $this->code_block);

                $line->content = '<p><pre><code>'.$highlighter->parse(html_entity_decode($this->code_block), $this->language).'</code></pre></p>';
                $this->code_block = '';
                $this->language = null;

            } else {
                $line->content = '';
            }
        }

        return $line;
    }
}
