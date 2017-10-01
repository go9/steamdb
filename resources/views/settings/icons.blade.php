@extends('settings.index')

@section('settings-content')

@section('actionbar-contents')
    @parent
    <div class="dropdown-divider"></div>
    <h6 class="dropdown-header">Icon Actions</h6>
    <button class="dropdown-item" type="button" data-toggle="modal" data-target="#new-icon-modal">
        New Icon
    </button>
    <script>$("#action-bar").show()</script>
@endsection


<div class="container" style="padding:0px;">
    <h1>Icon Manager</h1>
    <div class="row">
        <div class="col-11">
            There are {!! count($icons) !!} icons in the database.
        </div>
        <div class="col-1">
            <a href="http://fontawesome.io/icons/" target="_none">{!! App\Icon::find("font-awesome")->wrap() !!}</a>
        </div>
    </div>

    <table id="table" class="table table-responsive table-sm table-bordered" style="width:auto;margin:0 auto;overflow:scroll;margin-top:15px;">
        <thead>
        <tr>
            <th style="width:6000px;">ID</th>
            <th style="width:6000px;">Name</th>
            <th>Color</th>
            <th>Size</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        @foreach($icons as $icon)
            <tr>
                <td>{!! $icon->id !!}</td>
                <td>{!! $icon->name !!}</td>
                <td>{!! $icon->default_color !!}</td>
                <td>{!! $icon->default_size !!}</td>
                <td>{!! $icon->wrap(["size" => ""]) !!}</td>
                <td style="text-align:center;">
                    <button class="btn-empty"
                            style="text-align:center;font-size: 1em;padding:0px;"
                            type="button"
                            data-toggle="modal"
                            data-target="#edit-icon-modal"
                            data-id="{!! $icon->id !!}"
                            data-name="{!! $icon->name !!}"
                            data-color="{!! $icon->default_color !!}"
                            data-size="{!! $icon->default_size !!}">
                        {!! App\Icon::find("pencil-square-o")->wrap() !!}
                    </button>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<!-- New Icon Modal -->
<div class="modal fade" id="new-icon-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Icon Creator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="new-icon-form" method="POST" action="{!! action("IconController@store") !!}">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div style="background-color:whitesmoke;padding:15px;margin-bottom:15px;">
                        <h6>Preview</h6>
                        <div style="text-align: center; padding:15px;">
                            <i id="new-preview" aria-hidden="true"></i>
                        </div>
                    </div>
                    <input class="form-control" name="id" oninput="refreshPreview(0);">
                    <input class="form-control" name="name" oninput="refreshPreview(0);">
                    <input class="form-control" name="default_color" oninput="refreshPreview(0);">

                    <select name="default_size" class="form-control" oninput="refreshPreview(0);">
                        <option value="default">Default</option>
                        <option value="fa-lg">fa-lg(133%)</option>
                        <option value="fa-2x">fa-2x(200%)</option>
                        <option value="fa-3x">fa-3x(300%)</option>
                        <option value="fa-4x">fa-4x(400%)</option>
                        <option value="fa-5x">fa-5x(500%)</option>
                    </select>
                </div>

                <div class="modal-footer">

                    <button form="new-icon-form" type="submit" class="btn btn-secondary">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Icon Modal -->
<div class="modal fade" id="edit-icon-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Icon Editor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="icon-form" method="POST" action="{!! action("IconController@update") !!}">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div style="background-color:whitesmoke;padding:15px;margin-bottom:15px;">
                        <h6>Preview</h6>
                        <div style="text-align: center; padding:15px;">
                            <i id="edit-preview" aria-hidden="true"></i>
                        </div>
                    </div>
                    <input type="hidden" id="form-old-id" name="old_id">
                    <input class="form-control" id="form-id" name="id" oninput="refreshPreview(1);">
                    <input class="form-control" id="form-name" name="name" oninput="refreshPreview(1);">
                    <input class="form-control" id="form-color" name="default_color" oninput="refreshPreview(1);">

                    <select id="form-size" name="default_size" class="form-control" oninput="refreshPreview(1);">
                        <option value="default">Default</option>
                        <option value="fa-lg">fa-lg(133%)</option>
                        <option value="fa-2x">fa-2x(200%)</option>
                        <option value="fa-3x">fa-3x(300%)</option>
                        <option value="fa-4x">fa-4x(400%)</option>
                        <option value="fa-5x">fa-5x(500%)</option>
                    </select>
                </div>

                <div class="modal-footer">

                    <button form="icon-form" type="submit" class="btn btn-secondary">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var data = [];

    $(document).on('show.bs.modal', '#edit-icon-modal', function (e) {
        data.id = $(e.relatedTarget).data("id");
        data.name = $(e.relatedTarget).data("name");
        data.color = $(e.relatedTarget).data("color");
        data.size = $(e.relatedTarget).data("size");
        resetForm();
        refreshPreview();
    });

    function resetForm() {
        $("#form-old-id").val(data.id);
        $("#form-id").val(data.id);
        $("#form-name").val(data.name);
        $("#form-color").val(data.color);

        if (data.size) {
            $("#form-size").val(data.size).change();
        }
        else {
            $("#form-size").val("default").change();
        }

    }
    function refreshPreview(preview) {
        if (preview === 0) {
            $("#new-preview").removeClass();
            $("#new-preview").addClass("fa");
            $("#new-preview").addClass("fa-" + $("#form-id").val());
            $("#new-preview").addClass($("#form-size").val());
            $("#new-preview").css("color", $("#form-color").val())
        }
        else {
            $("#edit-preview").removeClass();
            $("#edit-preview").addClass("fa");
            $("#edit-preview").addClass("fa-" + $("#form-id").val());
            $("#edit-preview").addClass($("#form-size").val());
            $("#edit-preview").css("color", $("#form-color").val())
        }
    }
</script>
@endsection



