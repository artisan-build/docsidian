<?php

namespace ArtisanBuild\Docsidian\Commands;

use ArtisanBuild\Docsidian\Actions\AddNavigationToAllRenderedFiles;
use ArtisanBuild\Docsidian\Actions\HighlightCodeWithTempest;
use ArtisanBuild\Docsidian\Actions\BuildNavigation;
use ArtisanBuild\Docsidian\Actions\DecorateRemainingHashTags;

use ArtisanBuild\Docsidian\Actions\EnableLiveCode;
use ArtisanBuild\Docsidian\Actions\EnsureAllHeadingsHaveAnId;
use ArtisanBuild\Docsidian\Actions\EnsureAnIndexFileExists;
use ArtisanBuild\Docsidian\Actions\GenerateOnPageNavigation;
use ArtisanBuild\Docsidian\Actions\GetPageTitles;
use ArtisanBuild\Docsidian\Actions\ParseMarkdownFiles;
use ArtisanBuild\Docsidian\Actions\RemoveEmptyParagraphs;
use ArtisanBuild\Docsidian\Actions\SetBlockVisibility;
use ArtisanBuild\Docsidian\Actions\SetPageVisibility;
use ArtisanBuild\Docsidian\Actions\WrapEachFileInTheLayoutComponent;
use ArtisanBuild\Docsidian\Actions\WriteEachBladeFileToTheFolioPath;
use ArtisanBuild\Docsidian\Contracts\HighlightsCodeBlocks;
use ArtisanBuild\Docsidian\Contracts\IndexesSiteForSearch;
use ArtisanBuild\Docsidian\DocumentationSite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Pipeline;

class GenerateCommand extends Command
{
    public $signature = 'docsidian:generate';

    public $description = 'Generate the documentation folio pages';

    public function handle(): int
    {
        foreach (config('docsidian.sites') as $key => $site) {
            $this->info("Generating {$key}");

            File::deleteDirectory($site['folio_path']);
            File::ensureDirectoryExists($site['folio_path']);
            File::put(implode('/', [$site['folio_path'], 'index.blade.php']), '<p>Please add an index.md file to your markdown directory.</p>');


            Pipeline::send(new DocumentationSite($site))
                ->through([
                    BuildNavigation::class,
                    SetPageVisibility::class,
                    SetBlockVisibility::class,
                    EnableLiveCode::class,
                    HighlightsCodeBlocks::class,
                    DecorateRemainingHashTags::class,
                    EnsureAllHeadingsHaveAnId::class,
                    GenerateOnPageNavigation::class, //todo
                    RemoveEmptyParagraphs::class,
                    AddNavigationToAllRenderedFiles::class,
                    WrapEachFileInTheLayoutComponent::class,
                    WriteEachBladeFileToTheFolioPath::class,
                    IndexesSiteForSearch::class,
                ])
                ->thenReturn();
        }

        return self::SUCCESS;
    }

}