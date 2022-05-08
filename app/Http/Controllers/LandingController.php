<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Blog\Query\ListPosts;

final class LandingController extends Controller
{
    const LANDING_POSTS_COUNT = 3;

    public function __construct(
        private ListPosts $listPosts
    ) {
    }

    public function __invoke(): View
    {
        $posts = $this->listPosts->__invoke(self::LANDING_POSTS_COUNT);

        return view('landing/landing', [
            'posts' => $posts,
        ]);
    }
}
