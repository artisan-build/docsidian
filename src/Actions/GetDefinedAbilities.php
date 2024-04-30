<?php

namespace ArtisanBuild\Docsidian\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class GetDefinedAbilities
{
    public function __invoke(): Collection
    {
        $abilities = [];
        foreach (Gate::abilities() as $key => $value) {
            if (str_starts_with($key, 'docsidian')) {
                $abilities[] = str_replace('docsidian-', '', $key);
            }
        }

        return collect($abilities);
    }
}
