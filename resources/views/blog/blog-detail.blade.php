<x-layout navbar-class="navbar-blog">
    <section class="page-section blog-detail text-dark background-light mt-2 mb-0  pb-5" id="blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto mb-0 text-center">
                <h1 class="text-center text-uppercase mt-5 mb-3">
                    Dependency injection with Behat (and PHP-DI)
                </h1>
                <time datetime="2016-12-29" class="blog-time text-center">
                    29/12/2016
                </time>
            </div>
        </div>

        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <i class="fas fa-feather-alt"></i>
            </div>
            <div class="divider-custom-line"></div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-8 mx-auto mb-4">
                <p>Konstantin Kudryashov (alias <a href="https://twitter.com/everzet">@everzet</a>) gave us a nice present when releasing <a href="https://github.com/Behat/Behat/blob/master/CHANGELOG.md">Behat 3.3.0</a> on Christmas! 🎅</p>
<p><a href="http://gifsboom.net/post/135889409639/santa-claus-cat-%E3%81%8B%E3%81%94%E7%8C%AB-%EF%BD%82%EF%BD%8C%EF%BD%8F%EF%BD%87"><img src="/img/posts/santa_kitten.gif" alt="" /></a></p>
<p>The main feature of this new version is <a href="https://github.com/Behat/Behat/pull/974">Helper containers</a> which is something I did not really expected but don't know how I lived without until now. It allows to define and inject reusable services into Behat contexts, and more!</p>
<p>I think it's a good occasion to review a bit how Behat works but if you are already a Behat master you can…</p>
<p><em>tl;dr: go directly to <a href="#dependencyinjectiontotherescue">dependency injection</a> or the <a href="#useyourphpdicontainer">PHP-DI example</a>.</em></p>
<h2 id="santaclausdelivery">Santa Claus Delivery</h2>
<p>Imagine we have to test that Santa is checking if children really deserve their presents, our <code>christmas_delivery.feature</code> could look like:</p>
<pre><code class="language-gherkin">Feature: Santa's delivery

    Scenario: Behaving children should have some presents
        Given the child is not on the naughty child list
        And the child wanted a "Playstation 4"
        When Santa Claus makes his delivery
        Then child should find a "Playstation 4" under the christmas tree

    Scenario: Misbehaving children will get what they deserve
        Given the child is on the naughty child list
        And the child wanted a "Playstation 4"
        When Santa Claus makes his delivery
        Then child should find a "bag of charcoal" under the christmas tree</code></pre>
<p>The christmas delivery &quot;database&quot; is stored in a single <code>christmas_delivery.json</code> file that looks like this:</p>
<pre><code class="language-javascript">{
    "naughtyChildren": [],
    "wishlist": [],
    "naughtyPresent": "a bag of charcoal",
    "deliveredPresents": []
}</code></pre>
<p>For the sake of this example, let's pretend we organize our code into three different contexts, first we have the <code>NaughtyListContext.php</code>:</p>
<pre><code class="language-php">&lt;?php

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
</code></pre>
<p>Then the <code>PresentContext.php</code>:</p>
<pre><code class="language-php">&lt;?php

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
}</code></pre>
<p>And finally the <code>DeliveryContext.php</code>:</p>
<pre><code class="language-php">&lt;?php

use Behat\Behat\Context\Context;

class DeliveryContext implements Context
{
    /**
     * @BeforeSuite
     */
    public static function initList()
    {
        file_put_contents('christmas_delivery.json', json_encode([
            'naughtyChildren' =&gt; [],
            'wishlist' =&gt; [],
            'naughtyPresent' =&gt; 'bag of charcoal',
            'deliveredPresents' =&gt; [],
        ], JSON_PRETTY_PRINT));
    }

    /**
     * @When Santa Claus makes his delivery
     */
    public function santaClausMakesHisDelivery()
    {
        $deliveryList = json_decode(file_get_contents('christmas_delivery.json'), true);

        foreach ($deliveryList['wishlist'] as $child =&gt; $present) {
            $hasBeenNaughty = in_array($child, $deliveryList['naughtyChildren']);
            if ($hasBeenNaughty) {
                $deliveryList['deliveredPresents'][$child] = $deliveryList['naughtyPresent'];
            } else {
                $deliveryList['deliveredPresents'][$child] = $present;
            }
        }

        file_put_contents('christmas_delivery.json', json_encode($deliveryList, JSON_PRETTY_PRINT));
    }
}</code></pre>
<p>So how do you like that?! What? ... You don't? 😭</p>
<p>Ok yes, you probably picture me with blood in my eyes while I'm writing this blog post and you're right because I've just sticked a pair of scissors in them to end the agony.</p>
<h2 id="addingclasses">Adding classes</h2>
<p>On a more serious matter, we know how to solve this problem by refactoring the code into classes. First let's create a <code>ChristmasStorage.php</code> class to handle the filesystem:</p>
<pre><code class="language-php">&lt;?php

class ChristmasStorage
{
    private $filepath = 'christmas_delivery.json';

    public function getData()
    {
        $json = file_get_contents($this-&gt;filepath);

        return json_decode($json, true);
    }

    public function saveData(array $data)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents($this-&gt;filepath, $json);
    }
}</code></pre>
<p>A <code>NaughtyListRepository.php</code> to handle naughty children:</p>
<pre><code class="language-php">&lt;?php

class NaughtyListRepository
{
    protected $storage;

    public function __construct(ChristmasStorage $storage)
    {
        $this-&gt;storage = $storage;
    }

    public function add($child)
    {
        $list = $this-&gt;storage-&gt;getData();
        $list['naughtyChildren'][] = $child;

        $this-&gt;storage-&gt;saveData($list);
    }

    public function remove($child)
    {
        $list = $this-&gt;storage-&gt;getData();
        $index = array_search($child, $list['naughtyChildren']);

        if ($index !== false) {
            unset($list['naughtyChildren'][$index]);
            $this-&gt;storage-&gt;saveData($list);
        }
    }

    public function isNaughty($child)
    {
        $list = $this-&gt;storage-&gt;getData();

        return in_array($child, $list['naughtyChildren']);
    }
}</code></pre>
<p>And a <code>PresentRepository.php</code> to handle the wishlist and the present delivery:</p>
<pre><code class="language-php">&lt;?php

class PresentRepository
{
    protected $storage;
    protected $naughtlyListRepository;

    public function __construct(
        ChristmasStorage $storage,
        NaughtyListRepository $naughtyListRepository
    )
    {
        $this-&gt;storage = $storage;
        $this-&gt;naughtyListRepository = $naughtyListRepository;
    }

    public function wish($child, $present)
    {
        $list = $this-&gt;storage-&gt;getData();
        $list['wishlist'][$child] = $present;

        $this-&gt;storage-&gt;saveData($list);
    }

    public function deliverPresent($child)
    {
        $list = $this-&gt;storage-&gt;getData();

        if ($this-&gt;naughtyListRepository-&gt;isNaughty($child)) {
            $list['deliveredPresents'][$child] = $list['naughtyPresent'];
        } else {
            $list['deliveredPresents'][$child] = $list['wishlist'][$child];
        }

        $this-&gt;storage-&gt;saveData($list);
    }

    public function getDeliveredPresent($child)
    {
        $list = $this-&gt;storage-&gt;getData();

        return $list['deliveredPresents'][$child];
    }
}</code></pre>
<p>And the updated contexts classes, starting with the <code>NaughtyListContext.php</code>:</p>
<pre><code class="language-php">&lt;?php

use Behat\Behat\Context\Context;

class NaughtyListContext implements Context
{
    protected $naughtyListRepository;

    public function __construct()
    {
        $this-&gt;naughtyListRepository = new NaughtyListRepository(new ChristmasStorage);
    }

    /**
     * @Given the child is not on the naughty child list
     */
    public function theChildIsNotOnTheNaughtyChildList()
    {
        $this-&gt;naughtyListRepository-&gt;remove('gabriel');
    }

    /**
     * @Given the child is on the naughty child list
     */
    public function theChildIsOnTheNaughtyChildList()
    {
        $this-&gt;naughtyListRepository-&gt;add('gabriel');
    }
}</code></pre>
<p>The <code>PresentContext.php</code>:</p>
<pre><code class="language-php">&lt;?php

use Behat\Behat\Context\Context;

class PresentContext implements Context
{
    protected $presentRepository;

    public function __construct()
    {
        $storage = new ChristmasStorage;

        $this-&gt;presentRepository = new PresentRepository(
            $storage,
            new NaughtyListRepository($storage)
        );
    }

    /**
     * @Given the child wanted a :present
     */
    public function theChildWantedA($present)
    {
        $this-&gt;presentRepository-&gt;wish('gabriel', $present);
    }

    /**
     * @Then child should find a :present under the christmas tree
     */
    public function childShouldFindAUnderTheChristmasTree($present)
    {
        $deliveredPresent = $this-&gt;presentRepository-&gt;getDeliveredPresent('gabriel');

        if ($deliveredPresent !== $present) {
            throw new \Exception(sprintf(
                'Delivered present was "%s" but "%s" was expected.',
                $deliveredPresent,
                $present
            ));
        }
    }
}</code></pre>
<p>And finally the <code>DeliveryContext.php</code>:</p>
<pre><code class="language-php">&lt;?php

use Behat\Behat\Context\Context;

class DeliveryContext implements Context
{
    protected $presentRepository;

    public function __construct()
    {
        $storage = new ChristmasStorage;

        $this-&gt;presentRepository = new PresentRepository(
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
        $storage-&gt;saveData([
            'naughtyChildren' =&gt; [],
            'wishlist' =&gt; [],
            'naughtyPresent' =&gt; 'bag of charcoal',
            'deliveredPresents' =&gt; [],
        ]);
    }

    /**
     * @When Santa Claus makes his delivery
     */
    public function santaClausMakesHisDelivery()
    {
        $this-&gt;presentRepository-&gt;deliverPresent('gabriel');
    }
}</code></pre>
<h2 id="betterbutnotperfectyet">Better, but not perfect yet</h2>
<p>Well, it's no work of art but it's getting somewhere. We have some problems though. First of all we already have some nasty dependencies issues: the <code>PresentRepository</code> class depends on <code>ChristmasStorage</code> as well as <code>NaughtyListRepository</code> that ALSO depends on <code>ChristmasStorage</code>. In a regular project you know it can be far worse than that and open the gates of the Dependency Hell.</p>
<p>Another issue is that Context classes are instanciated once per scenario, that means that if a class is instanciated in 10 contexts and your suite has 158 scenarios, it will be instanciated 1580 times when the test suite is executed. Just in this little example the <code>ChrismasStorage</code> class will be instanciated 7 times (3 times in each scenario, and one more in the <code>@BeforeSuite</code> hook). Here it's just a little class reading a file, but in a real world project you want to avoid unnecessary instanciation costs.</p>
<p>We could also have used a Trait, but they are a bit unpractical, even if they are still a good way to share some code between contexts, it's best for &quot;toolbox code&quot;, see more about it in <a href="http://www.tentacode.net/10-tips-with-behat-and-mink#9godrywithtraits">another Behat related post</a>.</p>
<p>Sorry for the (very) long introduction to the problem we were facing before, now let's see how it can be fixed in Behat 3.3.0!</p>
<h2 id="dependencyinjectiontotherescue">Dependency injection to the rescue</h2>
<p>I think Konstantin found a very smart and simple way to describe dependency injection directly in the <code>behat.yml</code> configuration file:</p>
<pre><code class="language-yaml">default:
  suites:
    default:
      contexts:
        - FirstContext:
          - "@shared_service"
        - SecondContext:
          - "@shared_service"

      services:
        shared_service: "SharedService"</code></pre>
<p>This simple example will create an instance of the <code>SharedService</code> and pass the instance as a constructor argument for each context.</p>
<p>Note that this could not work on our previous example because two of our &quot;services&quot; need constructor arguments and can't be directly instanciated. We could solve this issue by declaring our services as &quot;factories&quot; that will instanciate our services:</p>
<pre><code class="language-yaml">default:
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
                    factory_method: "create"</code></pre>
<p>This is still not really convenient because:</p>
<ul>
<li>We need to add a static <code>create</code> method to each service.</li>
<li>We cannot pass services as arguments to the factory methods <code>create</code>, so we still need to instanciate codependencies inside the <code>create</code> method.</li>
<li>We also have to define a new service key in <code>behat.yml</code> each time we want to add a service.</li>
</ul>
<h2 id="yaycontainers">Yay, containers!</h2>
<p>You might have heard that containers are evil but in this case they are really helpful! Another smart move from Konstantin was to use the <code>ContainerInterface</code> from the <a href="https://github.com/container-interop/container-interop">Container Interop</a> project.</p>
<p>We will directly use a factory method so that we are sure to always use the same instance of the container and that our services are instanciated only once when the tests are run. Here is how the <code>behat.yml</code> file looks like:</p>
<pre><code class="language-yaml">default:
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
            services: ChristmasContainer::create</code></pre>
<p>And our <code>ChristmasContainer.php</code> file:</p>
<pre><code class="language-php">&lt;?php

use Interop\Container\ContainerInterface;

class ChristmasContainer implements ContainerInterface
{
    protected static $instance;
    protected $services;

    public function __construct()
    {
        $this-&gt;services['storage'] = new ChristmasStorage;

        $this-&gt;services['naughtyListRepository'] = new NaughtyListRepository(
            $this-&gt;services['storage']
        );

        $this-&gt;services['presentRepository'] = new PresentRepository(
            $this-&gt;services['storage'],
            $this-&gt;services['naughtyListRepository']
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
        return in_array($id, array_keys($this-&gt;services));
    }

    public function get($id)
    {
        return $this-&gt;services[$id];
    }
}</code></pre>
<p>Voilà! This is a practical way of defining and injecting services, and we also are sure that services won't be instanciated more than once during the test suite execution.</p>
<p>Edit: As <a href="https://twitter.com/BehatPHP/status/814481086411657217">Konstantin rightfuly points out</a>, using a Singleton container will break test isolation and can be a bad practice, it's your choice to see if you prefer to garanty that the state is reset between every scenario or if you value more the performance gained by setting up your container and services only once. My opinion is that when writing tests you can sometime allow yourself some practices that you won't allow in your production code, personally I don't really bother that the in memory state is perfectly reset (because what I test is generally in another process anyway, via a HTTP server or a command line utility), but I do care that my data state is properly reset between scenario (for example, the database should be reset). Anyway, just my opinion here. 😉</p>
<h2 id="useyourphpdicontainer">Use your PHP-DI container</h2>
<p>Of course because Behat 3.3.0 uses Container Interop, you can use the same container as you use in your application if it follows this convention. This is REALLY helpful when you want to reuse some of your project code (yes, you can, even in your tests) like repositories. Here is how to create your <a href="http://php-di.org/">PHP-DI</a> container in Behat:</p>
<pre><code class="language-php">&lt;?php

class ContainerFactory
{
    protected static $container;

    public static function create()
    {
        if (!self::$container) {
            $builder = new \DI\ContainerBuilder();
            $builder-&gt;addDefinitions(require('/path/to/definitions.php'));

            self::$container = $builder-&gt;build();
        }

        return self::$container;
    }
}</code></pre>
<p>Unfortunately you can forget PHP-DI's auto wiring system in Behat for the moment but, who knows, someone might just make it happen one day!</p>
<p>Thanks for reading this way too long blog post! Please leave a comment if you have any question or suggestion.</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mx-auto">
                <p class="lead  text-center h1">
                    If you scrolled this far, that means you enjoyed this post.
                </p>
            </div>
        </div>

        <div class="text-center mt-1 mb-5">
                        <a 
                class="btn btn-lg btn-primary shadow-sm"
                href="https://twitter.com/intent/tweet?text=Dependency%20injection%20with%20Behat%20%28and%20PHP-DI%29%20%E2%80%94%20by%20%40tentacode%0A%0Ahttps%3A%2F%2Ftentacode.dev%2Fbehat-dependency-injection-phpdi"
            >
                <i class="fab fa-twitter mr-2"></i>
                share on twitter
            </a>
        </div>
    </div>
</section>


    <x-blog.footer />
</x-layout>
