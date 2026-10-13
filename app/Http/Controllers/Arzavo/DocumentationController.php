<?php

namespace App\Http\Controllers\Arzavo;

use Illuminate\Http\Request;

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

    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        return redirect()->route('docs.index', ['search' => $query]);
    }
}
