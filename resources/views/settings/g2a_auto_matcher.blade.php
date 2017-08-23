@extends('settings.index')

@section('settings-content')
    <h1>G2a Auto Matcher</h1>
    <p>
        Number of games:
        <input id="counter" value="{!! count($games) !!}">
    </p>
    <button class="btn" id="start">Start</button>

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
            interval = interval(function () {
                updateGame(games[counter].id);
                counter++;
            }, 0, games.length);
        });

        function updateGame(id){
            // Search
            $.ajax({
                url: '/settings/auto_match',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    game_id : id},
                dataType : 'json',
                success: function (json, response) {
                    if(json.success === false){
                        if(json.code == 0){
                            $('#results').prepend(
                                '<tr>'
                                +'<td>'+json.data.id+'</td>'
                                +'<td></td>'
                                +'<td></td>'
                                +'<td>'+json.message+'</td>'
                                +'</tr>');
                        }
                        if(json.code == 1){
                            $('#results').prepend(
                                '<tr>'
                                +'<td>'+json.data.id+'</td>'
                                +'<td>'+json.data.name+'</td>'
                                +'<td>'+json.data.type+'</td>'
                                +'<td>'+json.message+'</td>'
                                +'</tr>');
                        }
                    }

                    if(json.success === true){
                        $('#results').prepend(
                            '<tr>'
                            +'<td>'+json.data.id+'</td>'
                            +'<td>'+json.data.name+'</td>'
                            +'<td>'+json.data.type+'</td>'
                            +'<td>Matched With GID: '+json.data.g2a_id+'</td>'
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

    <script>
        function interval(func, wait, times){
            var interv = function(w, t){
                return function(){
                    if(typeof t === "undefined" || t-- > 0){
                        setTimeout(interv, w);
                        try{
                            func.call(null);
                        }
                        catch(e){
                            t = 0;
                            throw e.toString();
                        }
                    }
                };
            }(wait, times);

            setTimeout(interv, wait);
        };
    </script>



@endsection
