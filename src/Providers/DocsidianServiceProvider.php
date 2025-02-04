<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class DocsidianServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/docsidian.php', 'docsidian');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'docsidian');

        $this->app->bind('shortcodes', fn (): array => collect(File::files(config('docsidian.shortcode_path')))
            ->mapWithKeys(function (SplFileInfo $file, int $k): array {
                $key = str($file->getFilenameWithoutExtension())->headline()->slug()->toString();
                $value = implode('\\', [
                    config('docsidian.shortcode_namespace'),
                    $file->getFilenameWithoutExtension(),
                ]);

                return [$key => $value];
            })->toArray());

    }

    public function boot(): void
    {
        Blade::directive('live', function (string $action): string {
            if (! class_exists($action)) {
                return '<strong class="ml-4">Error: </strong>'.$action.' does not appear to be an invokable class';
            }

            return app($action)();
        });
    }
}
