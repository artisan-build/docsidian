<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\DocsidianPage;

// TODO: How to get this file to be recognized by Pest?

expect()->extend('toProduce', function (string $expected_output, callable $using): void {
    // using test() instead of $this because of Pest's limitations for phpstan
    $page = new DocsidianPage(test()->value);
    $actual_output = $using($page, function ($page): DocsidianPage {
        return $page;
    })->markdown;

    expect($actual_output)->toBe($expected_output);
});
