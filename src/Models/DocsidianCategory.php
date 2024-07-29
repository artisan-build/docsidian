<?php

namespace ArtisanBuild\Docsidian\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @mixin Eloquent */
class DocsidianCategory extends Model
{
    protected $guarded = [];

    public function docsidian_sites(): HasMany
    {
        return $this->hasMany(DocsidianSite::class);
    }
}
