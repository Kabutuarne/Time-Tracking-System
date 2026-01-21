<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: { // color scheme
                    accent: '#380036',
                    textcol: '#F1E4E8',
                    dark: '#26081C',
                    primary: '#01BAEF',
                    darker: '#150811',
                    secondary: '#0CBABA'
                }
            }
        }
    }
</script>

<body class="font-sans text-textcol bg-darker min-h-screen">
    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>
</body>

</html>