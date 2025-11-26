<section class="page-section blog text-dark background-light mb-0 pb-0" id="blog">
    <div class="container">

        <h2 class="page-section-heading text-center text-uppercase mb-0">Sur mon blog</h2>

        <div class="divider-custom" aria-hidden="true">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <i class="fas fa-feather-alt"></i>
            </div>
            <div class="divider-custom-line"></div>
        </div>

        <div class="row">
            @foreach ($posts as $post)
                <div class="col-lg-8 mx-auto" lang="{{ $post->language }}">
                    <h3><a href="{{ route('blog.detail', ['blogSlug' => $post->slug]) }}">
                        {{ $post->content->title }}
                    </a></h3>
                    <p class="lead">
                        {!! $post->content->getDescription() !!}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a class="btn btn-xl btn-outline-dark shadow-sm" href="{{ route('blog.list') }}">
                <i class="fas fa-plus-circle me-2"></i>
                liste des articles de blog
            </a>
        </div>
    </div>
</section>
