# 10 tips with Behat3 and Mink

I've been in 💖 with Behat for years now, and when [@matthieunapoli](https://twitter.com/matthieunapoli) asked me about how to setup Behat3 in one of his project I realized that I'm using some tricks in my scenarios, so I might as well share them… I hope you'll find one or two useful!

* [0. No shame disclaimer](#0noshamedisclaimer)
* [1. Create a BaseContext](#1createabasecontext)
* [2. Add some Magic](#2addsomemagic)
* [3. The Inevitable Spin](#3theinevitablespin)
* [4. Use ExpectationException](#4useexpectationexception)
* [5. Polymorphic steps](#5polymorphicsteps)
* [6. Use Mink Assertion API](#6useminkassertionapi)
* [7. Smart CSS selectors](#7smartcssselectors)
* [8. Use the Symfony container](#8usethesymfonycontainer)
* [9. Go D.R.Y. with Traits](#9godrywithtraits)
* [10. Taking a screenshot when a scenario fails (Even in CI)](#10takingascreenshotwhenascenariofailseveninci)

## 0. No shame disclaimer

You will not find any beautiful code in here, no decoupled classes, no great design pattern and that's ok. Because test code is very different from production code, you're allowed to write quick and (not necessarly) dirty code.

The goal here is to go fast writing your tests, the quickest you go and the most time you can spend on actual production code. This is why you will find shortcuts, magic and a BaseContext class that have way too much responsabilities bellow. It does not mean that it's awful code either because context classes are more simple than production code, it's also not a nightmare to refactor. Of course this is my opinion and if you want to go all [S.O.L.I.D.](http://williamdurand.fr/2013/07/30/from-stupid-to-solid-code/) on your context classes please do.

## 1. Create a BaseContext

We will start by creating the proper `BaseContext` file that all of our `*Context` classes will extend (at least those working with Mink):

```php
<?php

// I like to have my contexts in a specific namespace, your choice really
namespace Context;

use Behat\Behat\Context\Context as ContextInterface;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

abstract class BaseContext extends RawMinkContext implements ContextInterface, SnippetAcceptingContext
{
    // Useful stuff we will add later
}
```

## 2. Add some Magic

Everytime you need to use a method of Mink API, you generally either need to call it on the Session with `$this->getSession()->foo()` or on the Page object like `$this->getSession()->getPage()->foo()`. As we  will mostly use these functions, we can add some magic to our `BaseContext`:

```php
public function __call($method, $parameters)
{
    // we try to call the method on the Page first
    $page = $this->getSession()->getPage();
    if (method_exists($page, $method)) {
        return call_user_func_array(array($page, $method), $parameters);
    }

    // we try to call the method on the Session
    $session = $this->getSession();
    if (method_exists($session, $method)) {
        return call_user_func_array(array($session, $method), $parameters);
    }

    // could not find the method at all
    throw new \RuntimeException(sprintf(
        'The "%s()" method does not exist.', $method
    ));
}
```

Voilà! We can now directly call things like `$field = $this->find('css', 'input#foo');` in our contexts, that will save us some time and improve readability.

## 3. The Inevitable Spin

Everyone who tried Behat with Mink and a JavaScript driver (I use Selenium2Driver with phantomjs) has had issues with trying to assert something in the current web page while some JavaScript code has not been finished yet (pending Ajax query for example).

The [proper and recommended way](http://docs.behat.org/en/v2.5/cookbook/using_spin_functions.html) of dealing with these issues is to use a `spin` method in your context, that will run the assertion or code multiple times before failing. Here is my implementation that you can add to your `BaseContext`:

```php
public function spins($closure, $tries = 10)
{
    for ($i = 0; $i <= $tries; $i++) {
        try {
            $closure();

            return;
        } catch (\Exception $e) {
            if ($i == $tries) {
                throw $e;
            }
        }

        sleep(1);
    }
}
```

The callback function will be called once, if it throws an Exception it will be called again after a one second sleep and so on until it has tried enought (here 10 tries by default). An example for using this would be checking that an element is present in the page after doing something in JavaScript that can take a long time:

```php
/**
 * @When something long is taking long but should output :text
 */
public function somethingLongShouldOutput($text)
{
    $this->find('css', 'button#longStuff')->click();

    $this->spins(function() use ($text) { 
        $this->assertSession()->pageTextContains($text);
    });
}
```

Another use would be to wait before and element is found before doing something on it:

```php
/**
 * @Then do something on a button that might not be there yet
 */
public function doSomethingNotThereYet()
{
    $this->spins(function() { 
        $button = $this->find('css', 'button#mightNotBeThereYet');
        if (!$button) {
            throw \Exception('Button is not there yet :(');
        }
        $button->click();
    });
}
```

## 4. Use ExpectationException

If you're trying to assert something that should occur on a web page, use Mink's `ExpectationException` rather than another exception. If thrown Behat will print the HTML code of the page if run it with the verbose option, it will also open the HTML in your browser if you properly setted your `behat.yml` configuration file:

```yaml
default:
    # ...
    extensions:
        # ...
        Behat\MinkExtension\ServiceContainer\MinkExtension:
            # ...
            show_auto: true
            # this is the syntax for MacOS and Chrome
            show_cmd: open -a "Google Chrome" %s
```

Again we can add a little shortcut in our `BaseContext` class because we will use them a lot:

```php
use Behat\Mink\Exception\ExpectationException;

class BaseContext //...
{
    protected function throwExpectationException($message)
    {
        throw new ExpectationException($message, $this->getSession());
    }
}
```

## 5. Polymorphic steps

One easily forgotten feature of Behat is that you can write several step definitions that will use a single step code, this is usefull to be grammatically correct when dealing with numbers for example:

```php
/**
 * @Then I should have no apple
 * @Then I should have :count apple
 * @Then I should have :count apples
 */
public function shouldHaveApples($appleCount = 0)
{
    // ...
}

```

But it can be even more powerful when you need to combine a lot of different parameters for the same step:

```php
/**
 * @When I get things that weight :weight kilogram
 * @When I get the :color things
 * @When I get things that are :length feet longs
 * @When I GET ALL THE THINGS
 */
public function getThings($weight = null, $color = null, $length = null)
{
    // ...
}
```

## 6. Use Mink Assertion API

You probably know that it's better to [hide the implementation when writing Behat test](http://elnur.pro/use-the-domain-language-in-bdd-features/), for example:

```gherkin
Then element ".msg-success" should contain "Congratulations !" 
```

Should be written:

```gherkin
Then I should be congratulated for my efforts
```

This mean we will try to never use built-in Mink steps but rather implement our own steps. It's a bit more code to write but in the end our scenarios will be much more readable. Also the Mink assertion API is great and complete: refer to the class [Behat\Mink\WebAssert](https://github.com/minkphp/Mink/blob/master/src/WebAssert.php) for a list of available assertions.

```php
/**
 * @Then I should be congratulated for my efforts
 */
public function iShouldBeCongratulated()
{
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->addressMatches("/congrats$");
    $this->assertSession()->elementContains('css', '.msg-success', "Congratulations !");
}
```

## 7. Smart CSS selectors

Mink uses the [symfony/css-selector](https://github.com/symfony/css-selector) component for querying the DOM. This enables you to use [CSS3 selectors](http://www.w3.org/TR/2011/REC-css3-selectors-20110929/) ( `:empty`, `:checked`, `:nth-child`…) as well as the `:contains` selector that was [not even implemented](http://www.w3.org/TR/2011/REC-css3-selectors-20110929/#content-selectors) in CSS (but those of you using jQuery might have heard of it).

Long story short, you can do cool stuff with it:

```php
/**
 * @Then I delete all swear words
 */
public function iDeleteAllSwearWords()
{
    $notPoliteRows = $this->find('css ', 'table.moderation tr:contains("Merde")');
    foreach ($notPoliteRows as $row) {
        $deleteButton = $row->find('button.delete');
        $deleteButton->click();
    }
}
```

## 8. Use the Symfony container

If you are testing a Symfony project, I suggest you install [behat/symfony2-extension](https://github.com/Behat/Symfony2Extension/blob/master/doc/index.rst) with composer.  Then you can simply access the container from you context class:

```php
use Behat\Symfony2Extension\Context\KernelDictionary;

class UserContext extends BaseContext
{
    use KernelDictionary;

    /**
     * @When I go to :nickname profile page
     */
    public function iGoToProfilePage($nickname)
    {
        $user = $this->getContainer()
            ->get('user_repository')
            ->findByNickname($nickname)
        ;
        
        $this->visit(sprintf('/user/%s/profile', $user->getId());
    }
}
```

## 9. Go D.R.Y. with Traits

Just like the `KernelDictionary`, traits are a really great way to avoid code duplication in your scenarios. For example I have a `LogDictionnary` that tells me if something has been logged, a `MailDictionnary` that tells me if something has been mailed and a… you get the point.

```php
<?php

namespace Context\Dictionary;

trait LogDictionary
{
    protected function assertLogExists($log)
    {
        // logs are emptied before each scenarios
        // so I know the content of test.log has been written
        // during the current scenario
        $baseDirectory = str_replace('/features/Context/Dictionary', '', __DIR__);
        $logFilename = sprintf('%s/app/logs/test.log', $baseDirectory);
        $logContent = file_get_contents($logFilename);

        if (strpos($logContent, $log) !== false) {
            return;
        }

        // not using ExpectationException, the assertion is not about the web page
        throw new \RuntimeException(sprintf('"%s" was never logged.', $log));
    }
}
```


## 10. Taking a screenshot when a scenario fails (Even in CI)

For my final trick I'm just going to share a whole context that I'm using, it's nothing big but it allows me to see exactly what went wrong during a scenario that failed by taking a screenshot. Cool thing is that it also works in your hosted continuous integration solution because instead of just saving the screenshots it sends it to [wsend.net](http://wsend.net) for you to look at later. Feel free to edit it to suit your needs.

```php
<?php

namespace Context;

use Behat\Testwork\Tester\Result\TestResult;
use Behat\Mink\Driver\Selenium2Driver;

class ScreenshotContext extends BaseContext
{
    protected $scenarioTitle = null;
    protected static $wsendUser = null;

    /**
     * @BeforeScenario
     */
    public function cacheScenarioName($event)
    {
        // it's only to have a clean screenshot name later
        $this->scenarioTitle = $event->getScenario()->getTitle();
    }

    /**
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if ($event->getTestResult()->getResultCode() !== TestResult::FAILED) {
            return;
        }

        $this->takeAScreenshot();
    }

    /**
     * @Then take a screenshot
     */
    public function takeAScreenshot()
    {
        if (!$this->isJavascript()) {
            print "Screenshot cannot be taken from non javascript scenario.\n";

            return;
        }

        $screenshot = $this->getSession()->getDriver()->getScreenshot();

        $filename = $this->getScreenshotFilename();
        file_put_contents($filename, $screenshot);

        $url = $this->getScreenshotUrl($filename);

        print sprintf("Screenshot is available :\n%s", $url);
    }

    protected function getScreenshotUrl($filename)
    {
        if (!self::$wsendUser) {
            self::$wsendUser = $this->getWsendUser();
        }

        exec(sprintf(
            'curl -F "uid=%s" -F "filehandle=@%s" %s 2>/dev/null',
            self::$wsendUser,
            $filename,
            'https://wsend.net/upload_cli'
        ), $output, $return);

        return $output[0];
    }

    protected function getWsendUser()
    {
        // create a wsend anonymous user
        $curl = curl_init('https://wsend.net/createunreg');
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'start=1');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $wsendUser = curl_exec($curl);
        curl_close($curl);

        return $wsendUser;
    }

    protected function getScreenshotFilename()
    {
        $filename = $this->scenarioTitle;
        $filename = preg_replace("#[^a-zA-Z0-9\._-]#", '_', $filename);

        return sprintf('%s/%s.png', sys_get_temp_dir(), $filename);
    }

    protected function isJavascript()
    {
        return $this->getSession()->getDriver() instanceof Selenium2Driver;
    }
}
```

This will output somthing like this:

```gherkin
Scenario: Doing something that should not fail
    Given I am on some page
    When I do something
    Then it should not fail
        Oops, and yet it failed.
          
Screenshot is available :
https://wsend.net/b4b2187882645a0ac200cb441e7cdfa1/Doing_something_that_should_not_fail.png
```

<center><p><img src="https://media.giphy.com/media/KIS4alAucQILe/giphy.gif" /></p></center>
<center><p>Oops, I almost forgot to add a cat gif, here it is.</p></center>