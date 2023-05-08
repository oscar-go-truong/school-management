@extends('components.tableLayout')
@section('th')
    <tr>
        <th class="sorting sort" data-column="id">#</th>
        <th>Type</th>
        <th>User request</th>
        <th>User approve</th>
        <th class="sorting sort sorting_desc" data-column="created_at">Created at</th>
        <th class="text-center">Info</th>
        <th class="sorting sort" data-column="status">Status</th>
    </tr>
@endsection
@section('tableId', 'requestsTable')
<script>
    const model = 'request';
    const tableId = '#requestsTable';
    const url = `/requests/table`;
    const isAdmin = '{{ Auth::User()->isAdministrator() }}';
    let queryData = {
        page: 1,
        orderBy: 'created_at',
        orderDirect: 'desc',
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
        row.append(` <td class="text-info text-2xl text-center">
                        <a href='/requests/${request.id}'><i class="fa-sharp fa-solid fa-circle-info"></i></a>
                    </td>`);
        row.append(
            `<td class="${request.status === 1 ?'text-primary':''} ${request.status === 3 ?'text-success':''} ${request.status === 2 ?'text-danger':''}"> ${request.status === 1 ?'Pedding':''} ${request.status === 3 ?'Approved':''} ${request.status === 2 ?'Rejected':''} </td>`
        );
        return row;
    }
    $(document).ready(function() {
        $(document).ready(function() {
            $('#filter-status').change(function() {
                if ($(this).val())
                    queryData.status = $(this).val();
                else
                    queryData.status = null;
                getTable(createRow);
            });
        })
    })
</script>
