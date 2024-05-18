<?php

namespace ArtisanBuild\Docsidian;

use Illuminate\Support\Facades\File;

class ObsidianConfiguration
{
    public string $css;

    public string $attachment_path;

    /* TODO: It would be nice to let users use their Obsidian theme's CSS to style their documentation site. To
        that end, I am populating $this->css with the full path to the CSS that is bundled with the theme. I am not
        sure how much work it will take to create a template that has the correct classes in the correct places
        and whether we might also have to grab or generate a base theme css file to make this work. It's very much
        a seed of an idea at this point, but it would be nice to make it happen at some point if we can figure it out.
    */
    public function __construct(array $configuration)
    {
        // Calculate the full path to the Obsidian configuration folder
        $configuration_path = data_get($configuration, 'obsidian_config');
        if (! str_starts_with($configuration_path, base_path())) {
            $configuration_path = base_path($configuration_path);
        }

        $config = [
            'app' => json_decode(file_get_contents(implode('/', [$configuration_path, 'app.json'])), true),
            'appearance' => json_decode(file_get_contents(implode('/', [$configuration_path, 'appearance.json'])), true),
        ];

        $this->attachment_path = implode('/', [
            data_get($configuration, 'folio_uri'),
            str_replace('./', '', data_get($config, 'app.attachmentFolderPath')),
        ]);

        $this->css = implode('/', [
            $configuration_path,
            'themes',
            data_get($config, 'appearance.cssTheme'),
            'theme.css',
        ]);
    }
}