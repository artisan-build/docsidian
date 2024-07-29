<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class EscapeBladeBreakingCharacters
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $site->blade_files->each(
            fn ($file) => $file->lines = $file->lines->map(fn ($line) => $this->addLiteral($line)));

        return $next($site);
    }

    protected function addLiteral($line)
    {
        $line->content = str_replace('@', '@@', $line->content);
        $line->content = str_replace('{{', '@{{', $line->content);

        return $line;

    }
}
