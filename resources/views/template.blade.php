<html>
    <head>
        <title>@yield("title", "Default Title") - Ukrainians find help</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset("css/app.css") }}" rel="stylesheet" />
        <script type="module" src="{{ asset("js/svelte-include.js") }}"></script>
    </head>

    <body>
        @yield("content")
    </body>
</html>
