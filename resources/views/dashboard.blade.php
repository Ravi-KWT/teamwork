<!DOCTYPE html>
<html ng-app="app">
    <head>
        <title>Management information system|@yield('title')</title>
        <link rel="stylesheet" href="{{ elixir('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
        <script type="text/javascript">
        window.onload = function()
        {
          // fix for windows 8
          if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
            document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />'
        }
        </script>
    </head>
    <body ng-controller="BodyCtrl" class="fixed-header menu-pin">
        
        <script src="{{ elixir('js/vendor.js') }}"></script>
        <script src="{{ elixir('js/app.js') }}"></script>
    </body>
</html>
