<?php

namespace ArtisanBuild\Docsidian;

use ArtisanBuild\Docsidian\Actions\DoNotIndexForSearch;
use ArtisanBuild\Docsidian\Actions\GetDefinedAbilities;
use ArtisanBuild\Docsidian\Actions\HighlightCodeWithTempest;
use ArtisanBuild\Docsidian\Commands\DiscoverCommand;
use ArtisanBuild\Docsidian\Commands\GenerateCommand;
use ArtisanBuild\Docsidian\Commands\InstallCommand;
use ArtisanBuild\Docsidian\Contracts\HighlightsCodeBlocks;
use ArtisanBuild\Docsidian\Contracts\IndexesSiteForSearch;
use ArtisanBuild\Docsidian\Models\DocsidianSite;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Laravel\Folio\Folio;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DocsidianServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('docsidian')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_docsidian_table')
            ->hasCommands([
                InstallCommand::class,
                GenerateCommand::class,
                DiscoverCommand::class,
            ]);
    }

    public function registeringPackage()
    {
        $this->app->bind(IndexesSiteForSearch::class, DoNotIndexForSearch::class);
        $this->app->bind(HighlightsCodeBlocks::class, HighlightCodeWithTempest::class);

        if (class_exists(Filament::class)) {
            $this->app->register(\ArtisanBuild\Docsidian\DocumentationPanelProvider::class);
        }

    }

    public function packageBooted(): void
    {
        if (! config('docsidian.installed')) {
            return;
        }

        if (! Schema::hasTable('docsidian_sites')) {
            return;
        }

        Blade::component(DocsidianLayoutComponent::class, 'docsidian');

        foreach (DocsidianSite::all() as $site) {
            Folio::path($site->folio_path)
                ->uri($site->folio_uri)
                ->middleware($site->folio_middleware);
        }

        if (app(GetDefinedAbilities::class)()->isEmpty()) {
            Gate::define('docsidian-public', fn (?Authenticatable $user) => true);
            Gate::define('docsidian-protected', fn (?Authenticatable $user) => $user instanceof Authenticatable);
            Gate::define('docsidian-private', fn (?Authenticatable $user) => false);
        }
    }
}
