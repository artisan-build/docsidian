<?php

namespace ArtisanBuild\Docsidian\Models;

use ArtisanBuild\Docsidian\SiteStatus;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
