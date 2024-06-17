<?php

namespace ArtisanBuild\Docsidian;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Finder\SplFileInfo;

class DocumentationPage
{
    public Collection $lines;

    public Collection $anchors;

    public string $original_content = '';

    public string $file_name;

    public string $parent;

    public string $blade_file;

    public string $title = '';

    public string $uri;

    public function __construct(
        public DocumentationSite $site,
        public SplFileInfo $markdown_file,
    ) {
        $this->anchors = collect();
        $this->toHtml();
        $this->file_name = str_replace('.md', '.blade.php', $this->markdown_file->getFilename());
        $this->parent = str_replace($this->site->markdown_path, '', $this->markdown_file->getPath());
        $this->blade_file = implode('/', [
            $this->site->folio_path,
            $this->parent,
            $this->file_name,
        ]);
        $this->title = data_get($this->lines->where('token', 'h1')->first(), 'text', 'Untitled Page');

        $this->uri = str_replace('.blade.php', '', '/'.implode('/', array_filter([$this->site->configuration['folio_uri'], $this->parent, $this->file_name])));
        // I shouldn't  have to do this
        $this->uri = str_replace('//', '/', $this->uri);
    }

    public function toHtml()
    {
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AttributesExtension());

        $converter = new MarkdownConverter($environment);

        $this->original_content = $converter->convert($this->markdown_file->getContents());

        $this->lines = collect(explode(PHP_EOL, $this->original_content))
            ->map(fn ($line) => new DocumentationLine(page: $this, original_content: $line));
    }

    public function write(): void
    {
        File::ensureDirectoryExists(File::dirname($this->blade_file));
        File::put($this->blade_file, $this->lines->pluck('content')->implode(PHP_EOL));
    }
}
