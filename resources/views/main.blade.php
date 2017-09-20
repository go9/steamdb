<!DOCTYPE html>
<html lang="en">
@yield('stylesheets')
@yield('scripts')

<head>
    @include("partials._head")
</head>

<body style="
    background-image:url(/images/background.png);
    background-repeat: repeat-y;
    background-attachment: fixed;
    background-size: 100%, 100%;
">

<div id="loader" style="display:none; position:fixed;width:100%;height:100%;background-color:rgba(240,248,255,.2);z-index:1000000;">
    <div style="width:100%;height:100%;background: url(/images/loading.gif);background-repeat: no-repeat;background-position: center;">
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
</div>

<div id="main-continer">

    @include("partials._nav")

    <div id="content-container" class="container" style="min-height:1000px;background-color:#EDECED ;padding:15px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        @include('partials._messages')

        @yield("content")

    </div>

    @include("partials._footer")

</div>
</body>
</html>

