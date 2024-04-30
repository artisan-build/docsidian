<?php

namespace ArtisanBuild\Docsidian;

use ArtisanBuild\Docsidian\Commands\GenerateCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Folio\Folio;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ArtisanBuild\Docsidian\Commands\InstallCommand;

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
            ->hasCommand(InstallCommand::class)
            ->hasCommand(GenerateCommand::class);
    }

    public function packageBooted(): void
    {
        if (! config('docsidian.installed')) {
            return;
        }

        Blade::component(DocsidianLayoutComponent::class, 'docsidian');

        foreach (config('docsidian.sites') as $site) {
            Folio::path($site['folio_path'])
                ->uri($site['folio_uri'])
                ->middleware($site['folio_middleware']);
        }


    }
}
