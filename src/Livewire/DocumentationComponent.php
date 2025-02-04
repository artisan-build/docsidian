<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Livewire;

use ArtisanBuild\Docsidian\DocsidianPage;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\View;
use Livewire\Component;

class DocumentationComponent extends Component
{
    public string $markdown_file = '';

    public string $content = '';

    public array $navigation = [];

    protected ?string $folder = null;

    protected ?string $doc = 'index';

    protected string $template = 'docsidian::documentation';

    protected DocsidianPage $page;

    public function mount(): void
    {
        /** @var Route $route */
        $route = request()->route();
        $this->folder = $route->parameter('folder', null);
        $this->doc = $route->parameter('doc', 'index');
        $this->markdown_file = implode('/', array_filter([config('docsidian.markdown_root'), $this->folder, "{$this->doc}.md"]));

        if (! File::exists($this->markdown_file)) {
            $this->template = 'docsidian::not-found';
        }

        $markdown = File::get($this->markdown_file);

        $this->page = new DocsidianPage($markdown);

        // TODO: Handle caching

        $this->content = Blade::render(
            Pipeline::send($this->page)
                ->through(config('docsidian.transformations'))
                ->thenReturn()
                ->html
        );

        View::share('title', $this->page->title);
        View::share('folder', $this->folder);

        // Build sidebar navigation
        $navigation = collect(File::directories(config('docsidian.markdown_root')))
            ->filter(fn (string $folder): bool => File::exists(implode('/', [$folder, 'index.md'])))
            ->map(function (string $folder): array {
                /** @var DocsidianPage $page */
                $page = Pipeline::send(new DocsidianPage(File::get(implode('/', [$folder, 'index.md']))))
                    ->through(config('docsidian.navigation_transformations'))
                    ->thenReturn();

                return [
                    'slug' => last(explode('/', $folder)),
                    'title' => $page->title,
                    'weight' => data_get($page->front_matter, 'weight', 500),
                    'pages' => data_get($page->front_matter, 'pages', []),
                    'href' => route('docsidian.documentation', ['folder' => last(explode('/', $folder))]),
                ];
            })->sortBy('weight');

        View::share('navigation', $navigation);
    }

    public function page(string $to): void
    {
        $this->redirect($to, true);
    }

    public function render(): ViewContract
    {
        return view($this->template)->layout('docsidian::components.documentation-layout');
    }
}
