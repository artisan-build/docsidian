<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\ConvertsMarkdownToHtml;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Embed\Embed;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\CommonMark\HighlightExtension;

class ConvertMarkdownToHtml implements ConvertsMarkdownToHtml
{
    private MarkdownConverter $converter;

    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $embedLibrary = new Embed;
        $embedLibrary->setSettings([
            'oembed:query_parameters' => [
                'maxwidth' => 800,
                'maxheight' => 600,
            ],
        ]);

        $environment = new Environment([
            'embed' => [
                'adapter' => new OscaroteroEmbedAdapter($embedLibrary),
                'allowed_domains' => [],
                'fallback' => 'link',
            ],
        ]);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new AttributesExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new EmbedExtension);
        $environment->addExtension(new HighlightExtension);

        $this->converter = new MarkdownConverter($environment);

        $converted = $this->converter->convert($page->markdown);
        $page->html = implode("\n\n", [
            '<div class="space-y-6">',
            $converted->getContent(),
            '</div>',
        ]);

        if ($converted instanceof RenderedContentWithFrontMatter) {
            $page->front_matter = $converted->getFrontMatter();
        }

        return $next($page);
    }
}
