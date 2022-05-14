<?php

namespace spec\App\Blog\Query;

use App\Blog\Query\GetPostBySlug;
use PhpSpec\ObjectBehavior;

use App\Blog\Factory\PostFromFileFactory;
use App\Blog\Post;
use App\Blog\PostContent;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetPostBySlugSpec extends ObjectBehavior
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
        $this->shouldHaveType(GetPostBySlug::class);
    }

    function it_get_post_if_file_match_slug($filesystem, $postFromFileFactory)
    {
        $filesystem->files()->shouldBeCalled()->willReturn([
            '20220101_post-bidule.md',
            '20220102_post-machin.md',
            '20220103_post-truc.md',
        ]);

        $post = new Post(
            'post-bidule',
            CarbonImmutable::now(),
            new PostContent(
                'Post Bidule',
                'Post Bidule text',
                '<h1>Post Bidule text</h1>',
                'post_bidule.png'
            )
        );

        $postFromFileFactory
            ->__invoke('20220102_post-machin.md')
            ->shouldBeCalled()
            ->willReturn($post);

        $this->__invoke('post-machin')->shouldReturn($post);
    }

    function it_throws_404_if_file_does_not_match_slug($filesystem)
    {
        $filesystem->files()->shouldBeCalled()->willReturn([
            '20220101_post-bidule.md',
            '20220102_post-non.md',
            '20220103_post-truc.md',
        ]);

        $this->shouldThrow(new NotFoundHttpException('Post was not found.'))
            ->during__invoke('post-machin');
    }

    function it_throws_404_if_no_post_files($filesystem)
    {
        $filesystem->files()->shouldBeCalled()->willReturn([]);

        $this->shouldThrow(new NotFoundHttpException('Post was not found.'))
            ->during__invoke('post-machin');
    }

}
