@extends('main')
@section('title')

@section('content')
    <!-- New Purchase Modal-->
    <div class="modal fade"
         id="new-purchase-modal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bundle-modal">Add new Purchase</h5>
                </div>
                <div class="modal-body container">

                    <form class="form" id="submit-bundle-form" method="POST" action="/purchases">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{!! Auth::user()->id !!}">

                        <div class="form-group row">
                            <label for="submit-bundle-name" class="col-2 col-form-label">Name*</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="name" id="submit-bundle-name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="submit-bundle-price" class="col-2 col-form-label">Price Paid</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="price_paid" id="submit-bundle-price">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="submit-bundle-store" class="col-2 col-form-label">Store</label>
                            <div class="col-10">
                                <select class="form-control" name='store_id' data-live-search="true" required>
                                    @foreach(App\Store::all() as $store)
                                        <option value="{{$store->id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="submit-bundle-date" class="col-2 col-form-label">Date Purchased</label>
                            <div class="col-10">
                                <input type="date" class="form-control" name="date_purchased" id="submit-bundle-date"
                                       value="{!! \Carbon\Carbon::today()->toDateString() !!}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="submit-bundle-notes" class="col-2 col-form-label">Notes</label>
                            <div class="col-10">
                                <textarea class="form-control" name="notes" id="submit-bundle-notes"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button for="submit-bundle-form" type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Import Modal -->
    @include("games.bulk_import")

    <!-- Add game Modal -->
    @include("games.game_import")

    <script>
        function processImport(game_ids, purchase_id) {
            $.ajax({
                url: '/purchase/insert_game',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'game_ids': game_ids,
                    'purchase_id': purchase_id,
                    "availability": 0
                },
                dataType: 'json',
                success: function (json, response, three) {
                    buildTable();
                },
                error: function (request, error) {
                }
            });
        }
    </script>

    <!-- Buttons -->


    @section('actionbar-contents')
        <button class="dropdown-item" type="button"
                data-toggle="modal"
                data-target="#new-purchase-modal">
            Add New Purchase
        </button>

        @if(Auth::user()->checkRole("admin"))
        @endif

        <script>$("#action-bar").show()</script>
    @endsection




    <script>

        $("#action-form").submit(function (e) {
            bulkEdit(
                $("#action-form").serializeArray()
            );
            this.reset();
            return false;
        });

        function bulkEdit(input) {
            var data = {
                ids: [],
                "_token": "{!! csrf_token() !!}"
            };

            for (var i in input) {
                if (input[i].name === "action") {
                    data.action = input[i].value;
                }
                if (input[i].name === "move_to") {
                    data.move_to = parseInt(input[i].value);
                }
                if (input[i].name === "ids[]") {
                    data.ids.push(input[i].value);
                }
            }

            if (data.ids.length == 0) {
                return false;
            }

            if(data.action === "delete"){
                if(!confirm("This action can not be undone. Are you sure?")){
                    return false;
                }
            }

            $.ajax({
                url: "/purchase/action",
                type: "POST",
                data: data,
                success: function () {
                    buildTable();
                },
                error: function (one) {
                    console.log(one);
                }
            });


        }
    </script>



    <div style="width:100%;">
        <!-- Bulk Edit-->
        <form id="action-form">
            <select  style="" name="action" onchange="$('#action-form').submit();">
                <option hidden>Bulk</option>
                <option value="delete">Delete</option>
                <option value="move">Move</option>
                <option>Reset</option>
            </select>
        </form>

    <table class="table table-responsive table-sm table-hover" id="purchases-table"
           style="overflow-x:auto;overflow-y:hidden;padding-bottom:200px;">
        <thead id="purchase-table-thead">
        <tr>
            <th>
                <div style="width:10px;">

                </div>
            </th>
            <th>
                <div style="width:10px;"></div>
            </th>
            <th>
                <div id="table-name" style="min-width:250px;width:250px;">Name</div>
            </th>
            <th>
                <div style="width:150px;">Availability</div>
            </th>
            <th>
                <div style="width:60px;">Paid</div>
            </th>
            <th>
                <div style="width:60px;">Sold</div>
            </th>
            <th>
                <div style="width:50px;">Actions</div>
            </th>
        </tr>
        </thead>
    </table>
    </div>
    <script>
        resize();
        $(window).resize(function () {
            resize();
        });

        function resize() {
            console.log("Content container: " + $("#content-container").width());
            console.log("Purchase Table Thead: " + $("#purchase-table-thead").width());
            console.log("table name: " +  $("#table-name").width());

            var width =
                $("#content-container").width()
                - ($("#purchase-table-thead").width() - $("#table-name").width())
                - (15*7);

            $("#table-name").css("width", width);
        }
    </script>

    <script>
        // DB Functions

        var stores = {!! App\Store::pluck("name","id") !!};

        function updateAvailability(id, availablity) {
            $.ajax({
                url: '/purchase/update_availability',
                type: 'POST',
                data: {
                    "_token": "{{csrf_token()}}",
                    "game_purchase_id": id,
                    "availability": availablity
                },
                dataType: 'json',
                success: function (json, response, three) {
                },
                error: function (request, error) {
                    alert("There was an error updating the games availability. Please contact support or try again later.");
                }
            });
        }

        function getPurchases() {
            var data = null;
            $.ajax({
                url: "/purchase/get_purchases",
                type: "POST",
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}"
                },
                success: function (json) {
                    data = json;
                },
                error: function (request, error) {

                }
            });

            return data;
        }

        // Table Drawing Functions

        function buildTable() {
            // Empty table before building
            emptyTable();

            // Get purchases from database
            var purchases = getPurchases();
            if (purchases.success == true) {
                for (var i in purchases.data) {
                    addPurchase(purchases.data[i]);
                }
            }
        }

        function removePurchase(purchase){

        }


        function emptyTable() {
            counter = 0;
            $("#purchases-table tbody").empty();
        }

        function addPurchase(purchase) {
            // Iterate Counter
            counter++;

            // Create row
            var tr = $("<tr>", {
                bgcolor: "#d0ced0"
            });

            // Add Tools
            tr.append(
                $("<td>", {
                    html: $("<input>", {
                        type: "radio",
                        form: "action-form",
                        name: "move_to",
                        value: purchase.id
                    })
                })
            );

            // Add Counter
            tr.append(
                $("<td>", {
                    html: counter
                })
            );

            // Add name
            tr.append(
                $("<td>", {
                    html: "<strong>" + purchase.name + "</strong>"
                })
            );

            // Add availability, nothing here
            tr.append(
                $("<td>", {
                    html: ""
                })
            );

            tr.append(editableTextField(purchase.price_paid, purchase.id, "purchase", "paid"));
            tr.append(editableTextField(purchase.price_sold, purchase.id, "purchase", "sold"));

            // Add Actions
            var div = $("<div>", {
                style: "display: inline-flex;"
            });

            // Single add
            div.append(
                $("<button/>", {
                    html: (new Icon("plus")).wrap({"title": "Add Game"}),
                    class: "btn-empty",
                    "data-toggle": "modal",
                    "data-target": "#add-game-modal",
                    "data-from": purchase.id,
                })
            );

            // Bulk add
            div.append(
                $("<button/>", {
                    html: (new Icon("plus-circle")).wrap({"title": "Add Games"}),
                    class: "btn-empty",
                    "data-toggle": "modal",
                    "data-target": "#bulk-import-modal",
                    "data-from": purchase.id,
                })
            );

            // Delete
            div.append(
                $("<button/>", {
                        html: (new Icon("trash")).wrap({"title": "Delete", "color": "red"}),
                        class: "btn-empty",
                        click: function () {
                            $.ajax({
                                url: ('/purchases/' + purchase.id),
                                type: 'post',
                                data: {
                                    "_method": 'delete',
                                    "_token": "{{ csrf_token() }}",
                                    'purchase_id': purchase.id,
                                },
                                dataType: 'json',
                                success: function (json, response, three) {
                                    tbody.remove();
                                },
                                error: function (request, error) {
                                }
                            })
                        }
                    }
                ));
            tr.append(
                $("<td>", {
                    html: div
                })
            );

            // Wrap in tbody
            var tbody = $("<tbody/>");
            tbody.append(tr);

            // Add to table
            $('#purchases-table').append(tbody);

            addPurchaseItems(purchase, tbody)
        }

        function addPurchaseItems(purchase, tbody) {
            // Add purchase items
            var localCounter = 0;

            for (var i in purchase.games) {
                item = purchase.games[i];

                // Iterate Counter
                localCounter++;

                // Create row
                var tr = $("<tr>", {
                    data: {
                        "counter" : counter + "." + localCounter
                    },
                });


                // Add Tools
                tr.append(
                    $("<td>", {
                        html: $("<input>", {
                            type: "checkbox",
                            form: "action-form",
                            name: "ids[]",
                            value: item.pivot.id
                        })
                    })
                );

                // Add Counter

                tr.append(
                    $("<td>", {
                        html: counter + "." + localCounter
                    })
                );

                // Add name and link
                tr.append(
                    $("<td>", {})
                        .append(
                            $("<a>", {
                                text: item.name,
                                href: "/games/" + item.id
                            })
                        )
                );

                // Add availability
                tr.append(
                    $("<td>", {
                        html: drawPill(item)
                    })
                );

                tr.append(editableTextField(item.pivot.price_paid, item.pivot.id, "purchase_item", "paid"));
                tr.append(editableTextField(item.pivot.price_sold, item.pivot.id, "purchase_item", "sold"));

                // Add Actions
                tr.append(
                    $("<td>", {
                        html: ""
                    })
                );

                // Add to table
                tbody.append(tr);

            }
        }

        function editableTextField(n, id, model, type) {
            // Format money
            if (n == null) {
                n = 0;
            }
            n = stripMoney(n);

            // Add price paid
            var price =
                $("<div>", {
                    html: money(n),
                    click: function () {
                        price.toggle();
                        input.toggle();
                        button.toggle();
                    }
                });

            var input =
                $("<input>", {
                    value: money(n),
                    style: "display:none;width:75px;float:left;padding:2px;",
                    class: "form-control input-sm"
                });


            var button =
                $("<button>", {
                    text: "Update",
                    style: "display:none;float:left;width:100%;",
                    class: "btn btn-secondary btn-sm",
                    click: function () {
                        // Toggle the views
                        price.toggle();
                        input.toggle();
                        button.toggle();

                        var url = "";
                        var data = {
                            "_token": "{{ csrf_token() }}"
                        };

                        if (model === "purchase") {
                            url = "/purchase/update_purchase_price";
                            data["purchase_id"] = id;
                        }
                        else if (model === "purchase_item") {
                            url = "/purchase/update_purchase_item_price";
                            data["game_purchase_id"] = id;
                        }

                        if (type === "sold") {
                            data["price_sold"] = stripMoney(input.val());
                        }
                        else if (type === "paid") {
                            data["price_paid"] = stripMoney(input.val());
                        }

                        // Update the price
                        if(input.val() === "-"){
                            price.html("-");
                        }
                        else{
                            price.html(money(input.val()));

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: data,
                                dataType: 'json',
                                success: function (json, response, three) {
                                },
                                error: function (request, error) {
                                    console.log(request);
                                    alert("There was an error updating the games price. Please contact support or try again later.");
                                }
                            });
                        }



                    }
                });


            var td = $("<td>", {});

            td.append(price);
            td.append(input);
            td.append(button);

            return td;
        }

        function drawPill(item, pill = null, availability = null) {
            // If no pill is supplied, draw a new pill.
            if (pill == null) {
                pill =
                    $("<span>", {
                        style: "display: inline-flex;"
                    });
            }
            pill.removeClass();


            // If no availability is provided, use the one that came with the item (before any changes)
            if (availability == null) {
                availability = item.pivot.availability;
            }

            // Draw the pill
            // The text of the pill will toggle between availabilities
            var text = function (name) {
                return $("<button>", {
                    class: "btn-empty",
                    style: "color:white;",
                    text: name,
                    click: function () {
                        // Change availability
                        switch (availability) {
                            case -1 :
                                availability = 0;
                                break; // It's currently redeemed, make it available
                            case 0 :
                                availability = -1;
                                break; // It's currently available, make it redeemed
                            default:
                                availability = 0;
                                break; // It's currently sold, make it available
                        }

                        // Save in DB
                        updateAvailability(item.pivot.id, availability);

                        // Redraw pill
                        drawPill(item, pill, availability);
                    }
                });
            };

            switch (availability) {
                case -1:
                    pill.html(text("Redeemed"));
                    style = "info";
                    break;
                case 0:
                    pill.html(text("Available"));
                    style = "success";
                    break;
                default:
                    pill.html(text("Sold on " + stores[availability]));
                    style = "default";
                    break;
            }

            pill.addClass("badge");
            pill.addClass("badge-" + style);
            pill.addClass("btn-" + style);

            // Create dropdown to change availability
            var dropdown =
                $("<div>", {
                    class: "dropdown",
                    style: "float:right;"
                })
                    .append(
                        $("<button>", {
                            class: "btn dropdown-toggle badge-" + style,
                            "data-toggle": "dropdown",
                            style: "padding:0px;color:white;"
                        })
                    );
            pill.append(dropdown);

            var dropdownMenu =
                $("<div>", {
                    class: "dropdown-menu dropdown-menu-right",
                    style: "z-index:100;"
                });
            dropdown.append(dropdownMenu);

            var form =
                $("<form>", {
                    class: "form",
                });

            dropdownMenu.append(form);

            // Form won't serialize buttons, so create a dummy input
            var selection =
                $("<input>", {
                    type: "hidden",
                    name: "availability",
                    value: null,
                });
            form.append(selection);

            // Create buttons for all the options
            var button = function (name, value) {
                return $("<button>", {
                    class: "dropdown-item",
                    text: name,
                    click: function () {
                        selection.val(value);
                    }
                });
            };

            // Show default buttons
            form.append(button("Redeemed", -1));
            form.append(button("Available", 0));

            // Create buttons for all the stores
            for (var x in stores) {
                form.append(
                    button("Sold on " + stores[x], x)
                );
            }

            // Handle form submission
            form.on("submit", function (e) {
                // Don't let form submit
                e.preventDefault();

                availability = parseInt(form.serializeArray()[0].value); // Data from form
                // Update in database
                updateAvailability(item.pivot.id, availability);
                // Redraw Pill
                drawPill(item, pill, availability);
            });
            return pill;
        }

        // Draw Table
        buildTable();

    </script>

@endsection