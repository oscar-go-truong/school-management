@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div> User managerment</div>

                        <div>
                            <input type="text" class="form-control w-60 h-8 inline translate-y-[-5px] " placeholder="email"
                                id='email'>
                            <a href='{{ route('users.create') }}'><i class="fa-solid fa-user-plus inline"></i></a>
                        </div>
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content"">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="sorting sorting_asc" data-column="id">#</th>
                                <th class="sorting" data-column="username">Username</th>
                                <th class="sorting" data-column="fullname">Full name</th>
                                <th class="sorting" data-column="email">Email</th>
                                <th class="sorting" data-column="role">Role</th>
                                <th class="sorting" data-column="status">Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="usersTable">
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
                <div id="userPagination" class="pt-2">
                    {{ $users->links('components.pagination', ['options' => $itemPerPageOptions]) }}
                </div>
            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <script>
        // Contants
        const PAGINATION_LIMIT = 7;
        // query's data
        let queryData = {
            page: 1,
            orderBy: {
                id: "asc"
            },
            searchLike: {
                email: ""
            }
        };

        let last_page;

        // set up header for ajax
        let _token = '{{ csrf_token() }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': _token
            }
        });

        // Create user rows 
        const createUserRow = (user) => {
            let row = $(`<tr id="user-${ user.id    }">`);
            row.append(`<td>${ user.id }</td>`);
            row.append(`<td>${ user.username }</td>`);
            row.append(`<td>${ user.fullname }</td>`);
            row.append(`<td>${ user.email }</td>`);
            row.append(`<td>${ user.role}</td>`);
            row.append(`<td><div class="form-check form-switch">
                            <input class="form-check-input status" type="checkbox" id="${ user.id }"
                            data-id="${ user.id }" ${ user.status === 1 ? 'checked' : '' }>
                            <label class="form-check-label" for="${ user.id }">
                            ${ user.status === 1 ? 'active' : 'blocked' }
                            </label>
                            </div>
                            </td>`);
            row.append(`<td class="text-primary"><a href="/users/${ user.id }/edit"><i
                                                class="fa-solid fa-pen-to-square"></i></a></td>`);
            row.append(`<td class="text-danger"><i class="fa-sharp fa-solid fa-user-minus delete"
                                            data-id=${ user.id }></i></i></td>`);
            return row;
        }

        // Update data 
        const getTable = () => {
            // hidden data on table
            $('#usersTable').hide();

            // active current page 
            $('.pageIndex').removeClass('bg-gray-300');
            $('#page-' + queryData.page).removeClass('bg-white');
            $('#page-' + queryData.page).addClass('bg-gray-300');

            // get data 
            $.ajax({
                type: "GET",
                url: "/users/table",
                data: queryData,
                dataType: "json",
                success: function(resp) {
                    // clear old data
                    $('#usersTable').show();
                    $('#usersTable').html("");
                    const users = resp.data;

                    // loading new data
                    for (let i = 0; i < users.length; i++) {
                        const user = createUserRow(users[i]);
                        $('#usersTable').append(user);
                    }
                    last_page = resp.last_page;

                    // update pagination
                    $('#from').text(resp.from);
                    $('#to').text(resp.to);
                    $('#total').text(resp.total);
                    $('#pagination').html("");
                    let pages = [queryData.page];
                    let k = 1;
                    while (pages.length < PAGINATION_LIMIT && (queryData.page - k > 0 || queryData.page +
                            k <=
                            last_page)) {
                        if (queryData.page - k > 0) pages.unshift(queryData.page - k);
                        if (queryData.page + k <= last_page) pages.push(queryData.page + k);
                        k++;
                    }
                    if (pages[0] > 1)
                        $('#pagination').append(`<span 
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700  border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                          >
                                            ...
                                        </span>`);
                    for (let i = 0; i < pages.length; i++) {
                        $('#pagination').append(`<span id="page-${pages[i]}"
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 ${pages[i] === queryData.page ? "bg-gray-300":""} border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                            data-index="${pages[i]}">
                                            ${pages[i]}
                                        </span>`)
                    }
                    if (pages[pages.length - 1] < last_page)
                        $('#pagination').append(`<span 
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700  border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                          >
                                            ...
                                        </span>`)
                },
                error: function() {
                    $('#usersTable').show();
                    toastr.error('Error, Please try again later!');
                }
            });
        }
        $(document).ready(function() {

            // update user's status
            $('#itemPerPage').change(function() {
                queryData['limit'] = $(this).val();
                queryData['page'] = 1;
                getTable();
            });
            // 
            $(document).on('click', '.pageIndex', function() {
                queryData.page = $(this).data('index');
                $('.pageIndex').removeClass('bg-gray-300');
                $(this).removeClass('bg-white');
                $(this).addClass('bg-gray-300')
                getTable();
            });
            // Go to next page
            $('#next').click(function() {
                if (!last_page || queryData.page < last_page) {
                    queryData.page++;
                    getTable();
                }
            })
            // Go to prev page
            $('#prev').click(function() {
                if (queryData.page != 1) {
                    queryData.page--;
                    getTable();
                }
            })
            // Sorting column
            $('th').click(function() {
                $('th').removeClass('sorting_desc');
                $('th').removeClass('sorting_asc');
                const column = $(this).data('column');
                if (queryData.orderBy[column] === 'asc') {
                    queryData.orderBy[column] = "desc";
                    $(this).addClass('sorting_desc');
                } else {
                    queryData.orderBy = {};
                    queryData.orderBy[column] = "asc";
                    $(this).addClass('sorting_asc');
                }
                getTable();
            })
            // search by email 
            $('#email').change(function() {
                queryData.searchLike.email = $(this).val();
                getTable();
            })
            // Change user status
            $('.status').change(function() {
                toastr.info('Updating status!');
                let id = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;


                $(this).parent().children('label').text(status ? "active" : "blocked");
                $.ajax({
                    type: "PATCH",
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
                        toastr.error('Error, Please try again later!');
                    }
                });
            });
            // delete user
            $('.delete').click(function() {
                toastr.info('Deleting user!');
                let id = $(this).data('id');

                $.ajax({
                    type: "DELETE",
                    url: "/users/" + id,
                    dataType: "json",
                    success: function() {
                        toastr.success('Delete user successful!');
                        $('#user-' + id).remove();
                    },
                    error: function() {
                        toastr.error('Error, Please try again later!');
                    }
                });
            });
        });
    </script>
@endsection
