<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between mb-3">
                    <h1>Head List</h1>
                    <button type="button" class="btn btn-sm btn-primary addHeadBtn">Add Head</button>
                </div>
                <ul>
                    @foreach ($heads as $head)
                        <li>
                            {{ $head->name }}
                            <button type="button" data-id="{{ $head->id }}" class="btn btn-info edit">Edit</button>
                            <a href="{{ route('delete', $head->id) }}" class="btn btn-danger">Delete</a>
                            @if ($head->children->count())
                                <ul>
                                    @foreach ($head->children as $child)
                                        @include('head', ['head' => $child])
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax" action="{{ route('store') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Head</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Parent:</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Parent</option>
                                @foreach ($parents as $head)
                                    <option value="{{ $head->id }}">{{ $head->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax" action="{{ route('store') }}" method="POST">
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Head</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Parent:</label>
                            <select name="parent_id" class="form-control">
                                <option value="">Parent</option>
                                @foreach ($parents as $head)
                                    <option value="{{ $head->id }}">{{ $head->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="editRoute" value="{{ route('info') }}">

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('.addHeadBtn').on('click', function() {
            var selector = $('#addModal');
            selector.modal('show');
        });

        $(document).on('click', '.edit', function() {
            var ajaxData = {
                type: 'get',
                url: $('#editRoute').val(),
                dataType: 'json',
                success: getDataEditRes,
                error: getDataEditRes
            }

            ajaxData.data = {
                'id': $(this).data('id')
            };
            $.ajax(ajaxData);
        });

        function getDataEditRes(response) {
            var selector = $('#editModal');
            selector.find('.is-invalid').removeClass('is-invalid');
            selector.find('.error-message').remove();
            selector.find('input[name=id]').val(response.id);
            selector.find('input[name=name]').val(response.name);
            selector.find('select[name=parent_id]').val(response.parent_id);
            selector.modal('show');
        }

        // form submit by ajax
        $(document).on('submit', "form.ajax", function(event) {
            event.preventDefault();
            var enctype = $(this).prop("enctype");
            if (!enctype) {
                enctype = "application/x-www-form-urlencoded";
            }
            var ajaxData = {
                type: $(this).prop('method'),
                url: $(this).prop('action'),
                dataType: 'json',
                success: successHandler,
                error: successHandler
            }
            ajaxData.data = new FormData($(this)[0]);
            ajaxData.encType = 'enctype';
            ajaxData.contentType = false;
            ajaxData.processData = false;
            $.ajax(ajaxData);
        });

        function successHandler(response) {
            var output = '';
            var type = 'error';
            $('.error-message').remove();
            $('.is-invalid').removeClass('is-invalid');
            if (response['status'] == true) {
                output = output + response['message'];
                type = 'success';
                // toastr.success(response.message)
                console.log('success');
                location.reload();
            } else {
                var output = '';
                var type = 'error';
                $('.error-message').remove();
                $('.is-invalid').removeClass('is-invalid');
                if (response['status'] == false) {
                    output = output + response['message'];
                } else if (response['status'] === 422) {
                    var errors = response['responseJSON']['errors'];
                    $.each(errors, function(index, items) {
                        var itemSelect = $("[name='" + index + "']");
                        itemSelect.addClass('is-invalid');
                        itemSelect.closest('div').append('<span class="text-danger p-2 error-message">' + items[
                            0] + '</span>')
                    });
                } else if (typeof data['responseJSON']['error'] !== 'undefined') {
                    output = data['responseJSON']['error'];
                } else {
                    output = data['responseJSON']['message'];
                }
            }
        }
    </script>
</body>

</html>
