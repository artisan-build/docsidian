<?php

use ArtisanBuild\Docsidian\EmbeddedMedia;

it('can get the configuration out of the container', function () {

    $embedded_media = app(EmbeddedMedia::class);

    expect($embedded_media->config)->toBe($this->config);
});

it('sets the correct path and uri', function () {

    $embedded_media = app(EmbeddedMedia::class)->make('lighthouse.png');

    expect($embedded_media->uri)->toBe(implode('/', [
        str_replace(public_path(), '', $this->config['media_path']),
        'lighthouse.png',
    ]))->and($embedded_media->path)->toBe(implode('/', [
        $this->config['media_path'],
        'lighthouse.png',
    ]))->and($embedded_media->source_path)->toBe(implode('/', [
        $this->config['md_path'],
        'lighthouse.png',
    ]));
});

it('throws if a referenced file does not exist in the md directory', function () {
    expect(fn () => app(EmbeddedMedia::class)->make('not-here.png'))->toThrow(RuntimeException::class);
});

it('copies from the source to the destination', function () {
    expect(file_exists(implode('/', [$this->config['media_path'], 'lighthouse.png'])))->toBeFalse();
    $embedded_media = app(EmbeddedMedia::class)->make('lighthouse.png');
    expect(file_exists($embedded_media->path))->toBeTrue();
});
