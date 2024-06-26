<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Transit Tracker Regio' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body>
    <nav class="w-full bg-primary-40 z-40 text-white fixed top-0  shadow-2" x-data="{ navShow: false }">
        <div class="md:container flex flex-col md:flex-row   md:gap-4 md:items-center md:px-4 mx-auto md:h-16 bg-primary-50 md:bg-transparent" x-bind:class="!navShow ? 'hidden md:flex' : ''">
            <a href="#" class="flex items-center px-2 py-1 -mx-2 -my-1 transition-colors text-xl bg-white bg-opacity-0 rounded hover:bg-opacity-10 font-heading">
                <svg viewBox="0 0 295.01 403.72" xmlns="http://www.w3.org/2000/svg" class="h-6 mr-2">
                    <path fill="#fff" d="m147.51 1.875c-80.34 0-145.63 65.291-145.63 145.63 0 84.709 87.863 198.79 126.94 245.63 9.708 11.651 27.428 11.651 37.137 0 39.32-46.845 127.18-160.92 127.18-245.63 0-80.34-65.291-145.63-145.63-145.63zm0 70.631c34.895 0 63.158 3.9481 63.158 31.58v78.945c0 6.948-3.0785 13.185-7.8945 17.527v10.105c0 6.553-5.3678 11.842-11.842 11.842-6.553 0-11.842-5.3678-11.842-11.842v-3.9473h-63.158v3.9473a11.826 11.826 0 0 1-11.842 11.842 11.826 11.826 0 0 1-11.844-11.842v-10.105c-4.816-4.342-7.8945-10.579-7.8945-17.527v-78.945c0-27.632 28.263-31.58 63.158-31.58zm-47.367 31.58v39.473h94.734v-39.473h-94.734zm11.842 63.156a11.826 11.826 0 0 0-11.842 11.844 11.826 11.826 0 0 0 11.842 11.842c6.552 0 11.842-5.2888 11.842-11.842a11.826 11.826 0 0 0-11.842-11.844zm71.051 0c-6.552 0-11.842 5.2908-11.842 11.844a11.826 11.826 0 0 0 11.842 11.842 11.826 11.826 0 0 0 11.842-11.842 11.826 11.826 0 0 0-11.842-11.844z" />
                </svg>

                <p>Transit Tracker &bull; Regio</p>
            </a>

            <span class="grow"></span>
        </div>
    </nav>
        @inertia
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    </body>
</html>
