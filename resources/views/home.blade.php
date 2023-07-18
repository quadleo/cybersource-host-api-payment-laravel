<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CyberSource Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased text-gray-600 min-h-full flex flex-col [overflow-anchor:none]">
    <div class="bg-white">
        <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
          <div class="relative isolate overflow-hidden bg-blue-900 px-6 pt-16 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
            <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
              <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">CyberSource Payment.<br>Start Testing.</h2>
              <p class="mt-6 text-lg leading-8 text-gray-300">Please Start from here.</p>
              <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                <a href="{{route('hosted-pay')}}" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Hosted Payment</a>
                <a href="{{route('api-index')}}" class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-blue-900 shadow-sm hover:bg-blue-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Api Payment</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
</body>

</html>
