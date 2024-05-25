<?php

namespace ArtisanBuild\Docsidian;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class DocumentationSite
{
    public Collection $blade_files;

    public string $folio_path;

    public string $markdown_path;

    public Collection $navigation;

    public ObsidianConfiguration $obsidian_configuration;

    public function __construct(public array $configuration)
    {
        $this->folio_path = $this->configuration['folio_path'];
        $this->markdown_path = $this->configuration['md_path'];

        $this->obsidian_configuration = new ObsidianConfiguration($this->configuration);
        $this->blade_files = collect(File::allFiles($this->configuration['md_path']))->filter(fn ($file) => str_ends_with($file->getFilename(), '.md'))->map(function ($file) {
            return new DocumentationPage(site: $this, markdown_file: $file);
        });
        $this->navigation = collect();
    }
}
