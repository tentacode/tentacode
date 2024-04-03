<header class="masthead text-light text-center shadow">
    <div class="container d-flex align-items-center flex-column">
        <h1 class="masthead-heading text-uppercase mb-0">Bonjour !</h1>

        <div class="divider-custom divider-light">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="divider-custom-line"></div>
        </div>

        <p class="masthead-subheading font-weight-light" style="max-width: 650px">
            Moi c'est Gabriel et j'ai plus de quinze ans d'expérience dans le développement d'applications web, spécialisé sur PHP et Symfony.<br /><br />
            J'offre mon expertise en Freelance sur des rôles de développeur expert, Lead Dev, Team/Tech Lead ou CTO on demand.
            J'ai aussi une affinité avec l'assurance qualité et la philosophie DevOps.
        </p>

        <div class="text-center mt-5">
            <a class="btn btn-xl btn-outline-light shadow-sm" href="{{ route('blog.detail', ['blogSlug' => 'ma-mission-ideale']) }}">
                <i class="fas fa-binoculars me-2"></i>
                Quelle est ma mission idéale ?
            </a>
        </div>
    </div>
</header>
