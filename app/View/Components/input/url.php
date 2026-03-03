<?php
namespace App\View\Components\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Tenant\Page;
use App\Models\Tenant\Course;

class Url extends Component
{
    public $urls;

    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | REQUEST LEVEL CACHE
        |--------------------------------------------------------------------------
        */

        static $cachedUrls = null;

        if ($cachedUrls === null) {

            $pages = Page::select('name', 'slug')
                ->get()
                ->map(fn($p) => [
                    'label' => $p->name,
                    'url' => url($p->slug)
                ]);

            $courses = Course::select('title', 'slug')
                ->get()
                ->map(fn($c) => [
                    'label' => $c->title,
                    'url' => url('/course/' . $c->slug)
                ]);

            $cachedUrls = [
                'Pages' => $pages,
                'Courses' => $courses,
            ];
        }

        $this->urls = $cachedUrls;
    }

    public function render(): View|Closure|string
    {
        return view('components.input.url');
    }
}