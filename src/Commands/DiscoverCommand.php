<?php

namespace ArtisanBuild\Docsidian\Commands;

use ArtisanBuild\Docsidian\Models\DocsidianSite;
use ArtisanBuild\Docsidian\SiteStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DiscoverCommand extends Command
{
    public $signature = 'docsidian:discover {directory?}';

    public $description = 'Create a record for every folder in the markdown folder';

    public function handle(): int
    {

        $directories = File::directories($this->argument('directory') ?? config('docsidian.md_path'));

        foreach ($directories as $directory) {
            if (DocsidianSite::where('md_path', $directory)->exists()) {
                continue;
            }
            $key = last(explode(DIRECTORY_SEPARATOR, $directory));
            $site = DocsidianSite::create([
                'name' => $this->generateName($key),
                'description' => null,
                'image' => null,
                'weight' => (DocsidianSite::max('weight') ?? 0) + 100,
                'status' => SiteStatus::Hidden,
                'default_visibility' => config('docsidian.default_visibility'),
                'folio_middleware' => config('docsidian.folio_middleware'),
                'folio_path' => implode(DIRECTORY_SEPARATOR, [config('docsidian.folio_path'), $key]),
                'folio_uri' => implode('/', [config('docsidian.folio_uri'), $key]),
                'layout' => config('docsidian.layout'),
                'md_path' => $directory,
                'obsidian_config' => config('docsidian.obsidian_config'),

            ]);

            File::ensureDirectoryExists($site->folio_path);

            $this->info("Created record for {$key}");
        }



        // Now clean up any deleted directories
        foreach (DocsidianSite::get() as $site) {
            if (is_dir($site->md_path)) {
                continue;
            }

            $this->info("Deleted {$site->md_path}");

            $site->delete();
        }

        return self::SUCCESS;
    }

    public function generateName(string $key): string
    {
        if (str_contains('.', $key)) {
            return $key;
        }

        return Str::headline($key);
    }
}
