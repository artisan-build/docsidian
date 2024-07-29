<?php

namespace ArtisanBuild\Docsidian\Commands;

use ArtisanBuild\Docsidian\Actions\AddNavigationToAllRenderedFiles;
use ArtisanBuild\Docsidian\Actions\BuildNavigation;
use ArtisanBuild\Docsidian\Actions\DecorateRemainingHashTags;
use ArtisanBuild\Docsidian\Actions\EnableLiveCode;
use ArtisanBuild\Docsidian\Actions\EnableMermaid;
use ArtisanBuild\Docsidian\Actions\EnsureAllHeadingsHaveAnId;
use ArtisanBuild\Docsidian\Actions\EscapeBladeBreakingCharacters;
use ArtisanBuild\Docsidian\Actions\GenerateOnPageNavigation;
use ArtisanBuild\Docsidian\Actions\HandleWikiStyleImages;
use ArtisanBuild\Docsidian\Actions\RemoveEmptyParagraphs;
use ArtisanBuild\Docsidian\Actions\SetBlockVisibility;
use ArtisanBuild\Docsidian\Actions\SetPageVisibility;
use ArtisanBuild\Docsidian\Actions\TranformInternalLinks;
use ArtisanBuild\Docsidian\Actions\WrapEachFileInTheLayoutComponent;
use ArtisanBuild\Docsidian\Actions\WriteEachBladeFileToTheFolioPath;
use ArtisanBuild\Docsidian\Contracts\HighlightsCodeBlocks;
use ArtisanBuild\Docsidian\Contracts\IndexesSiteForSearch;
use ArtisanBuild\Docsidian\DocumentationSite;
use ArtisanBuild\Docsidian\EmbeddedMedia;
use ArtisanBuild\Docsidian\Models\DocsidianSite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Pipeline;

class GenerateCommand extends Command
{
    public $signature = 'docsidian:generate';

    public $description = 'Generate the documentation folio pages';

    public function handle(): int
    {
        foreach (DocsidianSite::all() as $site) {
            $this->info("Generating {$site->name}");

            $site = $site->toArray();

            File::deleteDirectory($site['folio_path']);
            File::ensureDirectoryExists($site['folio_path']);
            File::put(implode('/', [$site['folio_path'], 'index.blade.php']), '<p>Please add an index.md file to your markdown directory.</p>');

            app()->when(EmbeddedMedia::class)->needs('$config')->give($site);

            Pipeline::send(new DocumentationSite($site))
                ->through([
                    EscapeBladeBreakingCharacters::class,
                    BuildNavigation::class,
                    SetPageVisibility::class,
                    SetBlockVisibility::class,
                    EnableLiveCode::class,
                    EnableMermaid::class,
                    HighlightsCodeBlocks::class,
                    DecorateRemainingHashTags::class,
                    TranformInternalLinks::class,
                    EnsureAllHeadingsHaveAnId::class,
                    GenerateOnPageNavigation::class, //todo
                    RemoveEmptyParagraphs::class,
                    HandleWikiStyleImages::class,
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
