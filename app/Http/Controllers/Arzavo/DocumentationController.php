<?php

namespace App\Http\Controllers\Arzavo;

class DocumentationController
{
    public function index()
    {
        return view('arzavo.website.documentation.index');
    }

    public function show($slug)
    {
        $viewPath = 'arzavo.website.documentation.pages.' . $slug;
        if (view()->exists($viewPath)) {
            return view($viewPath, compact('slug'));
        }
        abort(404);
    }
}
