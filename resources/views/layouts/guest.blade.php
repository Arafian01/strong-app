<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <style>
        .select2-container .select2-selection--single {
            width: 100% !important;
            background-color: #f9fafb;
            border: 1px solid #d1d5db !important;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            height: 43px;
            border-radius: 0.4rem;
            color: #1f2937;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            top: 20% !important;
            right: 8px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            font-size: 14px !important;
            top: -2px;
            left: -6px;
            position: relative;
            color: #1f2937;
        }

        .select2-search__field {
            font-size: 14px !important;
            border-radius: 0.5rem;
        }

        .select2-results {
            font-size: 14px !important;
            border-radius: 0px 10px 0px 10px;
        }

        /* Custom Styles for Login */
        .auth-card {
            @apply bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-gray-100;
        }
        .input-group {
            @apply mb-6;
        }
        .input-label {
            @apply text-sm text-gray-600 mb-2 block;
        }
        .form-input {
            @apply block w-full px-4 py-3 bg-white/50 border-2 border-gray-200 rounded-lg 
            focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-200 
            transition duration-200;
        }
        .toggle-switch {
            @apply relative inline-flex items-center cursor-pointer;
        }
        .toggle-switch input {
            @apply sr-only peer;
        }
        .toggle-slider {
            @apply w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full 
            after:absolute after:top-0.5 after:left-0.5 after:bg-white after:w-5 after:h-5 
            after:rounded-full after:transition-all after:duration-300 peer-checked:bg-red-600;
        }
        .btn-primary {
            @apply w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 
            shadow-lg transform transition-all duration-200 hover:scale-[1.02] active:scale-95;
        }
        .loading-spinner {
            @apply animate-spin h-5 w-5 text-white;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="auth-card w-full sm:max-w-md mt-6 px-6 py-8">
            {{ $slot }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Select2 Initialization
        $(".js-example-placeholder-single").select2({
            placeholder: "Pilih...",
            allowClear: true,
            width: '100%'
        });

        // Alpine.js Initialization (if needed)
        document.addEventListener('alpine:init', () => {
            // Initialization code here
        });
    </script>
    @stack('scripts')
</html>