<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class EnableLiveCode
{
    public bool $live = false;

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->each(
            fn ($file) => $file->lines = $file->lines->map(fn ($line) => $this->enableLiveCode($line)));

        return $next($site);
    }

    protected function enableLiveCode($line)
    {
        if (str_contains($line->content, '</code')) {
            if ($this->live) {
                $line->content = str_replace('</pre>', '', $line->content);
                $line->content = str_replace('</code>', '', $line->content);
                $line->content = str_replace('@@', '@', $line->content);
                $line->content = str_replace('@{{', '{{', $line->content);
                $this->live = false;

                return $line;
            }
        }
        if (str_contains($line->content, 'class="language-live"')) {
            $this->live = true;
        }

        if ($this->live) {
            $line->content = htmlspecialchars_decode($line->content);
            $line->content = str_replace('<pre><code class="language-live">', '', $line->content);
        }

        return $line;

    }
}
