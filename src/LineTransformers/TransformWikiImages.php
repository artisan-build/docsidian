<?php

namespace ArtisanBuild\Docsidian\LineTransformers;

use Closure;

class TransformWikiImages
{
    public function __invoke(string $line, Closure $next): string
    {
        return $next($line);
    }
}
