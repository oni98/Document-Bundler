<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @font-face {
            font-family: 'Nikosh';
            src: URL(URL('fonts/Nikosh.ttf')) format('truetype');
        }

        * {
            font-family: Nikosh;
            z-index: 2;
        }
    </style>
</head>

<body>
    <div style="z-index:9999" id="main_div">
        {!! $view !!}
    </div>

    <script>
        $('#main_div title').remove();
    </script>
</body>

</html>
