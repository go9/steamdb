<script>
    var from = null;
    // Check if there is a from variable
    $(document).on('show.bs.modal','#add-game-modal', function(e) {
        from = $(e.relatedTarget).data("from");
    });
</script>

<div class="modal fade" id="add-game-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Game Importer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input class="form-control" id="keyword" placeholder="Search (ex. Grand Theft Auto V)"
                       autocomplete="off">
                <select id="results" class="form-control" name="game_id">
                    <option>Results</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="processImport($('#results').val(), from);">
                    Continue
                </button>
            </div>

        </div>
    </div>
</div>


<script>
    $('#keyword').keyup(function () {
        if ($(this).val().length > 3) {
            search($(this).val());
        }
    });

    function search(keyword) {
        // Search
        $.ajax({
            url: '/search',
            type: 'GET',
            data: {
                'keywords': keyword
            },
            dataType: 'json',
            success: function (json, response, three) {
                $('#results').empty();
                var data = json.data;
                for (var x = 0; x < data.length; x++) {
                    $('#results').append(
                        $('<option>', {
                                value: data[x].id,
                                html: data[x].name
                            }
                        ));
                }
            },
            error: function (request, error) {

            }
        });
    }
</script>