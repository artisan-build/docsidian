<?php

namespace ArtisanBuild\Docsidian\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    public $signature = 'docsidian:install';

    public $description = 'Install the docsidian package in your Laravel app';

    public function handle(): int
    {
        $config['md_path'] = text(
            label: 'Where would you like us to place the markdown files for your documentation relative to your site root?',
            default: 'docs',
            hint: 'We will pass this to base_path() in the generated config file.'
        );

        $config['folio_path'] = text(
            label: 'Where would you like us to place the rendered HTML for your documentation relative to your resources/views directory?',
            default: 'views/docs',
            hint: 'We will pass this to resource_path() in the generated config file.'
        );

        $config['folio_uri'] = text(
            label: 'What should we set as the uri for your docs?',
            default: 'docs',
            hint: 'Your docs will be visible at '.config('app.url').'/docs',
        );

        $config['default_visibility'] = select(
            label: 'What visiblity do you want for pages by default?',
            options: ['public', 'protected', 'private'],
            default: 'public',
            hint: 'This value will be applied to any page that does not have a visibility hashtag',
        );

        $this->info('The web middleware is applied to your docs by default. You may edit this in the docsidian configuration file.');

        File::ensureDirectoryExists(resource_path($config['folio_path']));
        File::ensureDirectoryExists(base_path($config['md_path']));

        $configContents = File::get(__DIR__.'/../../config/docsidian.php.stub');

        foreach (['default_visibility', 'md_path', 'folio_uri', 'folio_path'] as $key) {
            $configContents = str_replace("{{ $key }}", $config[$key], $configContents);
        }

        File::put(config_path('docsidian.php'), $configContents);

        $this->callSilently('vendor:publish', [
            '--tag' => 'docsidian-migrations',
        ]);

        $this->call('migrate');

        return self::SUCCESS;
    }
}
