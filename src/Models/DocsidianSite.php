<?php

namespace ArtisanBuild\Docsidian\Models;

use ArtisanBuild\Docsidian\SiteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class DocsidianSite extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'folio_middleware' => 'array',
        'status' => SiteStatus::class,
    ];
}
