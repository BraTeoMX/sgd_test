<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.partials.head')
        @livewireStyles
    </head>
    <body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl" style="background-color: #F3F3F3">
        @include('layouts.partials.header')
           
        @include('layouts.partials.sidebar')
    
        <main id="content" role="main" class="main">
            <div class="content container-fluid">
                @include('flash::message')
               
                  @yield('content')
                              
            </div>

        </main>
            @include('layouts.partials.footer')

        <!-- container-scroller -->
        @include('layouts.partials.footer-scripts')
        <!-- End custom js for this page-->
        <!-- JS Plugins Init. -->
        <script>
          $(document).on('ready', function () {
            // initialization of navbar vertical navigation
            var sidebar = $('.js-navbar-vertical-aside').hsSideNav();

            // initialization of tooltip in navbar vertical menu
            $('.js-nav-tooltip-link').tooltip({ boundary: 'window' })

            $(".js-nav-tooltip-link").on("show.bs.tooltip", function(e) {
              if (!$("body").hasClass("navbar-vertical-aside-mini-mode")) {
                return false;
              }
            });

            // initialization of unfold
            $('.js-hs-unfold-invoker').each(function () {
              var unfold = new HSUnfold($(this)).init();
            });
          });
        </script>

        <!-- IE Support -->
        <script>
          if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="../assets/vendor/babel-polyfill/polyfill.min.js"><\/script>');
        </script>
        @livewireScripts
    </body>
</html>
