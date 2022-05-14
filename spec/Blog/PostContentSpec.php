<?php

namespace spec\App\Blog;

use App\Blog\PostContent;
use PhpSpec\ObjectBehavior;

class PostContentSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            'Un titre',
            'Du texte',
            '<h1>Du html</h1>',
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PostContent::class);
    }

    function it_has_a_title()
    {
        $this->title->shouldBe('Un titre');
    }

    function it_has_some_text()
    {
        $this->text->shouldBe('Du texte');
    }

    function it_has_some_html()
    {
        $this->html->shouldBe('<h1>Du html</h1>');
    }

    function it_gets_an_excerpt()
    {
        $this->beConstructedWith(
            'Un titre',
            'Ce texte est très long et il faut le couper.',
            '<h1>Du html</h1>',
        );

        $this->getExcerpt(666)->shouldReturn('Ce texte est très long et il faut le couper.');
        $this->getExcerpt(13)->shouldReturn('Ce texte est…');
        $this->getExcerpt(10)->shouldReturn('Ce texte…');
    }

    function its_description_is_in_html_description_paragraph()
    {
        $html = <<<HTML
            <html>
                <h1>Du html</h1>
                <p id="post-description">
                    Une description dans du html.
                </p>
            </html>
            HTML;

        $this->beConstructedWith(
            'Un titre',
            'Ce texte est très long et il faut le couper.',
            $html,
        );

        $this->getDescription()->shouldReturn('Une description dans du html.');

    }

    function its_description_is_the_excerpt()
    {
        $this->beConstructedWith(
            'Un titre',
            'Une description dans le texte.',
            '<h1>Du html</h1>',
        );

        $this->getDescription()->shouldReturn('Une description dans le texte.');
    }

    function it_is_immutable()
    {
        $this->shouldThrowReadOnlyError(fn() => $this->title = 'non');
        $this->shouldThrowReadOnlyError(fn() => $this->text = 'non');
        $this->shouldThrowReadOnlyError(fn() => $this->html = 'non');
    }

    public function getMatchers(): array
    {
        return [
            'throwReadOnlyError' => function ($subject, $closure) {
                try {
                    $closure();
                } catch (\Throwable $e) {
                    if (strpos($e->getMessage(), 'Cannot modify readonly property') !== false) {
                        return true;
                    }

                    throw new \RuntimeException(sprintf(
                        'Expected "read-only" Error but got "%s" with message "%s".',
                        get_class($e),
                        $e->getMessage()
                    ));
                }

                return false;
            },
        ];
    }
}
