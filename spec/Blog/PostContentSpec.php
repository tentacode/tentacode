<?php

namespace spec\App\Blog;

use App\Blog\PostContent;
use PhpSpec\ObjectBehavior;

class PostContentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PostContent::class);
    }

    // function it_has_a_title()
    // {

    // }

    // function it_can_have_metadatas()
    // {

    // }

    // function it_has_an_excerpt()
    // {

    // }

    // function it_has_an_html_body()
    // {

    // }
}
