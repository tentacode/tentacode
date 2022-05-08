<x-layout navbar-class="navbar-blog">
    <section class="page-section blog text-dark background-light mt-5 mb-0 pb-5" id="blog">
        <div class="container">
            <h1 class="page-section-heading text-center text-uppercase mt-5 mb-0">
                tentacode<span class="text-pink">.dev</span> blog
            </h1>
    
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon">
                    <i class="fas fa-feather-alt"></i>
                </div>
                <div class="divider-custom-line"></div>
            </div>
    
            <div class="row mt-5">
                @foreach ($posts as $post)
                    <div class="col-lg-8 mx-auto mb-4">
                        <h4>
                            <a href="{{ route('blog.detail', ['blogSlug' => $post->slug]) }}">{{ $post->content->title }}</a>
                            <small class="text-pink">— {{ $post->publishedAt->format('d/m/Y') }} </small>
                        </h4>
                        <p class="lead">
                            {!! $post->content->getDescription() !!}
                        </p> 
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    @include('blog.footer')
</x-layout>
