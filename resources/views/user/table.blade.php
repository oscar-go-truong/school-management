@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold">
                        User managerment
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content"">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Full name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="{{ $user->id }}"
                                                data-id="{{ $user->id }}" {{ $user->status === 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $user->id }}">
                                                {{ $user->status === 1 ? 'active' : 'block' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-primary"><i class="fa-solid fa-pen-to-square"></i></td>
                                    <td class="text-danger"><i class="fa-sharp fa-solid fa-user-minus"></i></i></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->links() }}
            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <script>
        $(document).ready(function() {
            $('.status').change(function() {
                let id = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;
                let _token = '{{ csrf_token() }}';

                $.ajax({
                    header: {
                        'X-CSRF-TOKEN': _token
                    },
                    type: "PUT",
                    url: "/users/status/" + id,
                    data: {
                        status: status,
                        _token: _token
                    },
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function() {
                        alert(1);
                    },
                    error: function() {
                        alert(2);
                    }
                });
            });
        });
    </script>
@endsection
