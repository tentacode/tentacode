<!DOCTYPE html>
<!--
    Oh no! You hacked my source code! 🙀

    Here is a gift as a reward 👏:

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
<html lang="en">
<head>
   <x-layout.metas />
</head>
<body id="top">
    <x-layout.top-menu navbar-class="{{ $navbarClass ?? 'transparent-top' }}" />

    {{ $slot }}

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
