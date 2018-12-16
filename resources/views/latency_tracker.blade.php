<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>Latency Check</title>
        <script src="/js/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/semantic.min.css">
        <link href=" {{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper" id="app">
            <latency-list v-bind:host_map="host_map" ></latency-list>
        </div>
        <script src="/js/bootstrap.js"></script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
