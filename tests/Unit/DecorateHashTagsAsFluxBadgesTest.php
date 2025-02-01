<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\DocsidianPage;
use ArtisanBuild\Docsidian\Pipeline\DecorateHashTagsAsFluxBadges;

// TODO: Get Pest to be aware of Expectations.php so this can live there.
expect()->extend('toProduce', function (string $expected_output, callable $using): void {
    $page = new DocsidianPage($this->value);
    $actual_output = $using($page, function ($page): DocsidianPage {
        return $page;
    })->markdown;

    expect($actual_output)->toBe($expected_output);
});

describe('Decorate Hash Tags As Flux Badges', function (): void {
    beforeEach(function (): void {
        $this->action = new DecorateHashTagsAsFluxBadges();
    });

    it('works', function (): void {
        $input = 'This is a #test';
        $output = 'This is a <flux:badge color="lime">#test</flux:badge>';

        expect($input)->toProduce($output, using: $this->action);
    });

    it('works with multiple hashtags', function (): void {
        $input = 'This is a #test and #another';
        $output = 'This is a <flux:badge color="lime">#test</flux:badge> and <flux:badge color="lime">#another</flux:badge>';

        expect($input)->toProduce($output, using: $this->action);
    });

    it('ignores blade syntax', function (): void {
        $input = '@if (true) poop @endif';
        $output = '@if (true) poop @endif';

        expect($input)->toProduce($output, using: $this->action);
    });
});
