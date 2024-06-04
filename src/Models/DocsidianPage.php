<?php

namespace ArtisanBuild\Docsidian\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class DocsidianPage extends Model
{
    use HasFactory;
    use Searchable;
}
