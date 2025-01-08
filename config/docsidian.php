<?php

declare(strict_types=1);

return [
    'markdown_root' => env('DOCSIDIAN_MARKDOWN_ROOT', base_path('docs')),
    'documentation_base_uri' => env('DOCSIDIAN_BASE_URI', 'documentation'),
    'middleware' => ['web', 'auth:web'],
    'shortcode_path' => env('DOCSIDIAN_SHORTCODE_PATH', app_path('Docsidian/ShortCodes')),
    'shortcode_namespace' => env('DOCSIDIAN_SHORTCODE_NAMESPACE', 'App\Docsidian\ShortCodes'),
];
