<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Fake Shop</title>
            {{-- CSS --}}
            @stack('styles')
            @include('backend.components.fixed.style')

  </head>
  <body >
    <script src="{{asset ('./dist/js/demo-theme.min.js?1684106062') }}"></script>
    <div class="page">

        {{-- Sidebar --}}
        @include('backend.components.fixed.sidebar')

        <div class="page-wrapper">

        {{-- Header --}}
        @include('backend.components.fixed.header')

               @yield('content')

         {{-- Footer --}}
         @include('backend.components.fixed.footer')

      </div>
    </div>

        {{-- Js --}}
        @include('backend.components.fixed.script')
    @stack('scripts')
  </body>
</html>
