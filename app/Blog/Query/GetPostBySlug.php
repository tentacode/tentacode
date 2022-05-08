<?php

namespace App\Blog\Query;

use App\Blog\Factory\PostFromFileFactory;
use App\Blog\Post;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

final class GetPostBySlug
{
    private Filesystem $filesystem;

    public function __construct(
        FilesystemManager $filesystemManager,
        private PostFromFileFactory $postFromFileFactory
    ) {
        $this->filesystem = $filesystemManager->disk('posts');
    }

    public function __invoke(string $blogSlug): Post
    {
        $postFilePath = $this->getPostFilepath($blogSlug);

        return $this->postFromFileFactory->__invoke($postFilePath);
    }

    private function getPostFilepath(string $slug): string
    {
        $filepaths = $this->filesystem->files();
        foreach ($filepaths as $filepath) {
            if (strpos($filepath, $slug) !== false) {
                return $filepath;
            }
        }

        abort(404, "Post was not found.");
    }
}
