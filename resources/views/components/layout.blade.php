<!DOCTYPE html>
<!--
    Oh non! Vous avez hacké mon code source! 🙀

    Voici un cadeau en récompense 👏:

    +      o     +              o
        +             o     +       +
    o          +
        o  +           +        +
    +        o     o       +        o
    -_-_-_-_-_-_-_,—————,      o
    _-_-_-_-_-_-_-|   /\_/\
    -_-_-_-_-_-_-~|__( ^ .^)  +     +
    _-_-_-_-_-_-_-""  ""
    +      o         o   +       o
        +         +
    o        o         o      o     +
        o           +
    +      +     o        o      +
-->
<html lang="fr">
<head>
   <x-layout.metas
   page="{{ $page ?? 'index'  }}"
   page-title="{{ $pageTitle ?? ''  }}"
   page-image="{{ $pageImage ?? '' }}"
   page-description="{!! isset($pageDescription) ? $pageDescription : '' !!}" />
</head>
<body id="top">
    <x-layout.top-menu page="{{ $page ?? 'index' }}" />

    {{ $slot }}

    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
