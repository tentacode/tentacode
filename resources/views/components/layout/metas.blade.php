<link rel="DNS-prefetch" href="//fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>

<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">

<meta content="Gabriel Pillet" name="author">
<meta name="robots" content="index, follow">
<title>{{ ($page === 'blog-detail') ? $pageTitle : '@tentacode - Gabriel Pillet, Backend Developer' }}</title>

<link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" as="font" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&display=swap" rel="stylesheet" as="font" type="text/css">

<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:image" content="https://tentacode.dev/img/twitter_card.png" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="628" />

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@tentacode">
<meta name="twitter:creator" content="@tentacode">
<meta name="twitter:image" content="https://tentacode.dev/img/twitter_card.png">

<link rel="icon" href="favicon.ico" />

@if ($page === 'blog-detail')
    <meta property="og:title" content="{{ $pageTitle }}" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="{!! $pageDescription !!}" />
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{!! $pageDescription !!}">
    <meta content="{!! $pageDescription !!}" name="description">
@else
    <meta property="og:title" content="Hello there!" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="My name is Gabriel Pillet and I'm a backend developer (PHP, Symfony, Laravel)." />
    <meta name="twitter:title" content="Hello there!">
    <meta name="twitter:description" content="My name is Gabriel Pillet and I'm a backend developer (PHP, Symfony, Laravel).">
    <meta content="Hello! My name is Gabriel Pillet and I'm a backend developer (PHP / Laravel / Symfony)." name="description">
@endif