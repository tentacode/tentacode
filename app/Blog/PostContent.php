<?php

namespace App\Blog;

use function Safe\preg_match;

class PostContent
{
    public function __construct(
        public readonly string $title,
        public readonly string $text,
        public readonly string $html,
        public readonly string $image,
    ) {
    }

    public function getDescription():string
    {
        $description = $this->getExcerpt(200);
        if (preg_match('/<p id="post-description">(.*)<\/p>/', $this->html, $matches)) {
            $description = strip_tags($matches[1]);
        }

        return $description;
    }

    public function getExcerpt(int $characterCount): string
    {
        $text = trim($this->text);
        if (strlen($text) <= $characterCount) {
            return $text;
        }

        $text = substr($text, 0, $characterCount);

        $cutCount = strrpos($text, ' ');
        if ($cutCount === false) {
            $cutCount = strlen($text);
        }

        $text = substr($text, 0, $cutCount);
        $text = $text."…";

        return $text;
    }
}
