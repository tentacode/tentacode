<?php

namespace App\Blog\Factory;

use App\Blog\Post;
use App\Blog\PostContent;
use Carbon\CarbonImmutable;
use ParsedownExtra;

use function Safe\preg_match;
use function Safe\preg_replace;

final class PostFromMarkdown
{
    public function __construct(
        private ParsedownExtra $parser,
    ) {
    }

    public function __invoke(CarbonImmutable $date, string $slug, string $markdown): Post
    {
        $title = $this->getTitle($markdown);
        $html = $this->getHtmlFromMarkdown($markdown, $title);
        $text = strip_tags($html);

        $postContent = new PostContent(
            title: $title,
            text: $text,
            html: $html,
        );

        return new Post($slug, $date, $postContent);
    }

    private function getTitle(string $markdown): string
    {
        if (!preg_match('/^# (.*)$/m', $markdown, $matches)) {
            throw new \RuntimeException('Could not extract title from markdown.');
        }

        list(, $title) = $matches;

        return $title;
    }

    private function getHtmlFromMarkdown(string $markdown, string $title): string
    {
        // remove title from markdown
        $markdown = trim(str_replace(sprintf(
            '# %s',
            $title,
        ), '', $markdown));

        $html = $this->parser->text($markdown);
        $html = htmlspecialchars_decode($html);
        $html = $this->autoAnchor($html);

        return $html;
    }

    private function autoAnchor(string $html): string
    {
        // on each title, auto set anchor
        $html = preg_replace_callback('/<h([1-6])>(.*)<\/h([1-6])>/u', function ($matches) {
            $id = preg_replace('/[\W|_]/', '', $matches[2]);
            $id = strtolower($id);
            
            return sprintf('<h%s id="%s">%s</h%s>', $matches[1], $id, $matches[2], $matches[3]);
        }, $html);

        if ($html === null) {
            throw new \RuntimeException(sprintf(
                'Could not replace titles. Preg last error : %s.',
                preg_last_error()
            ));
        }

        return $html;
    }
}
