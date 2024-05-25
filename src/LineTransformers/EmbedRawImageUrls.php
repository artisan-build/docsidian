<?php

namespace ArtisanBuild\Docsidian\LineTransformers;

use Closure;

class EmbedRawImageUrls
{
    public function __invoke(string $line, Closure $next): string
    {
        if (! filter_var(trim(current($chunks = explode(' ', trim(strip_tags($line))))), FILTER_VALIDATE_URL)) {
            return $next($line);
        }

        $headers = get_headers(current($chunks), true);

        if (! str_contains(data_get($headers, 'Content-Type'), 'image')) {

            return $next($line);
        }

        $line = count(array_filter($chunks)) === 1
            ? '<p><img class="object-contain md:object-scale-down" src="'.current($chunks).'" alt=""></p>'
            : '<p><figure><img class="object-contain md:object-scale-down" src="'
            .array_shift($chunks)
            .'" alt=""><figcaption>'
            .implode(' ', $chunks)
            .'</figcaption></figure></p>';

        return $next($line);
    }
}
