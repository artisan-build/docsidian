@props(['sidebarOpen' => true])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ $title ?? 'Documentation' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        pre, code {
            color: #24292e;
            background-color: #fafafa;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .hl-keyword {
            color: #d73a49;
        }

        .hl-property {
            color: #34A853;
        }

        .hl-attribute {
            font-style: italic;
        }

        .hl-type {
            color: #EA4334;
        }

        .hl-generic {
            color: #9d3af6;
        }

        .hl-value {
            color: #032f62;
        }

        .hl-literal {
            color: #032f62;
        }

        .hl-number {
            color: #032f62;
        }

        .hl-variable {
            color: #e36209;
        }

        .hl-comment {
            color: #6a737d;
        }

        .hl-blur {
            filter: blur(2px);
        }

        .hl-strong {
            font-weight: bold;
        }

        .hl-em {
            font-style: italic;
        }

        .hl-addition {
            display: inline-block;
            min-width: 100%;
            background-color: #00FF0022;
        }

        .hl-deletion {
            display: inline-block;
            min-width: 100%;
            background-color: #FF000011;
        }

        .hl-gutter {
            display: inline-block;
            font-size: 0.9em;
            color: #555;
            padding: 0 1ch;
            margin-right: 1ch;
            user-select: none;
        }

        .hl-gutter-addition {
            background-color: #34A853;
            color: #fff;
        }

        .hl-gutter-deletion {
            background-color: #EA4334;
            color: #fff;
        }

        blockquote {
            margin: 0;
            padding: 1rem;
            background-color: #fafafa; /* zinc-50 */
            border: .05em solid #e4e4e7; /* zinc-200 */
            border-left: .25em solid #e4e4e7; /* zinc-200 */
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        blockquote:has(*.success) {
            background-color: #dcfce7; /* green-100 */
            border-color: #4ade80; /* green-400 */
        }

        blockquote:has(*.info) {
            background-color: #ecfeff; /* cyan-50 */
            border-color: #67e8f9; /* cyan-300 */
        }

        blockquote:has(*.warning) {
            background-color: #fffbeb; /* amber-50 */
            border-color: #fcd34d; /* amber-300 */
        }

        blockquote:has(*.danger) {
            background-color: #fef2f2; /* red-50 */
            border-color: #fca5a5; /* red-300 */
        }

    </style>

    @livewireStyles
    @fluxStyles
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <flux:brand href="#" logo="/images/app.png" name="Documentation" class="px-2 dark:hidden" />
    <flux:brand href="#" logo="/images/app.png" name="Documentation" class="px-2 hidden dark:flex" />

    <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass" />

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="{{ route('docsidian.documentation') }}">Home</flux:navlist.item>
        @foreach ($navigation as $item)
            <flux:navlist>
                <flux:navlist.group :heading="$item['title']" x-on:click="if ($el.querySelector('button').contains($event.target)) window.location.href = '{{ $item['href'] }}'" :expanded="$folder === $item['slug']" expandable>
                @foreach ($item['pages'] as $page)
                    <flux:navlist.item :href="$item['href'] . '/' . $page">{{ str($page)->headline() }}</flux:navlist.item>
                @endforeach
                </flux:navlist.group>

            </flux:navlist>
        @endforeach

    </flux:navlist>

    <flux:spacer />

    <flux:navlist variant="outline">
        <flux:navlist.item target="_blank" icon="document-text" href="https://docsidian.com">Powered by Docsidian</flux:navlist.item>
    </flux:navlist>
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:spacer />


</flux:header>

<flux:main>
    {{ $slot }}
</flux:main>

@livewireScripts
@fluxScripts
</body>
</html>
