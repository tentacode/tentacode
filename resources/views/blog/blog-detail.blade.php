<x-layout 
    page="blog-detail"
    page-title="{{ $post->content->title }}"
    page-description="{{ $post->content->getDescription() }}"
>
    <section class="page-section blog-detail text-dark background-light mt-2 mb-0  pb-5" id="blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto mb-0 text-center">
                <h1 class="text-center text-uppercase mt-5 mb-3">
                    {{ $post->content->title }}
                </h1>
                <time datetime="2016-12-29" class="blog-time text-center">
                    {{ $post->publishedAt->format('d/m/Y') }}
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
                {!! $post->content->html !!}
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mx-auto">
                <p class="lead  text-center h1">
                    Did you enjoy this post?
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

    @include('blog.footer')
</x-layout>
