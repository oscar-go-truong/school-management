@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Type</th>
        <th>User request</th>
        <th>User approve</th>
        <th>Created at</th>
        <th>Status</th>
    </tr>
@endsection
@section('tableId', 'requestsTable')
<script>
    const model = 'request';
    const tableId = '#requestsTable';
    const url = `/requests/table`;
    const isStudent = '{{ Auth::User()->isStudent() }}';
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null,
    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (request) => {
        let row = $(`<tr id="request-${ request.id    }">`);
        row.append(`<td>${ request.id }</td>`);
        row.append(`<td>${ request.type }</td>`);
        row.append(`<td>${ request.user_request.fullname }</td>`);
        row.append(`<td>${ request.user_approve.fullname }</td>`);
        row.append(
            `<td>${  new Date(request.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="${ request.id }"
                    data-id="${ request.id }" ${ request.status === 1 ? 'checked' : '' }>
                    <label class="form-check-label" for="${ request.id }">
                    ${ request.status === 1 ? 'Open' : 'Close' }
                    </label>
                    </div>
                    </td>`);
        return row;
    }
</script>
