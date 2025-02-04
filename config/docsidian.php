<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\Pipeline;

return [
    'markdown_root' => env('DOCSIDIAN_MARKDOWN_ROOT', base_path('docs')),
    'documentation_base_uri' => env('DOCSIDIAN_BASE_URI', 'documentation'),
    'middleware' => ['web', 'auth:web'],
    'shortcode_path' => env('DOCSIDIAN_SHORTCODE_PATH', app_path('Docsidian/ShortCodes')),
    'shortcode_namespace' => env('DOCSIDIAN_SHORTCODE_NAMESPACE', 'App\Docsidian\ShortCodes'),

    'transformations' => [
        Pipeline\HandleShortCodes::class,
        Pipeline\DecorateHashTagsAsFluxBadges::class,
        Pipeline\ConvertMarkdownToHtml::class,
        Pipeline\TemporarilyRemoveCodeBlocks::class,
        Pipeline\DecorateBlockQuoteCallouts::class,
        Pipeline\GenerateOnPageNavigationUsingHeaders::class,
        Pipeline\GetTitleFromFrontMatterOrFirstHeadingOne::class,
        Pipeline\StylePlainTextWithFlux::class,
        Pipeline\StyleHeadingsWithFlux::class,
        Pipeline\StyleLinksWithFlux::class,
        Pipeline\RestoreCodeBlocks::class,
    ],

    'navigation_transformations' => [
        Pipeline\ConvertMarkdownToHtml::class,
        Pipeline\GetTitleFromFrontMatterOrFirstHeadingOne::class,
    ],
];
