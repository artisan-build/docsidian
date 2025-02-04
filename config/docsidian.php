<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\Contracts\ConvertsMarkdownToHtml;
use ArtisanBuild\Docsidian\Contracts\DecoratesBlockQuoteCallouts;
use ArtisanBuild\Docsidian\Contracts\DecoratesHashTags;
use ArtisanBuild\Docsidian\Contracts\GeneratesOnPageNavigation;
use ArtisanBuild\Docsidian\Contracts\GetsTitle;
use ArtisanBuild\Docsidian\Contracts\HandlesShortCodes;
use ArtisanBuild\Docsidian\Contracts\RestoresCodeBlocks;
use ArtisanBuild\Docsidian\Contracts\StylesHeadings;
use ArtisanBuild\Docsidian\Contracts\StylesLinks;
use ArtisanBuild\Docsidian\Contracts\StylesPlainText;
use ArtisanBuild\Docsidian\Contracts\TemporarilyRemovesCodeBlocks;

return [
    'markdown_root' => env('DOCSIDIAN_MARKDOWN_ROOT', base_path('docs')),
    'documentation_base_uri' => env('DOCSIDIAN_BASE_URI', 'documentation'),
    'middleware' => ['web', 'auth:web'],
    'shortcode_path' => env('DOCSIDIAN_SHORTCODE_PATH', app_path('Docsidian/ShortCodes')),
    'shortcode_namespace' => env('DOCSIDIAN_SHORTCODE_NAMESPACE', 'App\Docsidian\ShortCodes'),

    'transformations' => [
        HandlesShortCodes::class,
        DecoratesHashTags::class,
        ConvertsMarkdownToHtml::class,
        TemporarilyRemovesCodeBlocks::class,
        DecoratesBlockQuoteCallouts::class,
        GeneratesOnPageNavigation::class,
        GetsTitle::class,
        StylesPlainText::class,
        StylesHeadings::class,
        StylesLinks::class,
        RestoresCodeBlocks::class,
    ],
];
