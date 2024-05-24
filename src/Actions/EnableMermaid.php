<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class EnableMermaid
{
    public bool $mermaid = false;

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->each(
            fn ($file) => $file->lines = $file->lines->map(fn ($line) => $this->enableMermaid($line)));

        return $next($site);
    }

    protected function enableMermaid($line)
    {
        if (str_contains($line->content, '</code')) {
            if ($this->mermaid) {
                $line->content = str_replace('</code>', '', $line->content);
                $this->mermaid = false;

                return $line;
            }
        }
        if (str_contains($line->content, 'class="language-mermaid"')) {
            $this->mermaid = true;
        }

        if ($this->mermaid) {
            $line->content = htmlspecialchars_decode($line->content);
            $line->content = str_replace('<pre><code class="language-mermaid">', '<pre class="mermaid">', $line->content);
        }

        return $line;

    }
}
