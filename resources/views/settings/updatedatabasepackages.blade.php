@extends('settings.index')

@section('settings-content')
    <h1>Update Database</h1>
    <p>
        Number of packages remaining:
        <input id="counter" value="{{count($games)}}">
    </p>
    <button class="btn" id="start">Start</button>
    <button class="btn" id="stop" style="display:none;">Stop</button>

    <div style="height:500px;overflow:scroll;">
        <table class="table" id="results">
            <thead>
            <tr>
                <th>Appid</th>
                <th>Name</th>
                <th>Type</th>
                <th>Message</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
        var interval = null;
        var counter = 0;
        var games = {!! json_encode($games) !!};
        console.log("Number of games: " + games.length);
        console.log(games);

        $("#start").click(function () {
            // hide the start button and show the stop button
            $("#start").hide();
            $("#stop").show();

            interval = setInterval(function () {
                updateGame(games[counter]);
                counter++;
            }, 3000);
        });

        $("#stop").click(function () {
            // hide the stop button and show the start button
            $("#start").show();
            $("#stop").hide();
            clearInterval(interval);
        });

        function updateGame(id){
            console.log("Starting " + id);
            // Search
            $.ajax({
                url: '/games/store_package/' + id,
                type: 'GET',
                dataType : 'json',
                async: false,
                success: function (json, response) {
                    console.log(json);

                    if(json.success === true){
                        $('#results').prepend(
                            '<tr>'
                            +'<td>'+json.data.steam_id+'</td>'
                            +'<td>'+json.data.name+'</td>'
                            +'<td>'+json.data.type+'</td>'
                            +'<td>Added Successfuly</td>'
                            +'</tr>');
                    }

                    if(json.success === false){
                        $('#results').prepend(
                            '<tr>'
                            +'<td>'+json.data.steam_id+'</td>'
                            +'<td></td>'
                            +'<td></td>'
                            +'<td>'+json.message+'</td>'
                            +'</tr>');
                    }


                    $('#counter').val( function(i, oldval) {
                        return --oldval;
                    });
                },
                error: function (request, error) {
                    console.log(request);
                },

            });

        }

    </script>



@endsection

<script>
    function start() {
        $.ajax({
            url: '/games/sync_applist_from_steam',
            type: 'GET',
            dataType: 'json',
            success: function (json) {
                $("#start-button").hide();

            }
        });
    }
</script>