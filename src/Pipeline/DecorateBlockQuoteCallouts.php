<?php

declare(strict_types=1);

namespace ArtisanBuild\Docsidian\Pipeline;

use ArtisanBuild\Docsidian\Contracts\DocsidianAction;
use ArtisanBuild\Docsidian\DocsidianPage;
use Closure;
use Illuminate\Support\Str;

class DecorateBlockQuoteCallouts implements DocsidianAction
{
    public function __invoke(DocsidianPage $page, Closure $next): DocsidianPage
    {
        $callouts = [
            'note' => [
                'class' => 'plain',
                'icon' => 'pencil-square',
            ],
            'abstract' => [
                'class' => 'plain',
                'icon' => 'document',
            ],
            'summary' => [
                'class' => 'plain',
                'icon' => 'numbered-list',
            ],
            'tldr' => [
                'class' => 'plain',
                'icon' => 'list-bullet',
            ],
            'info' => [
                'class' => 'info',
                'icon' => 'pencil-square',
            ],
            'todo' => [
                'class' => 'info',
                'icon' => 'check-circle',
            ],
            'tip' => [
                'class' => 'info',
                'icon' => 'light-bulb',
            ],
            'hint' => [
                'class' => 'info',
                'icon' => 'magnifying-glass-circle',
            ],
            'success' => [
                'class' => 'success',
                'icon' => 'check-badge',
            ],
            'check' => [
                'class' => 'success',
                'icon' => 'check',
            ],
            'done' => [
                'class' => 'success',
                'icon' => 'check-circle',
            ],
            'question' => [
                'class' => 'info',
                'icon' => 'question-mark-circle',
            ],
            'help' => [
                'class' => 'info',
                'icon' => 'lifebuoy',
            ],
            'faq' => [
                'class' => 'info',
                'icon' => 'question-mark-circle',
            ],
            'warning' => [
                'class' => 'warning',
                'icon' => 'shield-exclamation',
            ],
            'caution' => [
                'class' => 'warning',
                'icon' => 'shield-exclamation',
            ],
            'important' => [
                'class' => 'warning',
                'icon' => 'shield-exclamation',
            ],
            'attention' => [
                'class' => 'warning',
                'icon' => 'eye',
            ],
            'failure' => [
                'class' => 'danger',
                'icon' => 'x-circle',
            ],
            'fail' => [
                'class' => 'danger',
                'icon' => 'x-circle',
            ],
            'missing' => [
                'class' => 'danger',
                'icon' => 'magnifying-glass-minus',
            ],
            'danger' => [
                'class' => 'danger',
                'icon' => 'exclamation-circle',
            ],
            'error' => [
                'class' => 'danger',
                'icon' => 'exclamation-triangle',
            ],
            'bug' => [
                'class' => 'danger',
                'icon' => 'bug-ant',
            ],
            'example' => [
                'class' => 'plain',
                'icon' => 'clipboard-document-list',
            ],
            'quote' => [
                'class' => 'plain',
                'icon' => 'chat-bubble-bottom-center-text',
            ],
            'cite' => [
                'class' => 'plain',
                'icon' => 'chat-bubble-bottom-center-text',
            ],
        ];

        foreach ($callouts as $k => $v) {
            $page->html = Str::replace('[!'.$k.']', '<div class="flex"><span class="flex-shrink-0"><flux:icon.'.$v['icon'].' class="inline-flex mt-1 mr-2" /></span><span><flux:heading class="mb-2 '.$v['class'].'" size="xl"> '.ucfirst(mb_strtolower($k)).'</flux:heading></span></div>', $page->html);
            $page->html = Str::replace('[!'.ucfirst($k).']', '<div class="flex"><span class="flex-shrink-0"><flux:icon.'.$v['icon'].' class="inline-flex mt-1 mr-2" /></span><span><flux:heading class="mb-2 '.$v['class'].'" size="xl"> '.ucfirst(mb_strtolower($k)).'</flux:heading></span></div>', $page->html);
            $page->html = Str::replace('[!'.mb_strtoupper($k).']', '<div class="flex"><span class="flex-shrink-0"><flux:icon.'.$v['icon'].' class="inline-flex mt-1 mr-2" /></span><span><flux:heading class="mb-2 '.$v['class'].'" size="xl"> '.ucfirst(mb_strtolower($k)).'</flux:heading></span></div>', $page->html);

            $page->html = Str::replace('<strong>'.$k.'</strong>', '<span class="'.$v['class'].'"><flux:icon.'.$v['icon'].' class="inline -mt-1 mr-2" variant="mini" /><strong> '.ucfirst(mb_strtolower($k)).'</strong></span>', $page->html);
            $page->html = Str::replace('<strong>'.ucfirst($k).'</strong>', '<span class="'.$v['class'].'"><flux:icon.'.$v['icon'].' class="inline -mt-1 mr-2" variant="mini" /><strong> '.ucfirst(mb_strtolower($k)).'</strong></span>', $page->html);
            $page->html = Str::replace('<strong>'.mb_strtoupper($k).'</strong>', '<span class="'.$v['class'].'"><flux:icon.'.$v['icon'].' class="inline -mt-1 mr-2" variant="mini" /><strong> '.ucfirst(mb_strtolower($k)).'</strong></span>', $page->html);
        }

        return $next($page);

    }
}
