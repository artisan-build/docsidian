<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\Livewire\DocumentationComponent;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;

describe('DocumentationComponent', function () {
    it('renders', function () {
        File::shouldReceive('exists')
            ->once()
            ->andReturnTrue();
        File::shouldReceive('get')
            ->once()
            ->andReturn('## Docsidian');
        File::shouldReceive('directories')
            ->once()
            ->andReturn([]);

        Livewire::test(DocumentationComponent::class)
            ->assertSee('Docsidian');
    });
});