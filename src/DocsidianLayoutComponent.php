<?php

namespace ArtisanBuild\Docsidian;

use Illuminate\View\Component;

class DocsidianLayoutComponent extends Component
{
    public $navigation;

    public function render()
    {
        return view('docsidian::components.docsidian');
    }
}
