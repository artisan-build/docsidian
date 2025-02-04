<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DocsidianAction;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Str;

class HandleShortCodes implements DocsidianAction
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $replace = collect($this->extractShortcodes($page->markdown))
            ->filter(fn (array $code): bool => array_key_exists($code['key'], app('shortcodes')))
            ->mapWithKeys(fn (array $code, int $key): array => [$code['original'] => app(app('shortcodes')[$code['key']])(...$code['attributes'])])
            ->toArray();

        $page->markdown = Str::replace(array_keys($replace), array_values($replace), $page->markdown);

        return $next($page);
    }

    /**
     * @return array<
     *   array-key,
     *   array{
     *     key: string,
     *     attributes: array<string, string>,
     *     original: string,
     *   }
     * >
     */
    public function extractShortcodes(string $text): array
    {
        $pattern = '/\[(\w[\w-]*)\s*([^]]*)]/';
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $shortcodes = [];

        foreach ($matches as $match) {
            $key = $match[1];
            $attributes = $match[2];

            $parsedAttributes = [];
            if ($attributes) {
                preg_match_all('/(\w+)=(["\'])(.*?)\2/', $attributes, $attrMatches, PREG_SET_ORDER);
                foreach ($attrMatches as $attrMatch) {
                    $parsedAttributes[$attrMatch[1]] = $attrMatch[3];
                }
            }

            $shortcodes[] = [
                'key' => $key,
                'attributes' => $parsedAttributes,
                'original' => $match[0],
            ];
        }

        return $shortcodes;
    }
}
