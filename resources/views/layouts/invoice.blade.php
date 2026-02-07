<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyaw Family Scaffolding - Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Open Sans', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Roboto', sans-serif;
        }

        .invoice-container {
            width: 794px;
            /* A4 width in pixels at 96dpi */
            margin: 0 auto;
        }
    </style>
</head>

<body class="bg-gray-100 p-5">
    @yield('invoice-content')
    
</body>

</html>