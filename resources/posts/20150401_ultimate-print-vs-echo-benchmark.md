# The Ultimate print versus echo benchmark

Single quotes VS double quotes, `sizeof()` VS `count()`, `for` VS `while`… A lot of questions have torn the PHP community appart for decades but one of the most debated question is:

> What is fastest? **print** or **echo**?!

While I do prefer `print` for aesthetic reason I'll try to answer this once and for all.

## The setup

It is always hard to find the correct setup for benchmarking low level langage features, there is always an impact of the script to the actual benchmark so I decided to benchmark directly via my terminal, using `php command line` and unix `time` function so that the php script is **not polluted by time computing functions** to output the time result. Also most of the script will output tons of data, **slowing down the rendering** in the terminal, so I decided to redirect all output to `/dev/null`. In the end the benchmark will look like this :

```bash
time php script.php > /dev/null
```

And output something like :

```bash
real   0m6.549s
user   0m2.012s
sys    0m1.820s
```

## Round 1 - Fight!

The script will be as simple as it can be, just iterating 10 million times the same function, here is `echo.php`:

```php
<?php
    for ($i = 0; $i <= 10000000; $i++) {
        echo 'Best benchmark ever';
    }
```

And the result:

```bash
time php echo.php > /dev/null

real	0m5.544s
user	0m2.796s
sys  	0m2.743s
```

Five seconds and a half, not that bad! let's see how `print` handles this :

```php
<?php
	for ($i = 0; $i <= 10000000; $i++) {
        print 'Best benchmark ever';
    }
```

And the result:

```bash
time php print.php > /dev/null

real	0m5.526s
user	0m2.813s
sys 	0m2.708s
```

All right a little better! `print` is 0,018 second faster than `echo`, not much of a difference but that should settle the debate once and for all, unless…

## Round 2 - Here comes a new challenger!

And this is when it occured to me… to be honest this thought only is what motivated me to write this post. I've always heard that `die()` is actually the quickest way to output something, for example `<?php die("Hello World!");` would be the fastest ever Hello World you could ever write, but I've never really used `die()` for that purpose, so lets see what it got!!

```php
<?php
    for ($i = 0; $i <= 10000000; $i++) {
        die('Best benchmark ever');
    }
```

And the result:

```bash
time php die.php > /dev/null

real	0m0.025s
user	0m0.019s
sys 	0m0.005s
```

**ZOMG 0.025 motherfucking seconds!** That's blazing fast!! So I think the morale is pretty clear: next time you want to optimize your scripts, please think about dying first. I think you'll save everyone a lot of time!

![sonic is so slow](http://resource.mmgn.com/Gallery/full/Too-slow-sonic-1041428.jpg)
(comic brought to you by [completelyseriouscomics.com](http://completelyseriouscomics.com/))

