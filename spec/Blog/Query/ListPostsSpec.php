<?php

namespace spec\App\Blog\Query;

use App\Blog\Query\ListPosts;
use PhpSpec\ObjectBehavior;

class ListPostsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ListPosts::class);
    }
}
