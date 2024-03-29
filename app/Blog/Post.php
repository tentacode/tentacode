<?php

namespace App\Blog;

use Carbon\CarbonImmutable;

final class Post
{
    public function __construct(
        public readonly string $slug,
        public readonly CarbonImmutable $publishedAt,
        public readonly PostContent $content
    ) {
    }
}
