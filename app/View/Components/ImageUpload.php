<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageUpload extends Component
{
    /**
     * @var \Illuminate\Support\Collection
     */
    public $images;

    /**
     * Accepts an optional collection/array of images. If none provided, tries to load
     * from App\Models\Images or App\Models\Image (whichever exists). Falls back to an empty collection.
     *
     * @param mixed $images
     */
    public function __construct($images = null)
    {
        if ($images instanceof \Illuminate\Support\Collection) {
            $this->images = $images;
            return;
        }

        if (is_array($images)) {
            $this->images = collect($images);
            return;
        }

        if ($images) {
            // any other truthy value - try to wrap into a collection
            $this->images = collect($images);
            return;
        }

        if (class_exists(\App\Models\Images::class)) {
            $this->images = \App\Models\Images::all();
            return;
        }

        $this->images = collect();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.image-upload');
    }
}
