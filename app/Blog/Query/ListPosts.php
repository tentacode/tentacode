<?php

namespace App\Blog\Query;

use App\Blog\Factory\PostFromFileFactory;
use App\Blog\Post;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

final class ListPosts
{
    private Filesystem $filesystem;

    public function __construct(
        FilesystemManager $filesystemManager,
        private PostFromFileFactory $postFromFileFactory
    ) {
        $this->filesystem = $filesystemManager->disk('posts');
    }

    /** @return array<Post> */
    public function __invoke(?int $postLimit = null): array
    {
        $postFilePaths = $this->getLimitFiles($postLimit);

        $posts = array_map(function (string $filePath) {
            return $this->postFromFileFactory->__invoke($filePath);
        }, $postFilePaths);

        return $posts;
    }

    private function getLimitFiles(?int $postLimit): array
    {
        $postFilePaths = $this->filesystem->files();

        $postFilePaths = $this->sortPostFilesByDateDesc($postFilePaths);

        if ($postLimit === null) {
            return $postFilePaths;
        }

        return array_slice($postFilePaths, 0, $postLimit);
    }

    private function sortPostFilesByDateDesc(array $postFilePaths): array
    {
        usort($postFilePaths, function (string $b, string $a) {
            return $a <=> $b;
        });

        return $postFilePaths;
    }
}
