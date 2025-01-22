<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Providers;

use ArtisanBuild\Docsidian\Contracts\ConvertsMarkdownToHtml;
use ArtisanBuild\Docsidian\Contracts\DecoratesBlockQuoteCallouts;
use ArtisanBuild\Docsidian\Contracts\DecoratesHashTags;
use ArtisanBuild\Docsidian\Contracts\GeneratesOnPageNavigation;
use ArtisanBuild\Docsidian\Contracts\GetsTitle;
use ArtisanBuild\Docsidian\Contracts\HandlesShortCodes;
use ArtisanBuild\Docsidian\Contracts\ProcessesBladeComponents;
use ArtisanBuild\Docsidian\Contracts\RestoresCodeBlocks;
use ArtisanBuild\Docsidian\Contracts\StylesHeadings;
use ArtisanBuild\Docsidian\Contracts\StylesLinks;
use ArtisanBuild\Docsidian\Contracts\StylesPlainText;
use ArtisanBuild\Docsidian\Contracts\TemporarilyRemovesCodeBlocks;
use ArtisanBuild\Docsidian\Pipeline\ConvertMarkdownToHtml;
use ArtisanBuild\Docsidian\Pipeline\DecorateBlockQuoteCallouts;
use ArtisanBuild\Docsidian\Pipeline\DecorateHashTagsAsFluxBadges;
use ArtisanBuild\Docsidian\Pipeline\GenerateOnPageNavigationUsingHeaders;
use ArtisanBuild\Docsidian\Pipeline\GetTitleFromFrontMatterOrFirstHeadingOne;
use ArtisanBuild\Docsidian\Pipeline\HandleShortCodes;
use ArtisanBuild\Docsidian\Pipeline\ProcessBladeComponents;
use ArtisanBuild\Docsidian\Pipeline\RestoreCodeBlocks;
use ArtisanBuild\Docsidian\Pipeline\StyleHeadingsWithFlux;
use ArtisanBuild\Docsidian\Pipeline\StyleLinksWithFlux;
use ArtisanBuild\Docsidian\Pipeline\StylePlainTextWithFlux;
use ArtisanBuild\Docsidian\Pipeline\TemporarilyRemoveCodeBlocks;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class DocsidianServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/docsidian.php', 'docsidian');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'docsidian');

        $this->app->bindIf(ConvertsMarkdownToHtml::class, ConvertMarkdownToHtml::class);
        $this->app->bindIf(StylesPlainText::class, StylePlainTextWithFlux::class);
        $this->app->bindIf(StylesHeadings::class, StyleHeadingsWithFlux::class);
        $this->app->bindIf(StylesLinks::class, StyleLinksWithFlux::class);
        $this->app->bindIf(GetsTitle::class, GetTitleFromFrontMatterOrFirstHeadingOne::class);
        $this->app->bindIf(GeneratesOnPageNavigation::class, GenerateOnPageNavigationUsingHeaders::class);
        $this->app->bindIf(ProcessesBladeComponents::class, ProcessBladeComponents::class);
        $this->app->bindIf(HandlesShortCodes::class, HandleShortCodes::class);
        $this->app->bindIf(DecoratesHashTags::class, DecorateHashTagsAsFluxBadges::class);
        $this->app->bindIf(DecoratesBlockQuoteCallouts::class, DecorateBlockQuoteCallouts::class);
        $this->app->bindIf(TemporarilyRemovesCodeBlocks::class, TemporarilyRemoveCodeBlocks::class);
        $this->app->bindIf(RestoresCodeBlocks::class, RestoreCodeBlocks::class);
        $this->app->bind('shortcodes', fn(): array => collect(File::files(config('docsidian.shortcode_path')))
            ->mapWithKeys(fn($file, $key) => [
                Str::slug(Str::headline($file->getFilenameWithoutExtension())) => implode('\\', [config('docsidian.shortcode_namespace'), $file->getFilenameWithoutExtension()]),
            ])->toArray());

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
