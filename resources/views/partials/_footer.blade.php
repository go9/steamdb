<div id="footer-container">
    <div>
        Copyright © {!! date("Y") !!} SteamGM.com
        <div style="float:right;">
            <a href="https://www.linkedin.com/in/giorlando/" target="_blank">
                <i style="font-size:1.8em;" class="fa fa-linkedin" aria-hidden="true"></i>
            </a>
            &nbsp;
            <a href="https://github.com/go9/steamgm" target="_blank">
                <i style="font-size:1.8em;" class="fa fa-github" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>


<script>
    fitFooterToScreen();

    $(window).resize(function () {
        fitFooterToScreen();
    });

    function fitFooterToScreen() {
        // Make sure footer fits to screen
        $("#content-container").css("min-height",
            $(window).height()
            - $("#nav-container-top").height()
            - $("#nav-container-bottom").height()
            - $("#footer-container").height());
    }
</script>