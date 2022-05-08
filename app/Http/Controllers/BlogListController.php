<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Blog\Query\ListPosts;

class BlogListController extends Controller
{
    public function __construct(
        private ListPosts $listPosts
    ) {
    }

    public function __invoke(): View
    {
        $posts = $this->listPosts->__invoke();

        return view('blog/blog-list', [
            'posts' => $posts,
        ]);
    }
}
