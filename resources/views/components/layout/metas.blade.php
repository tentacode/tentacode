<link rel="DNS-prefetch" href="//fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">

<meta content="Gabriel Pillet" name="author">
<meta name="robots" content="index, follow">
<title>{{ ($page === 'blog-detail') ? $pageTitle : '@tentacode - Gabriel Pillet, de Dev Backend Expert à CTO en Freelance, sur Lyon et Full-Remote' }}</title>

<link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" as="font" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&display=swap" rel="stylesheet" as="font" type="text/css">

<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="628" />

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@tentacode">
<meta name="twitter:creator" content="@tentacode">

<link rel="icon" href="favicon.ico" />

@if ($page === 'blog-detail')
    <meta property="og:title" content="{{ $pageTitle }}" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="{!! $pageDescription !!}" />
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{!! $pageDescription !!}">
    <meta content="{!! $pageDescription !!}" name="description">
    <meta name="twitter:image" content="{{ url($pageImage ?? '/img/twitter_card.png') }}">
    <meta property="og:image" content="{{ url($pageImage ?? '/img/twitter_card.png') }}" />
@else
    <meta property="og:title" content="Bonjour !" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="Moi c'est Gabriel Pillet et je suis développeur backend PHP (Symfony / Laravel) et CTO externalisé en Freelance." />
    <meta name="twitter:title" content="Bonjour !">
    <meta name="twitter:description" content="Moi c'est Gabriel Pillet et je suis développeur backend PHP (Symfony / Laravel) et CTO externalisé en Freelance.">
    <meta content="Moi c'est Gabriel Pillet et je suis développeur backend PHP (Symfony / Laravel) et CTO externalisé en Freelance." name="description">
    <meta name="twitter:image" content="{{ url('/img/twitter_card.png') }}">
    <meta property="og:image" content="{{ url('/img/twitter_card.png') }}" />
@endif
