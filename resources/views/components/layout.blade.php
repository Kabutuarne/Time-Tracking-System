<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Project Manager' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { // color scheme
                        primary: '#ECA400',
                        secondary: '#EAF8BF',
                        accent: '#006992',
                        dark: '#27476E',
                        darker: '#001D4A'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-accent">
    <x-nav />

    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>
</body>

</html>