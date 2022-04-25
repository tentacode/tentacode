<?php

namespace spec\App\Blog;

use App\Blog\Post;
use App\Blog\PostContent;
use Carbon\CarbonImmutable;
use PhpSpec\ObjectBehavior;

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
}
