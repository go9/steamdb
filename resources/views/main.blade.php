<style>
    body {
        background-color: aliceblue;
    }

    .nav-container {
        background-color: white;
    }

    .content-container {
        background-color: white;

    }

    .footer-container {

    }
</style>

<!DOCTYPE html>
<html lang="en">
@yield('stylesheets')
@yield('scripts')

<head>
    @include("partials._head")
</head>

<body>

    <div id="loader"
         style="
            display:none;
            position:fixed;
            width:100%;
            height:100%;
            background-color:rgba(240,248,255,.2);
            z-index:1000000;">
        <div style="
            width:100%;
            height:100%;
            background: url(/images/loading.gif);
            background-repeat: no-repeat;
            background-position: center;">
        </div>
    </div>
    <script>
        /*
         $(document).on({
         ajaxStart: function() {
         $("#loader").show()
         },
         ajaxStop: function() {
         $("#loader").hide();
         }
         });
         */
    </script>

    @include("partials._nav")


    <div id="content-container" class="container-fluid">
        @include('partials._messages')
        @yield("content")

        <div id="footer-container" class="container-fluid">
            @include("partials._footer")
        </div>
    </div>

</body>
</html>