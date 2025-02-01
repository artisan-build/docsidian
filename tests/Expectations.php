<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\DocsidianPage;

// TODO: How to get this file to be recognized by Pest?

expect()->extend('toProduce', function (string $expected_output, callable $using): void {
    $page = new DocsidianPage($this->value);
    $actual_output = $using($page, function ($page): DocsidianPage {
        return $page;
    })->markdown;

    expect($actual_output)->toBe($expected_output);
});
