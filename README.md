![test workflow](https://github.com/tentacode/tentacode/workflows/Tests/badge.svg) [![Twitter @tentacode](https://img.shields.io/twitter/url/https/twitter.com/tentacode.svg?style=social&label=Follow%20%40tentacode)](https://twitter.com/tentacode)

[![tentacode's github stats](https://github-readme-stats.vercel.app/api?username=tentacode&theme=jolly&border_radius=10&hide_border=true)](https://github.com/tentacode/github-readme-stats)

[![trophy](https://stable-github-profile-trophy.vercel.app//?username=tentacode&theme=dracula&column=5&margin-w=15&margin-h=15)](https://github.com/ryo-ma/github-profile-trophy)

```php
<?php

final class Tentacode
{
    public function __construct(
        private string $fullName = "Gabriel Pillet",
        private string $twitterHandle = "@tentacode",
        private string $portfolioUrl = "https://tentacode.dev",
        private array $favoriteEmojis = ['🐙', '✨', '🤖', '🤗'],
    ) {}

    public function  sayHello(): void
    {
        $greetingMessage = <<<BONJOUR
            Bonjour! {$this->favoriteEmojis[array_rand($this->favoriteEmojis)]}
            My name is {$this->fullName}.

            Check my portfolio at {$this->portfolioUrl}
            Or my twitter profile at https://twitter.com/{$this->twitterHandle}
            BONJOUR;

        print $greetingMessage . PHP_EOL;
    }
}

try { 
    (new Tentacode)->sayHello();
} catch (\Throwable $e) {
    echo "Oops, nobody is perfect I guess…". PHP_EOL . $e->getMessage();
    die(42);
}
```
