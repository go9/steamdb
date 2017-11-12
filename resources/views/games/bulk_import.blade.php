<style>
    #games{
        width:100%;height:100px;
        transition: height .5s;
    }
    #games:focus{
        height:300px;
        transition: height .5s;
    }
</style>
<div class="modal fade" id="bulk-import-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bulk Importer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Quickly search for multiple games
                    <textarea id="games" style=""></textarea><br>
                </p>
                <button class="btn btn-primary" style="width:100%;" onclick="parseUserInput();">Search</button>
                <br>
            </div>


            <div>
                <table id="bulk-import-table" class="table table-striped">
                    <thead>
                    <tr>
                        <th>User Input</th>
                        <th>Results</th>
                        <th>Save</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- results will go here -->
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  onclick="resetModal();">
                    Reset
                </button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="processImport(saved, from);">
                    Continue
                </button>
            </div>

        </div>
    </div>
</div>


<script>
    var inTable = [];
    var saved = [];
    var from = null;
    var counter = 0;

    function getCounter(){
        counter++;
        return counter;

    }

    // Check if there is a from variable
    $(document).on('show.bs.modal','#bulk-import-modal', function(e) {
        from = $(e.relatedTarget).data("from");
        resetModal();
    });

    function resetModal(){
        // Empty table
        inTable = [];
        $("#bulk-import-table tbody").empty();

        // Empty text area
        $("#games").val('');
    }

    function parseUserInput() {
        // Get the users input
        var games = $("#games").val().split("\n");
        // Clear the textarea
        $("#games").val('');

        for (var i = 0; i < games.length; i++) {
            games[i] = games[i].toLowerCase().trim();

            // Check if in array
            if (in_array(games[i], inTable) || games[i] === "") {
                //continue;
            }

            // Add to array
            inTable.push(games[i]);

            $.ajax({
                url: '/search',
                type: 'GET',
                data: {
                    'keywords': games[i]
                },
                dataType: 'json',
                success: function (json, response, three) {
                    var counter = getCounter();

                    // Empty Results Textarea
                    $('#results').empty();

                    // Get results from server
                    var data = json.data;

                    // Check if any matches were found
                    if (data.length == 0) {
                        // Add users text back into text area
                        var text = $("#games");
                        text.val(text.val() + json.input.keywords + "\n");
                    }
                    else {
                        // Create row
                        var tr = $("<tr>", {
                            id: "tr-" + counter
                        });
                        // Add user input to row
                        var td = $("<td>", {
                        }).append(
                            $("<span>", {
                                id: "td-" + counter,
                                html: json.input.keywords.toUpperCase()
                            }));

                        tr.append(td);

                        // Create dropdown
                        var select = document.createElement("select");
                        select.className += "form-control";
                        select.id = "game-" + counter;

                        // Add all the results to the dropdown
                        for (var x = 0; x < data.length; x++) {
                            var opt = document.createElement("option");
                            opt.value = data[x].id;
                            opt.text = data[x].name;
                            select.options.add(opt);
                        }

                        // Add options to row
                        td = $("<td>", {
                            html: select
                        });
                        tr.append(td);

                        // Create save button
                        var button = $("<button/>", {
                                click: function () {
                                    button.toggle();
                                    saveGame(counter);
                                },
                                class: "btn btn-secondary btn-table",
                                text: "Add",
                            }
                        );

                        // Add save button to row
                        td = $("<td>", {
                            class: "align-middle",
                            html: button
                        });
                        tr.append(td);

                        // Create remove button
                        var removeButton = $("<button/>", {
                                click: function () {
                                    removeGame(counter);
                                },
                                class: "btn btn-secondary btn-table",
                                text: "Remove",
                            }
                        );

                        // Add remove button to row
                        td = $("<td>", {
                            class: "align-middle",
                            html: removeButton
                        });
                        tr.append(td);

                        // Add row to table
                        $('#bulk-import-table').append(tr);
                    }
                },
                error: function (request, error) {
                    console.log("error");
                }
            });
        }
    }

    function saveGame(id) {
        var select = $("#game-" + id);
        saved.push(select.val());
        select.attr("disabled", true);
    }

    function removeGame(id) {


        var select = $("#game-" + id);

        // Add users text back into text area
        var text = $("#games");
        text.val(text.val() + $("#td-" + id).text() + "\n");

        // Delete row
        $("#tr-" + id).remove();

        // Remove game from saved array
        saved.splice(saved.indexOf(select.val()),1);


    }



</script>
