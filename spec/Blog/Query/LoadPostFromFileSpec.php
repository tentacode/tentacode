<?php

namespace spec\App\Blog\Query;

use App\Blog\Query\LoadPostFromFile;
use PhpSpec\ObjectBehavior;

class LoadPostFromFileSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LoadPostFromFile::class);
    }
}
