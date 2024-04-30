<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationSite;
use Closure;

class GenerateOnPageNavigation
{
    public function __invoke(DocumentationSite $site, Closure $next)
    {

        $site->blade_files->each(function ($page) {
            $nav = collect();
            $section = '';
            $page->lines->each(function ($line) use ($nav, &$section) {
                if ($line->token === 'h2') {
                    $section = $line->id;
                    $nav->push([
                        'id' => $line->id,
                        'text' => $line->text,
                        'parent' => '',
                    ]);
                }
                if ($line->token === 'h3') {
                    $nav->push([
                        'id' => $line->id,
                        'text' => $line->text,
                        'parent' => $section,
                    ]);
                }
            });
            $html = '<ul>';
            $section = '';
            $nav->each(function ($item) use (&$section, &$html) {
                $html .= implode('', [
                    '<li',
                    $item['parent'] === '' ? ' class="font-bold"' : ' class="pl-4"',
                    '><a href="#',
                    $item['id'],
                    '">',
                    $item['text'],
                    '</a></li>',
                ]);

                if ($section === $item['parent']) {
                    $html .= '</li>';
                }

                return $html;
            });

            $html .= '</ul>';

            $page->lines = $page->lines->map(function ($line) use ($html) {
                if ($line->token === 'h1') {
                    $line->content .= "\n".$html;
                }

                return $line;
            });
        });

        return $next($site);
    }
}
