<x-layout
    page="blog-detail"
    page-title="{{ $post->content->title }}"
    page-image="{{ $post->content->getFirstImage() }}"
    page-description="{!! $post->content->getDescription() !!}"
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
    </div>
</section>

    @include('blog.footer')
</x-layout>
