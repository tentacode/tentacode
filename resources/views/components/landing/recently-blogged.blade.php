<section class="page-section blog text-dark background-light mb-0 pb-0" id="blog">
    <div class="container">

        <h2 class="page-section-heading text-center text-uppercase mb-0">Recently blogged</h2>

        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <i class="fas fa-feather-alt"></i>
            </div>
            <div class="divider-custom-line"></div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h4><a href="{{ route('blog.detail', ['blogSlug' => '10-games-for-your-left-brain']) }}">10+ games for your Left Brain</a></h4>
                <p class="lead">
                    Edit: I&#039;ve had quite a few people telling me that the Left brain / Right brain is a myth,
                    I&#039;m just here to share some cool games and it was a nice catch phrase but feel free to do
                    some research on…
                </p>
            </div>
            <div class="col-lg-8 mx-auto">
                <h4><a href="{{ route('blog.detail', ['blogSlug' => 'behat-dependency-injection-phpdi']) }}">Dependency injection with Behat (and PHP-DI)</a>
                </h4>
                <p class="lead">
                    Konstantin Kudryashov (alias @everzet) gave us a nice present when releasing Behat 3.3.0 on
                    Christmas! 🎅

                    The main feature of this new version is Helper containers which is something I did not…
                </p>
            </div>
            <div class="col-lg-8 mx-auto">
                <h4><a href="{{ route('blog.detail', ['blogSlug' => 'metabase-with-kittens']) }}">Metabase with kittens</a></h4>
                <p class="lead">
                    As you read in the previous blog Easy charting with Metabase, I became quite fond of this tool
                    this year. I believe it&#039;s a great and easy way to display simple data, and the quality for
                    an…
                </p>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="btn btn-xl btn-outline-dark shadow-sm" href="{{ route('blog.list') }}">
                <i class="fas fa-plus-circle me-2"></i>
                and 5 more
            </a>
        </div>
    </div>
</section>