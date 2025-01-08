@if ($navigation !== [])
    <flux:navlist class="w-64 py-4">
        @foreach ($navigation as $top => $sub)
            <flux:navlist.item href="#{{ $top }}" icon="hashtag">{{ str($top)->headline() }}</flux:navlist.item>
            @foreach ($sub as $item)
                <flux:navlist.item href="#{{ $item }}" icon="hashtag" class="ml-4">{{ str($item)->headline() }}</flux:navlist.item>
            @endforeach
        @endforeach
    </flux:navlist>

@endif
