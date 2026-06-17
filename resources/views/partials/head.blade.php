<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(8px); }
        100% { opacity: 1; transform: translateY(0px); }
    }
    .animate-fade-in {
        animation: fadeSlideUp 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards;
    }
    .btn-press:active {
        transform: scale(0.97);
    }
</style>

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
