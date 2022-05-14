<?php

namespace spec\App\Blog;

use App\Blog\Post;
use App\Blog\PostContent;
use Carbon\CarbonImmutable;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostSpec extends ObjectBehavior
{
    function let(PostContent $postContent)
    {
        $this->beConstructedWith(
            'un-super-post',
            new CarbonImmutable('1984-09-30 01:02:03'),
            $postContent
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Post::class);
    }

    function it_has_a_slug()
    {
        $this->slug->shouldBe('un-super-post');
    }

    function it_has_a_published_date()
    {
        $this->publishedAt->shouldBeLike(new CarbonImmutable('1984-09-30 01:02:03'));
    }

    function it_has_a_content($postContent)
    {
        $this->content->shouldReturn($postContent);
    }

    function it_is_immutable()
    {
        $this->shouldThrowReadOnlyError(fn() => $this->slug = 'non');
        $this->shouldThrowReadOnlyError(fn() => $this->publishedAt = new CarbonImmutable());
        $this->shouldThrowReadOnlyError(fn() => $this->content = new PostContent(
            'title',
            'text',
            'html',
        ));
    }

    public function getMatchers(): array
    {
        return [
            'throwReadOnlyError' => function ($subject, $closure) {
                try {
                    $closure();
                } catch (\Throwable $e) {
                    if (strpos($e->getMessage(), 'Cannot modify readonly property') !== false) {
                        return true;
                    }

                    throw new \RuntimeException(sprintf(
                        'Expected "read-only" Error but got "%s" with message "%s".',
                        get_class($e),
                        $e->getMessage()
                    ));
                }

                return false;
            },
        ];
    }
}
