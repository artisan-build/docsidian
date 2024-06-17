<?php

namespace ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource\Pages;

use ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Artisan;

class CreateDocsidianSite extends CreateRecord
{
    protected static string $resource = DocsidianSiteResource::class;

    public function mount(): void
    {
        Artisan::call('docsidian:discover');
        Artisan::call('docsidian:generate');

        $this->redirect(url()->previous());
    }
}
