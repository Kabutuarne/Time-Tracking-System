<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Project Manager' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { // color scheme
                        accent: '#380036',
                        textcol: '#F1E4E8',
                        textcol2: 'rgb(197, 168, 183)',
                        dark: '#26081C',
                        primary: '#01BAEF',
                        darker: '#150811',
                        secondary: '#0CBABA'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark">
    <x-nav />

    <main id="app" class="container mx-auto px-4 py-8">
        {{ $slot }}
        {{-- notification flash data --}}
        @php
            $flashData = [];
            if (session()->has('success'))
                $flashData['success'] = session('success');
            if (session()->has('error'))
                $flashData['error'] = session('error');
            if (session()->has('warning'))
                $flashData['warning'] = session('warning');
            if (session()->has('info'))
                $flashData['info'] = session('info');
        @endphp

        <notification :flash-data='@json($flashData)'></notification>
        <confirmation-modal :visible="confirm.visible" :title="confirm.title" :message="confirm.message"
            @update:visible="confirm.visible = $event" @confirmed="confirm.action && confirm.action()" />
    </main>

</body>

</html>