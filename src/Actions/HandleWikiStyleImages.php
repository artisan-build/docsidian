<?php

namespace ArtisanBuild\Docsidian\Actions;

use ArtisanBuild\Docsidian\DocumentationLine;
use ArtisanBuild\Docsidian\DocumentationSite;
use ArtisanBuild\Docsidian\EmbeddedMedia;
use Closure;

class HandleWikiStyleImages
{
    public string $image_path = '';

    public function __invoke(DocumentationSite $site, Closure $next)
    {
        $this->image_path = $site->obsidian_configuration->attachment_path;

        $site->blade_files->each(
            fn ($file) => $file->lines = $file->lines->map(fn ($line) => $this->transformImages($line)));

        return $next($site);
    }

    public function transformImages(DocumentationLine $line)
    {
        $line->content = $this->convertWikiImagesToHtml($line->content);

        return $line;

    }

    public function convertWikiImagesToHtml($markdown): string
    {
        $pattern = '/!\[\[([^\]]+)\]\]/';
        preg_match($pattern, $markdown, $matches);

        if (isset($matches[1])) {
            $image = app(EmbeddedMedia::class)->make($matches[1]);
            $markdown = str_replace($matches[0], '<img src="{{ asset(\''.$image->uri.'\') }}" alt="">', $markdown);
        }

        return $markdown;
    }
}
