@extends('main')
@section('title', " | Update Database")
@section('content')
    <h1>Update Database</h1>
    <p>
        There are {!! count($games) !!} appid's remaining. <br>
        Number of games added:
        <input id="counter" value="0">
    </p>
    <button class="btn" id="start">Start</button>
    <button class="btn" id="stop">Stop</button>

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
        console.log(games);

        $("#start").click(function () {
            interval = setInterval(function () {
                updateGame(games[counter]);
                counter++;
            }, 1);
        });

        $("#stop").click(function () {
            clearInterval(interval);
        });

        function updateGame(game){
            // Search
            $.ajax({
                url: 'http://www.steamgm.com/games/insert',
                type: 'GET',
                data: {
                    'appid': game.appid,
                    'name' : game.name

                },
                dataType : 'json',
                async: false,
                success: function (json, response) {
                    //console.log(json);
                    console.log(json);
                    console.log(json['status']);

                    if(json.status === "success"){
                        $('#results > tbody:last-child').append(
                            '<tr>'
                            +'<td>'+json.data.steam_id+'</td>'
                            +'<td>'+json.data.name+'</td>'
                            +'<td>'+json.data.type+'</td>'
                            +'<td>Added Successfuly</td>'
                            +'</tr>');
                    }
                    else{
                        $('#results > tbody:last-child').append(

                            '<tr>'
                            +'<td>'+json.data.appid+'</td>'
                            +'<td>'+json.data.name+'</td>'
                            +'<td></td>'
                            +'<td>'+json.error.message+'</td>'
                            +'</tr>');
                    }

                    $('#counter').val( function(i, oldval) {
                        return ++oldval;
                    });
                },
                error: function (request, error) {

                },
            });
        }

        function sleep(ms){
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > 1000){
                    break;
                }
            }
        }
    </script>
@endsection