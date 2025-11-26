<?php

namespace App\Blog\Factory;

use App\Blog\Post;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Webmozart\Assert\Assert;

use function Safe\preg_match;

class PostFromFileFactory
{
    private Filesystem $filesystem;

    public function __construct(
        FilesystemManager $filesystemManager,
        private PostFromMarkdown $postFromMarkdown,
    ) {
        $this->filesystem = $filesystemManager->disk('posts');
    }

    public function __invoke(string $filePath): Post
    {
        $date = $this->getDateFromFilepath($filePath);
        $slug = $this->getSlugFromFilepath($filePath);
        $languague = $this->getLanguageFromFilepath($filePath);

        $fileContent = $this->filesystem->get($filePath);
        Assert::stringNotEmpty($fileContent);

        return $this->postFromMarkdown->__invoke($date, $slug, $languague, $fileContent);
    }

    private function getDateFromFilepath(string $filePath): CarbonImmutable
    {
        if (!preg_match('/^(\d{4})(\d{2})(\d{2})/', $filePath, $matches)) {
            throw new \RuntimeException(sprintf(
                'Could not extract date from file path "%s".',
                $filePath
            ));
        }

        list(, $year, $month, $day) = $matches;

        $date = CarbonImmutable::createFromFormat('Ymd', $year . $month . $day);
        Assert::notFalse($date);

        return $date;
    }

    private function getSlugFromFilepath(string $filePath): string
    {
        if (!preg_match('/^\d{8}_([\w-]+)\.[a-z]{2}\.md$/', $filePath, $matches)) {
            throw new \RuntimeException(sprintf(
                'Could not extract slug from file path "%s".',
                $filePath
            ));
        }

        list(, $slug) = $matches;

        return $slug;
    }

    private function getLanguageFromFilepath(string $filePath): string
    {
        if (!preg_match('/^\d{8}_[\w-]+\.([a-z]{2})\.md$/', $filePath, $matches)) {
            throw new \RuntimeException(sprintf(
                'Could not extract language from file path "%s".',
                $filePath
            ));
        }

        list(, $language) = $matches;

        return $language;
    }
}
