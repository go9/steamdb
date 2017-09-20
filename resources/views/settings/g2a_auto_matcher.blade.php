@extends('settings.index')

@section('settings-content')
    <h1>G2a Auto Matcher</h1>
    <p>
        Number of games:
        <input id="counter" value="{!! count($games) !!}">
    </p>
    <button class="btn" id="start">Start</button>
    <button class="btn" id="stop" style="display:none;">Stop</button>

    <div style="height:500px;overflow:scroll;">
        <table class="table" id="results">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>GID</th>
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

        $("#start").click(function () {
            $("#start").hide();
            $("#stop").show();

            interval = setInterval(function () {
                updateGame(games[counter].id);
                counter++;
            }, 0);
        });

        $("#stop").click(function () {
            // hide the stop button and show the start button
            $("#start").show();
            $("#stop").hide();

            clearInterval(interval);
        });

        function updateGame(id){
            counter++;

            // Search
            $.ajax({
                url: '/games/auto_match',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    game_id : id},
                dataType : 'json',
                async: false,
                success: function (json, response) {
                    if(json.success === false){
                        // Game not found
                        if(json.code == 1){
                            $('#results').prepend(
                                '<tr>'
                                +'<td>'+json.input.game_id+'</td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td>'+json.message+'</td>'
                                +'</tr>');
                        }
                        // No match found
                        if(json.code == 2){
                            $('#results').prepend(
                                '<tr>'
                                +'<td>'+json.data.game.id+'</td>'
                                +'<td>'+json.data.game.name+'</td>'
                                +'<td>'+json.data.game.type+'</td>'
                                +'<td>'+json.message+'</td>'
                                +'</tr>');
                        }
                    }

                    if(json.success === true){
                        $('#results').prepend(
                            '<tr>'
                            +'<td>'+json.data.game.id+'</td>'
                            +'<td>'+json.data.game.name+'</td>'
                            +'<td>'+json.data.game.type+'</td>'
                            +'<td>Matched With GID: '+json.data.game.g2a_id+'</td>'
                            +'</tr>');
                    }



                    $('#counter').val( function(i, oldval) {
                        return --oldval;
                    });
                },
                error: function (request, error) {

                },

            });

        }
    </script>

@endsection
