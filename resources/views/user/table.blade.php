@extends('components.tableLayout')
@section('th')
    <tr>
        <th class="sort sorting sorting_asc" data-column="id">#</th>
        <th class="sort sorting" data-column="username">Username</th>
        <th class="sort sorting" data-column="fullname">Full name</th>
        <th class="sort sorting" data-column="email">Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>
@endsection
@section('tableId', 'usersTable')
<script>
    // config for table
    const model = 'user';
    const tableId = '#usersTable';
    const url = '/users/table';
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null

    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (user) => {
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
                                    data-id=${ user.id } data-name=${ user.email }></i></i></td>`);
        return row;
    }

    $(document).ready(function() {
        // filter by role
        $('#selectRole').change(function(event) {
            if ($(this).val().includes("")) {
                $('.role-option').attr('selected', false);
                $('.role-option').attr('disabled', true);
                queryData.role = [];
            } else {
                $('.role-option').attr('disabled', false);
                queryData.role = $(this).val();
            }
            getTable(createRow);
        });
        $('.role-check-input').change(function() {
            const checked = $(this).is(':checked');
            const val = $(this).val();
            if (!checked) {
                const index = queryData.role.indexOf(val);
                queryData.role.splice(index, 1);
            } else {
                $('#role-' + val).addClass('bg-gray-300');
                queryData.role.push(val);
            }
            getTable(createRow);
        })

        //filter status
        $('#filter-status').change(function() {
            const val = $(this).val();
            if (val)
                queryData.status = val;
            else
                queryData.status = null;
            queryData.page = 1;
            getTable(createRow);
        })
    })
</script>
