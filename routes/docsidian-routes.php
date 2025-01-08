<?php

declare(strict_types=1);

use ArtisanBuild\Docsidian\Livewire\DocumentationComponent;
use Illuminate\Support\Facades\Route;

Route::middleware(config('docsidian.middleware'))
    ->get(config('docsidian.documentation_base_uri').'/{folder?}/{doc?}', DocumentationComponent::class)
    ->name('docsidian.documentation');
