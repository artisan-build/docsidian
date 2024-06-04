<?php

namespace ArtisanBuild\Docsidian;

use Illuminate\Support\Facades\File;

class EmbeddedMedia
{
    public string $uri = '';

    public string $path = '';

    public string $source_path = '';

    public bool $exists = false;

    public ObsidianConfiguration $obsidian;

    public function __construct(public array $config)
    {
        $this->obsidian = new ObsidianConfiguration($config);
    }

    public function make(string $path)
    {
        $this->path = implode('/', [
            config('docsidian.media_path'),
            $path,
        ]);

        $this->source_path = implode('/', [
            $this->obsidian->attachment_path,
            $path,
        ]);

        $this->uri = implode('/', [
            str_replace(public_path(), '', rtrim(config('docsidian.media_path'), '/')),
            $path,
        ]);

        throw_if(! file_exists($this->source_path), "{$this->source_path} does not exist");

        $this->exists = file_exists($this->path);

        File::ensureDirectoryExists(dirname($this->path));

        File::copy($this->source_path, $this->path);

        return $this;
    }
}
