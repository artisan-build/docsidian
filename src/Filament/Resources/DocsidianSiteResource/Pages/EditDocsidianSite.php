<?php

namespace ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource\Pages;

use ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocsidianSite extends EditRecord
{
    protected static string $resource = DocsidianSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
