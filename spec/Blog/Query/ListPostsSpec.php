<?php

namespace spec\App\Blog\Query;

use PhpSpec\ObjectBehavior;
use App\Blog\Factory\PostFromFileFactory;
use App\Blog\Post;
use App\Blog\PostContent;
use App\Blog\Query\ListPosts;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ListPostsSpec extends ObjectBehavior
{
    function let(
        FilesystemManager $filesystemManager,
        Filesystem $filesystem,
        PostFromFileFactory $postFromFileFactory
    ) {
        $filesystemManager->disk('posts')->willReturn($filesystem);

        $this->beConstructedWith(
            $filesystemManager,
            $postFromFileFactory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ListPosts::class);
    }
}
