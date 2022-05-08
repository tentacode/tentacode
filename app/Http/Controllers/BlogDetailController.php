<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Blog\Query\GetPostBySlug;

class BlogDetailController extends Controller
{
    public function __construct(
        private GetPostBySlug $getPostBySlug
    ) {
    }

    public function __invoke(string $blogSlug): View
    {
        $post = $this->getPostBySlug->__invoke($blogSlug);

        return view('blog/blog-detail', [
            'post' => $post,
        ]);
    }
}
