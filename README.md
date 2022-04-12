```php
<?php

class Tentacode
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