<nav class="{{ $page === 'blog' ? 'transparent-top' : '' }} navbar navbar-expand-xl bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="/#top">
            <i class="fas fa-laptop-code"></i>
            tentacode<span class="text-pink">.dev</span>
        </a>
        <button id="menu-button-closed" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"
            class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded"
            data-bs-target="#navbarResponsive" data-bs-toggle="collapse" type="button">
            <span id="menu-button-closed-text" class="d-none d-sm-inline">Menu </span><i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/#my-strengths">My strengths</a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{ route('blog.list') }}">Blog</a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/#portfolio">Portfolio</a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/#contact">Get in touch</a>
                </li>
            </ul>
        </div>
    </div>
</nav>