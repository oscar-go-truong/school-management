@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div> User managerment</div>
                        <div> <a href='{{ route('users.create') }}'><i class="fa-solid fa-user-plus"></i></a></div>
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
                                <th>Role</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr id="user-{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($role as $key => $value)
                                            @if ($value === $user->role)
                                                {{ $key }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="{{ $user->id }}"
                                                data-id="{{ $user->id }}" {{ $user->status === 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $user->id }}">
                                                {{ $user->status === 1 ? 'active' : 'blocked' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-primary"><a href="/users/{{ $user->id }}/edit"><i
                                                class="fa-solid fa-pen-to-square"></i></a></td>
                                    <td class="text-danger"><i class="fa-sharp fa-solid fa-user-minus delete"
                                            data-id={{ $user->id }}></i></i></td>
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
            // update user's status
            $('.status').change(function() {
                toastr.info('Updating status!');
                let id = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;
                let _token = '{{ csrf_token() }}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': _token
                    }
                });
                $(this).parent().children('label').text(status ? "active" : "blocked");
                $.ajax({
                    type: "PUT",
                    url: "/users/status/" + id,
                    data: {
                        status: status,
                        _token: _token
                    },
                    dataType: "json",
                    success: function() {
                        toastr.success('Update status successful!');
                    },
                    error: function() {
                        toastr.success('Error, Please try again later!');
                    }
                });
            });
            // delete user
            $('.delete').click(function() {
                toastr.info('Deleting user!');
                let id = $(this).data('id');
                let _token = '{{ csrf_token() }}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': _token
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: "/users/" + id,
                    dataType: "json",
                    success: function() {
                        toastr.success('Delete user successful!');
                        $('#user-' + id).remove();
                    },
                    error: function() {
                        toastr.success('Error, Please try again later!');
                    }
                });
            });
        });
    </script>
@endsection
