<?php

namespace spec\App\Blog\Query;

use App\Blog\Query\GetPostBySlug;
use PhpSpec\ObjectBehavior;

class GetPostBySlugSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GetPostBySlug::class);
    }
}
