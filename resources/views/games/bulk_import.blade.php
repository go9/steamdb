{{--
<button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#bulk-import-modal">
    Bulk Importer
</button>
--}}
<script>
    var from = null;
    // Check if there is a from variable
    $(document).on('show.bs.modal','#bulk-import-modal', function(e) {
        from = $(e.relatedTarget).data("from");
    });
</script>

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
                    <textarea id="games" style="width:100%;height:300px;"></textarea><br>
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

    function parseUserInput() {
        // Get the users input
        var games = $("#games").val().split("\n");
        // Clear the textarea
        $("#games").val('');

        for (var i = 0; i < games.length; i++) {
            games[i] = games[i].toLowerCase().trim();

            // Check if in array
            if (in_array(games[i], inTable) || games[i] === "") {
                continue;
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
                    // Empty Results Textarea
                    $('#results').empty();

                    // Get results from server
                    var data = json.data;

                    // Append to table

                    // Create row
                    var tr = $("<tr>", {
                        id: "" + json.input.keywords
                    });
                    // Add user input to row
                    var td = $("<td>", {
                        html: json.input.keywords.toUpperCase()
                    });
                    tr.append(td);

                    // Check if any matches were found
                    if (data.length == 0) {
                        td = $("<td>", {
                            html: "No Results"
                        });
                        tr.append(td);

                        // Append empty td for save button
                        tr.append($("<td>", {
                            html: ""
                        }));
                    }
                    else {
                        // Create dropdown
                        var select = document.createElement("select");
                        select.className += "form-control";
                        select.id = "game-" + json.input.keywords.replace(/\s+/g, '');

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
                                    saveGame(select.id);
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
                    }

                    // Add row to table
                    $('#bulk-import-table').append(tr);
                },
                error: function (request, error) {
                    console.log("error");
                }
            });
        }
    }

    function saveGame(id) {
        var select = $("#" + id.replace(/\s+/g, ''));

        if (in_array(select.val(), saved)) {

        }
        else {
            saved.push(select.val());
            select.attr("disabled", true);
        }
    }

</script>
