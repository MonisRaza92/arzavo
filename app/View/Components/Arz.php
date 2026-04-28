<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Arz extends Component
{
    public $section;
    public $tag;
    public $attributes;
    public $class;
    public $style;
    public $id;

    public function __construct($section, $tag = 'div', $attributes = [], $class = '', $style = '', $id = '')
    {
        $this->section = $section;
        $this->tag = $tag;
        $this->attributes = $attributes;
        $this->class = $class;
        $this->style = $style;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.arz', [
            'section' => $this->section,
            'tag' => $this->tag,
            'attributes' => $this->attributes,
            'class' => $this->class,
            'style' => $this->style,
            'id' => $this->id,
        ]);
    }
}
