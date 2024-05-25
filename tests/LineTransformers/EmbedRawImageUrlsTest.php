<?php

use ArtisanBuild\Docsidian\LineTransformers\EmbedRawImageUrls;
use Illuminate\Support\Facades\Pipeline;

it('transforms raw image urls without a caption', function () {
    $line = '<p>https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D</p>';
    $image = Pipeline::send($line)
        ->through([
            EmbedRawImageUrls::class,
        ])->thenReturn();

    expect($image)->toBe('<p><img class="object-contain md:object-scale-down" src="https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt=""></p>');
});

it('handles unexpected space before the image', function () {
    $line = '<p>    https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D</p>';
    $image = Pipeline::send($line)
        ->through([
            EmbedRawImageUrls::class,
        ])->thenReturn();

    expect($image)->toBe('<p><img class="object-contain md:object-scale-down" src="https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt=""></p>');
});

it('transforms raw image urls with a caption', function () {
    $line = '<p>https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D This has a caption</p>';
    $image = Pipeline::send($line)
        ->through([
            EmbedRawImageUrls::class,
        ])->thenReturn();

    expect($image)->toBe('<p><figure><img class="object-contain md:object-scale-down" src="https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt=""><figcaption>This has a caption</figcaption></figure></p>');
});

it('ignores images that are already in HTML tags', function () {
    $line = '<p><img class="object-contain md:object-scale-down" src="https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt=""></p>';

    $image = Pipeline::send($line)
        ->through([
            EmbedRawImageUrls::class,
        ])->thenReturn();

    expect($image)->toBe($line);
});

it('ignores images that are in markdown', function () {
    $line = '![image](https://images.unsplash.com/photo-1715073145727-393bbded41d9?q=80&w=3987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D)';

    $image = Pipeline::send($line)
        ->through([
            EmbedRawImageUrls::class,
        ])->thenReturn();

    expect($image)->toBe($line);
});
