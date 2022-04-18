# Dependency injection with Behat (and PHP-DI)

Konstantin Kudryashov (alias [@everzet](https://twitter.com/everzet)) gave us a nice present when releasing [Behat 3.3.0](https://github.com/Behat/Behat/blob/master/CHANGELOG.md) on Christmas! 🎅

[![](/img/posts/santa_kitten.gif)](http://gifsboom.net/post/135889409639/santa-claus-cat-%E3%81%8B%E3%81%94%E7%8C%AB-%EF%BD%82%EF%BD%8C%EF%BD%8F%EF%BD%87)

The main feature of this new version is [Helper containers](https://github.com/Behat/Behat/pull/974) which is something I did not really expected but don't know how I lived without until now. It allows to define and inject reusable services into Behat contexts, and more!

I think it's a good occasion to review a bit how Behat works but if you are already a Behat master you can…

*tl;dr: go directly to [dependency injection](#dependencyinjectiontotherescue) or the [PHP-DI example](#useyourphpdicontainer).*

## Santa Claus Delivery

Imagine we have to test that Santa is checking if children really deserve their presents, our `christmas_delivery.feature` could look like:

```gherkin
Feature: Santa's delivery

    Scenario: Behaving children should have some presents
        Given the child is not on the naughty child list
        And the child wanted a "Playstation 4"
        When Santa Claus makes his delivery
        Then child should find a "Playstation 4" under the christmas tree

    Scenario: Misbehaving children will get what they deserve
        Given the child is on the naughty child list
        And the child wanted a "Playstation 4"
        When Santa Claus makes his delivery
        Then child should find a "bag of charcoal" under the christmas tree
```

The christmas delivery "database" is stored in a single `christmas_delivery.json` file that looks like this:

```javascript
{
    "naughtyChildren": [],
    "wishlist": [],
    "naughtyPresent": "a bag of charcoal",
    "deliveredPresents": []
}
```

For the sake of this example, let's pretend we organize our code into three different contexts, first we have the `NaughtyListContext.php`:

```php
<?php

use Behat\Behat\Context\Context;

class NaughtyListContext implements Context
{
    /**
     * @Given the child is not on the naughty child list
     */
    public function theChildIsNotOnTheNaughtyChildList()
    {
        $deliveryList = json_decode(file_get_contents('christmas_delivery.json'), true);
        $index = array_search('gabriel', $deliveryList['naughtyChildren']);
        if ($index !== false) {
            unset($deliveryList[$index]);
        }

        file_put_contents('christmas_delivery.json', json_encode($deliveryList, JSON_PRETTY_PRINT));
    }

    /**
     * @Given the child is on the naughty child list
     */
    public function theChildIsOnTheNaughtyChildList()
    {
        $deliveryList = json_decode(file_get_contents('christmas_delivery.json'), true);
        $deliveryList['naughtyChildren'][] = 'gabriel';

        file_put_contents('christmas_delivery.json', json_encode($deliveryList, JSON_PRETTY_PRINT));
    }
}

```

Then the `PresentContext.php`:

```php
<?php

use Behat\Behat\Context\Context;

class PresentContext implements Context
{
    /**
     * @Given the child wanted a :present
     */
    public function theChildWantedA($present)
    {
        $deliveryList = json_decode(file_get_contents('christmas_delivery.json'), true);
        $deliveryList['wishlist']['gabriel'] = $present;

        file_put_contents('christmas_delivery.json', json_encode($deliveryList, JSON_PRETTY_PRINT));
    }

    /**
     * @Then child should find a :present under the christmas tree
     */
    public function childShouldFindAUnderTheChristmasTree($present)
    {
        $deliveryList = json_decode(file_get_contents('christmas_delivery.json'), true);
        $deliveredPresent = $deliveryList['deliveredPresents']['gabriel'];
        if ($deliveredPresent !== $present) {
            throw new \Exception(sprintf(
                'Delivered present was "%s" but "%s" was expected.',
                $deliveredPresent,
                $present
            ));
        }
    }
}
```

And finally the `DeliveryContext.php`:

```php
<?php

use Behat\Behat\Context\Context;

class DeliveryContext implements Context
{
    /**
     * @BeforeSuite
     */
    public static function initList()
    {
        file_put_contents('christmas_delivery.json', json_encode([
            'naughtyChildren' => [],
            'wishlist' => [],
            'naughtyPresent' => 'bag of charcoal',
            'deliveredPresents' => [],
        ], JSON_PRETTY_PRINT));
    }

    /**
     * @When Santa Claus makes his delivery
     */
    public function santaClausMakesHisDelivery()
    {
        $deliveryList = json_decode(file_get_contents('christmas_delivery.json'), true);

        foreach ($deliveryList['wishlist'] as $child => $present) {
            $hasBeenNaughty = in_array($child, $deliveryList['naughtyChildren']);
            if ($hasBeenNaughty) {
                $deliveryList['deliveredPresents'][$child] = $deliveryList['naughtyPresent'];
            } else {
                $deliveryList['deliveredPresents'][$child] = $present;
            }
        }

        file_put_contents('christmas_delivery.json', json_encode($deliveryList, JSON_PRETTY_PRINT));
    }
}
```

So how do you like that?! What? ... You don't? 😭

Ok yes, you probably picture me with blood in my eyes while I'm writing this blog post and you're right because I've just sticked a pair of scissors in them to end the agony.

## Adding classes

On a more serious matter, we know how to solve this problem by refactoring the code into classes. First let's create a `ChristmasStorage.php` class to handle the filesystem:

```php
<?php

class ChristmasStorage
{
    private $filepath = 'christmas_delivery.json';

    public function getData()
    {
        $json = file_get_contents($this->filepath);

        return json_decode($json, true);
    }

    public function saveData(array $data)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents($this->filepath, $json);
    }
}
```

A `NaughtyListRepository.php` to handle naughty children:

```php
<?php

class NaughtyListRepository
{
    protected $storage;

    public function __construct(ChristmasStorage $storage)
    {
        $this->storage = $storage;
    }

    public function add($child)
    {
        $list = $this->storage->getData();
        $list['naughtyChildren'][] = $child;

        $this->storage->saveData($list);
    }

    public function remove($child)
    {
        $list = $this->storage->getData();
        $index = array_search($child, $list['naughtyChildren']);

        if ($index !== false) {
            unset($list['naughtyChildren'][$index]);
            $this->storage->saveData($list);
        }
    }

    public function isNaughty($child)
    {
        $list = $this->storage->getData();

        return in_array($child, $list['naughtyChildren']);
    }
}
```

And a `PresentRepository.php` to handle the wishlist and the present delivery:

```php
<?php

class PresentRepository
{
    protected $storage;
    protected $naughtlyListRepository;

    public function __construct(
        ChristmasStorage $storage,
        NaughtyListRepository $naughtyListRepository
    )
    {
        $this->storage = $storage;
        $this->naughtyListRepository = $naughtyListRepository;
    }

    public function wish($child, $present)
    {
        $list = $this->storage->getData();
        $list['wishlist'][$child] = $present;

        $this->storage->saveData($list);
    }

    public function deliverPresent($child)
    {
        $list = $this->storage->getData();

        if ($this->naughtyListRepository->isNaughty($child)) {
            $list['deliveredPresents'][$child] = $list['naughtyPresent'];
        } else {
            $list['deliveredPresents'][$child] = $list['wishlist'][$child];
        }

        $this->storage->saveData($list);
    }

    public function getDeliveredPresent($child)
    {
        $list = $this->storage->getData();

        return $list['deliveredPresents'][$child];
    }
}
```

And the updated contexts classes, starting with the `NaughtyListContext.php`:

```php
<?php

use Behat\Behat\Context\Context;

class NaughtyListContext implements Context
{
    protected $naughtyListRepository;

    public function __construct()
    {
        $this->naughtyListRepository = new NaughtyListRepository(new ChristmasStorage);
    }

    /**
     * @Given the child is not on the naughty child list
     */
    public function theChildIsNotOnTheNaughtyChildList()
    {
        $this->naughtyListRepository->remove('gabriel');
    }

    /**
     * @Given the child is on the naughty child list
     */
    public function theChildIsOnTheNaughtyChildList()
    {
        $this->naughtyListRepository->add('gabriel');
    }
}
```

The `PresentContext.php`:

```php
<?php

use Behat\Behat\Context\Context;

class PresentContext implements Context
{
    protected $presentRepository;

    public function __construct()
    {
        $storage = new ChristmasStorage;

        $this->presentRepository = new PresentRepository(
            $storage,
            new NaughtyListRepository($storage)
        );
    }

    /**
     * @Given the child wanted a :present
     */
    public function theChildWantedA($present)
    {
        $this->presentRepository->wish('gabriel', $present);
    }

    /**
     * @Then child should find a :present under the christmas tree
     */
    public function childShouldFindAUnderTheChristmasTree($present)
    {
        $deliveredPresent = $this->presentRepository->getDeliveredPresent('gabriel');

        if ($deliveredPresent !== $present) {
            throw new \Exception(sprintf(
                'Delivered present was "%s" but "%s" was expected.',
                $deliveredPresent,
                $present
            ));
        }
    }
}
```

And finally the `DeliveryContext.php`:

```php
<?php

use Behat\Behat\Context\Context;

class DeliveryContext implements Context
{
    protected $presentRepository;

    public function __construct()
    {
        $storage = new ChristmasStorage;

        $this->presentRepository = new PresentRepository(
            $storage,
            new NaughtyListRepository($storage)
        );
    }

    /**
     * @BeforeSuite
     */
    public static function initList()
    {
        $storage = new ChristmasStorage;
        $storage->saveData([
            'naughtyChildren' => [],
            'wishlist' => [],
            'naughtyPresent' => 'bag of charcoal',
            'deliveredPresents' => [],
        ]);
    }

    /**
     * @When Santa Claus makes his delivery
     */
    public function santaClausMakesHisDelivery()
    {
        $this->presentRepository->deliverPresent('gabriel');
    }
}
```

## Better, but not perfect yet

Well, it's no work of art but it's getting somewhere. We have some problems though. First of all we already have some nasty dependencies issues: the `PresentRepository` class depends on `ChristmasStorage` as well as `NaughtyListRepository` that ALSO depends on `ChristmasStorage`. In a regular project you know it can be far worse than that and open the gates of the Dependency Hell.

Another issue is that Context classes are instanciated once per scenario, that means that if a class is instanciated in 10 contexts and your suite has 158 scenarios, it will be instanciated 1580 times when the test suite is executed. Just in this little example the `ChrismasStorage` class will be instanciated 7 times (3 times in each scenario, and one more in the `@BeforeSuite` hook). Here it's just a little class reading a file, but in a real world project you want to avoid unnecessary instanciation costs.

We could also have used a Trait, but they are a bit unpractical, even if they are still a good way to share some code between contexts, it's best for "toolbox code", see more about it in [another Behat related post](http://www.tentacode.net/10-tips-with-behat-and-mink#9godrywithtraits).

Sorry for the (very) long introduction to the problem we were facing before, now let's see how it can be fixed in Behat 3.3.0!

## Dependency injection to the rescue

I think Konstantin found a very smart and simple way to describe dependency injection directly in the `behat.yml` configuration file:

```yaml
default:
  suites:
    default:
      contexts:
        - FirstContext:
          - "@shared_service"
        - SecondContext:
          - "@shared_service"

      services:
        shared_service: "SharedService"
```

This simple example will create an instance of the `SharedService` and pass the instance as a constructor argument for each context.

Note that this could not work on our previous example because two of our "services" need constructor arguments and can't be directly instanciated. We could solve this issue by declaring our services as "factories" that will instanciate our services:

```yaml
default:
    suites:
        default:
            path: %paths.base%/features
            contexts:
                - NaughtyListContext:
                    - "@naughtyListRepository"
                - DeliveryContext:
                    - "@presentRepository"
                - PresentContext:
                    - "@presentRepository"
            services:
                storage:
                    class: "ChristmasStorage"
                naughtyListRepository:
                    class: "NaughtyListRepository"
                    factory_method: "create"
                presentRepository:
                    class: "PresentRepository"
                    factory_method: "create"
```

This is still not really convenient because:

* We need to add a static `create` method to each service.
* We cannot pass services as arguments to the factory methods `create`, so we still need to instanciate codependencies inside the `create` method.
* We also have to define a new service key in `behat.yml` each time we want to add a service.

## Yay, containers!

You might have heard that containers are evil but in this case they are really helpful! Another smart move from Konstantin was to use the `ContainerInterface` from the [Container Interop](https://github.com/container-interop/container-interop) project.

We will directly use a factory method so that we are sure to always use the same instance of the container and that our services are instanciated only once when the tests are run. Here is how the `behat.yml` file looks like:

```yaml
default:
    suites:
        default:
            path: %paths.base%/features
            contexts:
                - NaughtyListContext:
                    - "@naughtyListRepository"
                - DeliveryContext:
                    - "@presentRepository"
                - PresentContext:
                    - "@presentRepository"
            services: ChristmasContainer::create
```

And our `ChristmasContainer.php` file:

```php
<?php

use Interop\Container\ContainerInterface;

class ChristmasContainer implements ContainerInterface
{
    protected static $instance;
    protected $services;

    public function __construct()
    {
        $this->services['storage'] = new ChristmasStorage;

        $this->services['naughtyListRepository'] = new NaughtyListRepository(
            $this->services['storage']
        );

        $this->services['presentRepository'] = new PresentRepository(
            $this->services['storage'],
            $this->services['naughtyListRepository']
        );
    }

    public static function create()
    {
        if (!self::$instance) {
            self::$instance = new ChristmasContainer;
        }

        return self::$instance;
    }

    public function has($id)
    {
        return in_array($id, array_keys($this->services));
    }

    public function get($id)
    {
        return $this->services[$id];
    }
}
```

Voilà! This is a practical way of defining and injecting services, and we also are sure that services won't be instanciated more than once during the test suite execution.

Edit: As [Konstantin rightfuly points out](https://twitter.com/BehatPHP/status/814481086411657217), using a Singleton container will break test isolation and can be a bad practice, it's your choice to see if you prefer to garanty that the state is reset between every scenario or if you value more the performance gained by setting up your container and services only once. My opinion is that when writing tests you can sometime allow yourself some practices that you won't allow in your production code, personally I don't really bother that the in memory state is perfectly reset (because what I test is generally in another process anyway, via a HTTP server or a command line utility), but I do care that my data state is properly reset between scenario (for example, the database should be reset). Anyway, just my opinion here. 😉

## Use your PHP-DI container

Of course because Behat 3.3.0 uses Container Interop, you can use the same container as you use in your application if it follows this convention. This is REALLY helpful when you want to reuse some of your project code (yes, you can, even in your tests) like repositories. Here is how to create your [PHP-DI](http://php-di.org/) container in Behat:

```php
<?php

class ContainerFactory
{
    protected static $container;

    public static function create()
    {
        if (!self::$container) {
            $builder = new \DI\ContainerBuilder();
            $builder->addDefinitions(require('/path/to/definitions.php'));

            self::$container = $builder->build();
        }

        return self::$container;
    }
}
```

Unfortunately you can forget PHP-DI's auto wiring system in Behat for the moment but, who knows, someone might just make it happen one day!

Thanks for reading this way too long blog post! Please leave a comment if you have any question or suggestion.
