<?php

namespace App\View\Components\input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Tenant\Page;
use App\Models\Tenant\Course;

class url extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $pageUrls = Page::select('name', 'slug')
            ->get()
            ->map(fn($p) => [
                'type' => 'page',
                'label' => $p->name,
                'value' => $p->slug,
            ]);

        $courseUrls = Course::select('title', 'slug')
            ->get()
            ->map(fn($c) => [
                'type' => 'course',
                'label' => $c->title,
                'value' => $c->slug,
            ]);

        $urls = $pageUrls->merge($courseUrls)->values();

        return view('components.input.url', compact('urls'));
    }
}
