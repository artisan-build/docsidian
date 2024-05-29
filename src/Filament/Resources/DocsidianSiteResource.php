<?php

namespace ArtisanBuild\Docsidian\Filament\Resources;

use ArtisanBuild\Docsidian\Models\DocsidianSite;
use ArtisanBuild\Docsidian\SiteStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocsidianSiteResource extends Resource
{
    protected static ?string $model = DocsidianSite::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('weight')->numeric(),
                Forms\Components\TextInput::make('description'),
                Forms\Components\FileUpload::make('image')->image(),

                Forms\Components\Select::make('status')->options(SiteStatus::class),

                Forms\Components\TextInput::make('folio_uri')->columnSpanFull(),
                Forms\Components\TextInput::make('folio_path')->columnSpanFull(),
                Forms\Components\TextInput::make('md_path')->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('weight'),
                Tables\Columns\TextColumn::make('name'),
                // TODO: I shouldn't have to do this. The casting should work.
                Tables\Columns\TextColumn::make('status')->getStateUsing(fn ($record) => $record->status->name),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('weight');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource\Pages\ListDocsidianSites::route('/'),
            'create' => \ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource\Pages\CreateDocsidianSite::route('/create'),
            'edit' => \ArtisanBuild\Docsidian\Filament\Resources\DocsidianSiteResource\Pages\EditDocsidianSite::route('/{record}/edit'),
        ];
    }
}
