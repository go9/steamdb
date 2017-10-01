<!DOCTYPE html>
<html lang="en">
@yield('stylesheets')
@yield('scripts')

<head>
    @include("partials._head")
</head>

<body>

<div id="loader"
     style="display:none; position:fixed;width:100%;height:100%;background-color:rgba(240,248,255,.2);z-index:1000000;">
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

<div id="main-continer" style="overflow:hidden;">

    @include("partials._nav")

    <div id="content-container" class="container"
         style="min-height:1000px;background-color:#EDECED ;padding:15px; padding-top:0px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

        <div id="action-bar" style="width:100%;padding:5px;display:none;">
            <div class="dropdown pull-right" style="float:right;">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    @yield("actionbar-contents")
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div style="width:100%;">
            @include('partials._messages')
        </div>
        @yield("content")

    </div>

    @include("partials._footer")

</div>
</body>
</html>

