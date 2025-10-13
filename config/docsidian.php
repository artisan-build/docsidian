<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\Pipeline\ConvertMarkdownToHtml;
use ArtisanBuild\Docsidian\Pipeline\DecorateBlockQuoteCallouts;
use ArtisanBuild\Docsidian\Pipeline\DecorateHashTagsAsFluxBadges;
use ArtisanBuild\Docsidian\Pipeline\GenerateOnPageNavigationUsingHeaders;
use ArtisanBuild\Docsidian\Pipeline\GetTitleFromFrontMatterOrFirstHeadingOne;
use ArtisanBuild\Docsidian\Pipeline\HandleShortCodes;
use ArtisanBuild\Docsidian\Pipeline\RestoreCodeBlocks;
use ArtisanBuild\Docsidian\Pipeline\StyleHeadingsWithFlux;
use ArtisanBuild\Docsidian\Pipeline\StyleLinksWithFlux;
use ArtisanBuild\Docsidian\Pipeline\StylePlainTextWithFlux;
use ArtisanBuild\Docsidian\Pipeline\TemporarilyRemoveCodeBlocks;

return [
    'markdown_root' => env('DOCSIDIAN_MARKDOWN_ROOT', base_path('docs')),
    'documentation_base_uri' => env('DOCSIDIAN_BASE_URI', 'documentation'),
    'middleware' => ['web', 'auth:web'],
    'shortcode_path' => env('DOCSIDIAN_SHORTCODE_PATH', app_path('Docsidian/ShortCodes')),
    'shortcode_namespace' => env('DOCSIDIAN_SHORTCODE_NAMESPACE', 'App\Docsidian\ShortCodes'),

    'transformations' => [
        HandleShortCodes::class,
        DecorateHashTagsAsFluxBadges::class,
        ConvertMarkdownToHtml::class,
        TemporarilyRemoveCodeBlocks::class,
        DecorateBlockQuoteCallouts::class,
        GenerateOnPageNavigationUsingHeaders::class,
        GetTitleFromFrontMatterOrFirstHeadingOne::class,
        StylePlainTextWithFlux::class,
        StyleHeadingsWithFlux::class,
        StyleLinksWithFlux::class,
        RestoreCodeBlocks::class,
    ],

    'navigation_transformations' => [
        ConvertMarkdownToHtml::class,
        GetTitleFromFrontMatterOrFirstHeadingOne::class,
    ],
];
