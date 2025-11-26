<?php

namespace App\Blog;

use Carbon\CarbonImmutable;

final class Post
{
    public function __construct(
        public readonly string $slug,
        public readonly CarbonImmutable $publishedAt,
        public readonly string $language,
        public readonly PostContent $content
    ) {
    }
}
