<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlogListController extends Controller
{
    public function __invoke(): View
    {
        return view('blog/blog-list', [
            'blogPosts' => [],
        ]);
    }
}
