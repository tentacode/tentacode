<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlogDetailController extends Controller
{
    public function __invoke(string $blogSlug): View
    {
        return view('blog/blog-detail', [
            'blogSlug' => $blogSlug,
        ]);
    }
}
