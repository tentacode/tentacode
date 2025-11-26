# Blackfire.io review

I was at the [Forum PHP 2015](http://event.afup.org/) this year and, just like last year, [blackfire.io](https://blackfire.io) was sponsoring the event. I decided to give them another chance because the first version I tried last year was a bit raw and I have to admit they did some huge improvements in a year! I first tried it directly at the Forum PHP and did some more test later this week. (And yes, I also write this blog post to try and win a new macbook with their [#FireUpMyMac competition](http://blog.blackfire.io/fireup-my-mac-2015.html))

tl;dr go directly to [some examples of code optimization](#letsoptimizesomecode)

## My experience with profiling

Back in [PMSIpilot](https://www.pmsipilot.com/) several years ago I used a lot [xhprof](http://php.net/manual/fr/book.xhprof.php) with a GUI that I don't remember the name. It was **REALLY** powerful but like a lot of product at that time it was not really user-friendly. At that time we had a really huge import script that had to compile complicated medical/financial data into a MySQL database that we used with a Symfony backend as a reporting tool. Xhprof helped us mostly with memory leaks and speeding up the import by visually showing us what kind of optimization we could do (for example huge functions called in a loop), I don't remember how much time and memory we saved but it sure was a lot.

## Blackfire as a modern profiling alternative

Blackfire is essentially used for the same need as xhprof, but it's plug and play and the UI is really neat. You can't host your own blackfire instance, you have to use it as a SAAS product but that's not a bad thing and I'm all for SAAS solutions as long as they help the developer. We use quite a lot of SAAS products at Ouiche Lorraine: they have the advantage of being easy to setup and with blackfire you can literally begin profiling your website in under 5 minutes. I really liked the [installation documentation](https://blackfire.io/docs/up-and-running/installation) in which they even have this smart copy button to copy/paste your tokens without having to look for them in your profile page, because that's how lazy I am:

![Blackfire installation documentation](/img/posts/blackfire_installation.png)
 
 

## Let's optimize some code!

### 1. The impact of Doctrine caching in a Symfony project

First thing I tried in order to quickly see how blackfire works is to prove the impact of Doctrine caching on our project [Diwi.com](https://diwi.com). It's as simple as adding these three lines in your `config.yml`:

```yaml
# you should also install the apcu extension
doctrine:
    orm:
        metadata_cache_driver: apc
        query_cache_driver: apc
        result_cache_driver: apc
```

I'm running blackfire in my local dev environment, but with a production like setting to monitor the actual project performance. The front page just lists the last *Rendez-vous* of the website and it looks like this (Yes, those are fixtures ¯\\_(ツ)_/¯ ):
![Diwi mainpage](/img/posts/blackfire_diwi_mainpage.png)
All you have to do is call `blackfire curl http://diwi.dev` with and without apc cache enabled and compare the two builds, here are the results:

* [Without doctrine apc cache](https://blackfire.io/profiles/da46b733-8fb4-4ad0-bcfd-5a1d4a2f944a/graph)
* [With doctrine apc cache](https://blackfire.io/profiles/9665a509-d660-4ab9-8c31-bd4300ac3ef5/graph)
* [Build comparison](https://blackfire.io/profiles/compare/acea3f3c-5c3a-4566-8cf5-0737f74b9c98/graph)
![Doctrine apc build compare](/img/posts/blackfire_doctrine_apc.png)
And it's a **wooping 38% performance boost**! I guess the morale of the story is pretty clear. It's a simple example to prove that sometime the smallest change in your code can lead to huge improvements.

### 2. Profiling a Behat suite in order to speed it up

Adding three lines to a config file is easy, let's try with an actual problem where we need to analyse the profiling result, learn from it and optimize the code.

**The context:** still in [Diwi](http://diwi.com), I'm running a ~200 scenarios Behat test suite that runs in ~14 minutes including the setup of the test instance (composer install, npm, grunt) and parallelization in three separate Behat suites with [Codeship](http://codeship.com). Although it's not especially huge, 15 minutes adds some latency to our workflow and if we can reduce it the better. I also noticed that my tests on the Diwi API are anormally long, it's just some curl calls and should be really quick to execute. Here is the result of the `bin/behat features/api` suite:

```yaml
50 scenarios (50 passed)
164 steps (164 passed)
1m59.55s (49.73Mb)
```

**Finding the cause:** this time we ask blackfire to run a cli command, as Behat is run in php it can be profiled just like a web application. I'll run `blackfire run bin/behat features/api`, and here is the [resulting blackfire build](https://blackfire.io/profiles/3ea799e0-807d-4edc-b320-da0de468f769/graph).

The bottleneck is really easy to find here, we can see that the `DiwiContext::reloadData` method is taking nearly 76% of the execution time, called 50 times (once for each scenario) and is taking 1min31sec of the total 2 minutes of the suite. We also notice that `RestContext::iMakeARequestTo` only takes 20 seconds although I know for sure it's where the actual testing is taking place.

![Behat reload bottleneck](/img/posts/blackfire_behat_cause.png)

I'm pretty sure you want to know what the `reloadData` method looks like, so here it is:

```php
/**
 * @BeforeScenario
 */
public function reloadData()
{
    exec('bin/reload test');
}
```

This calls a script that itself calls a Symfony command like this: `app/console diwi:reload --env=test`. We cannot profile it deeper because it's called via an `exec()` statement. What this command do is easy: it reloads all the data fixtures.

Why reloading the data before each scenario? It's to preserve an isomorphic data state: that way scenarios cannot influence each other and it's a rule I'm not willing to break today. The fact is I already kind of optimized the process: the `diwi:reload` uses a cached sql dump if it exists, so why is it taking so long? Let's find out and run `bin/reload test` against blackfire with `blackfire run bin/reload test`. Let's take a look at the [resulting blackfire build](https://blackfire.io/profiles/3abc2c16-bc9c-4763-8940-3cd329c8a173/graph).

This time instead of looking at what piece of code takes the longest time, let's find what's really important for us: the loading of the cache dump:

![Behat cache dump](/img/posts/blackfire_behat_cachedump.png)

It's weird that actually reloading the cache dump only takes 659ms out of the 2.2 seconds the whole command takes, what else does it do? Those of you that are versed in the dark arts of Symfony should notice that little `Kernel::boot()` that takes the other 1.4 seconds of the script, it should not take that long unless… let's look at the `bin/reload` script to be sure:

```bash
#!/bin/bash
if [ "$1" == "" ]; then
    set $1 'dev'
fi

rm -Rf app/cache/$1 &&
app/console diwi:reload --env=$1
```

![Sherlocking intensifies](https://media.giphy.com/media/mEUA8Ly7wEC2c/giphy.gif)

**YEP THAT'S RIGHT.** Clearing the cache before reloading "just in case" is a bad idea, because the cache will warmup at the next command call, that means heavy computations called each time before a scenario. No, I'm not proud of this.

But in the meantime I'm glad that blackfire is here to show me I screwed up, this is exactly what I expected of this tool.

**Resolution:** Although just removing the cache clearing would be enough to save me a lot of time, it's also the occasion to think a little more about the issue: if the only thing I need between my Behat scenarios is to reload the cached sql dump, why should I call a Symfony command instead of just calling `exec("mysql < mydump.sql");`? This will be even more effective so let's change the `DiwiContext:reloadData` method:

```php
/**
 * @BeforeScenario
 */
public function reloadData()
{
    // please don't judge me on this code
    exec(sprintf(
        'mysql --user=%s --password=%s %s < %s/cache/cached_dump_fixtures.sql',
        $this->getContainer()->getParameter('database_user'),
        $this->getContainer()->getParameter('database_password'),
        $this->getContainer()->getParameter('database_name'),
        $this->getContainer()->getParameter('kernel.root_dir')
    ));
}
```

Again, let's profile and compare the results:

* [reloadData calls a Symfony command](https://blackfire.io/profiles/3ea799e0-807d-4edc-b320-da0de468f769/graph)
* [reloadData only loads the sql dump](https://blackfire.io/profiles/f0272100-0415-421a-aeaa-0781a37cf435/graph)
* [Build comparison](https://blackfire.io/profiles/compare/bffaab19-9904-44ff-b3ab-1be92b33bda8/graph)

And it's a **super-duper 59%** performance boost!! Of course we tested short API calls so the result is extremely significant, but I was curious about how the patch would improve the whole Diwi Behat suite that contains more complex scenarios, so I runned it on Codeship. Here is a picture worth a thousand words:

![Whole diwi test suite](/img/posts/blackfire_behat_whole_suite.png)

In the end it saved me 5 minutes per builds on a suite that already was parallelized so it will be a **HUGE** developer life improvement in the future.

**More on this topic:** if you are interested about optimizing your Behat suite, you can also have a look at this really nice post about how [Lakion sped up the Sylius Behat suite](http://lakion.com/blog/how-did-we-speed-up-sylius-behat-suite-with-blackfire) (also about blackfire).

## Conclusion: the harsh truth

I've got to admit, at the begining I mostly wanted to try and win a new macbook… Of course I was curious about blackfire because xhprof used to save my life before, but I did not expect it to show me that I made such a huge mistake in my code.

![Me trying blackfire](https://media.giphy.com/media/LXsakPwelEWhG/giphy.gif)
<center><i>Me trying blackfire, allegory</i></center>
 

> Even if you screw up sometime (and you will), you won't know unless someone actually put your nose into it. Better sooner than later, so try to monitor your code every now and then. — Abraham Lincoln

If you liked this post and are curious about blackfire, go try [the free plan](https://blackfire.io/pricing) and decide for yourself if you want to invest in the tool. As for myself this try convinced me to continue profiling my code, and I wait impatiently for some new features of blackfire that were sneak-peeked during the Forum PHP and will be released soon (did anyone said blackfire php-sdk?).
