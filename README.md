![test workflow](https://github.com/tentacode/tentacode/workflows/Tests/badge.svg) [![Twitter @tentacode](https://img.shields.io/twitter/url/https/twitter.com/tentacode.svg?style=social&label=Follow%20%40tentacode)](https://twitter.com/tentacode)

[![trophy](https://stable-github-profile-trophy.vercel.app//?username=tentacode&theme=dracula&column=5&margin-w=15&margin-h=15)](https://github.com/ryo-ma/github-profile-trophy)

```php
<?php

final class Tentacode
{
    public function __construct(
        private string $fullName = "Gabriel Pillet",
        private string $linkedinHandle = "gabrielpillet",
        private string $portfolioUrl = "https://tentacode.dev",
        private array $favoriteEmojis = ['🐙', '✨', '🤖', '🤗'],
    ) {}

    public function  sayHello(): void
    {
        $greetingMessage = <<<BONJOUR
            Bonjour! {$this->favoriteEmojis[array_rand($this->favoriteEmojis)]}
            Je m'appelle {$this->fullName}.

            Consultez mon portfolio à {$this->portfolioUrl}
            Ou mon profil linkedin à https://linkedin.com/in/{$this->linkedinHandle}
            BONJOUR;

        print $greetingMessage . PHP_EOL;
    }
}

try { 
    (new Tentacode)->sayHello();
} catch (\Throwable $e) {
    echo "Oups ! Personne n'est parfait je suppose…". PHP_EOL . $e->getMessage();
    die(42);
}
```
