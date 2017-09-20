<div id="footer-container">
    <div>
        Copyright © {!! date("Y") !!} SteamGM.com, developed by
        <a href="https://www.linkedin.com/in/giorlando/" target="_blank"
           style=""> John Orlando</a>
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