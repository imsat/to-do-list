<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="icon" href="https://tailwindcss.com/plus-assets/img/favicon/favicon.ico">
    @vite('resources/css/app.css')

</head>
<body>
<div id="app"></div>
@vite('resources/js/app.js')

<script>
    // For set app url
    window.appUrl =  @json(config('app.url'));
</script>
</body>
</html>
