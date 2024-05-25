<?php

use ArtisanBuild\Docsidian\EmbeddedMedia;
use ArtisanBuild\Docsidian\Tests\TestCase;
use Illuminate\Support\Facades\File;

uses(TestCase::class)->beforeEach(function () {
    $this->config = [
        'default_visibility' => 'protected',
        'folio_middleware' => ['web'],
        'folio_path' => resource_path('views/docs/test'),
        'folio_uri' => 'docs/test',
        'layout' => 'docsidian',
        'md_path' => __DIR__.'/md',
        'md_repo' => 'artisan-build/docsidian-docs', // Only applies to hosted docs
        'media_path' => public_path('vendor/docsidian/test'),
        'obsidian_config' => __DIR__.'/md/.obsidian',
    ];
    app()->when(EmbeddedMedia::class)->needs('$config')->give($this->config);
})->afterEach(function () {
    if (File::isDirectory($this->config['folio_path'])) {
        File::deleteDirectory($this->config['folio_path']);
    }
    if (File::isDirectory($this->config['media_path'])) {
        File::deleteDirectory($this->config['media_path']);
    }
})->in(__DIR__);
