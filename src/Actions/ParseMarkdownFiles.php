<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationPage;
use ArtisanBuild\Docsidian\DocumentationSite;
use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class ParseMarkdownFiles
{
    private MarkdownConverter $converter;

    public function __construct()
    {
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AttributesExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    public function __invoke(DocumentationSite $site, \Closure $next)
    {
        $site->markdown_files->each(/**
         * @throws CommonMarkException
         */ function ($file) use ($site) {
            $file_name = ltrim(str_replace($site->configuration['md_path'], '', str_replace('.md', '.blade.php', $file->getPathname())), '/');

            $site->blade_files->push(new DocumentationPage(
                site: $this,
                file_name: $file_name,
                original_content: $this->converter->convert($file->getContents()),
                parent: ltrim(str_replace($site->configuration['folio_path'], '', File::dirname($file_name)), '/')
            ));
        });

        return $next($site);
    }
}
