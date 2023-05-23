@extends('components.tableLayout')
@section('th')
    <tr>
        <th class="sorting sort" data-column="id">#</th>
        <th>Type</th>
        @role('admin')
            <th>User request</th>
            <th>User approve</th>
        @endrole
        <th class="sorting sort sorting_desc" data-column="created_at">Created at</th>
        <th class="text-center">Info</th>
        <th class="sorting sort" data-column="status">Status</th>
        @hasanyrole('teacher|student')
            <th class="text-center">Cancel</th>
        @endhasanyrole
    </tr>
@endsection
@section('tableId', 'requestsTable')
<script>
    const model = 'request';
    const tableId = '#requestsTable';
    const url = `/requests/table`;
    const isAdmin = '{{ Auth::User()->hasRole('admin') }}';
    let queryData = {
        page: 1,
        orderBy: 'created_at',
        orderDirect: 'desc',
        search: null,
        role: [],
        status: isAdmin ? 1 : null,
        type: null
    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (request) => {
        let row = $(`<tr id="request-${ request.id    }">`);
        row.append(`<td>${ request.id }</td>`);
        row.append(`<td>${ request.type }</td>`);
        if (isAdmin) {
            row.append(`<td>${ request.userRequest }</td>`);
            row.append(`<td>${ request.userApprove }</td>`);
        }
        row.append(
            `<td>${  new Date(request.created_at).toLocaleString('en-us')  }</td>`
        );
        row.append(` <td class="text-info text-2xl text-center">
                        <a href='/requests/${request.id}'><i class="fa-sharp fa-solid fa-circle-info"></i></a>
                    </td>`);
        row.append(
            `<td class="${request.status === 'Pending' ?'text-primary':''} ${request.status === 'Approved' ?'text-success':''} ${request.status === 'Rejected' || request.status === 'Cancelled' ?'text-danger':''}"> ${request.status} </td>`
        );
        if (!isAdmin)
            row.append(request.status !== 'Pending' ? `<td></td>` :
                `<td class="text-danger text-center text-2xl cancel" data-id=${request.id}><i class="fa-solid fa-xmark"></i></td>`
            );

        return row;
    }
    const cancel = (id) => {
        toastr.info('Cancelling request...');
        $.ajax({
            method: "PATCH",
            url: `/requests/${id}/cancel`,
            success: function(resp) {
                if (resp.data) {
                    toastr.success(resp.message);
                    getTable(createRow);
                } else
                    toastr.error(resp.message);
            },
            error: function() {
                toastr.error('Error, please try again later!');
            }
        })
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
        $('#filter-type').change(function() {
            if ($(this).val())
                queryData.type = $(this).val();
            else
                queryData.type = null;
            getTable(createRow);
        });
        $(document).on('click', '.cancel', function() {
            const btn = $(this);
            const id = btn.data('id');
            toastr.clear();
            toastr.options.timeOut = 0;
            toastr.options.extendedTimeOut = 0;
            toastr.options.closeButton = true;
            toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to cancel request #${id}?</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-warning mr-3">No</button> 
                        <button class="btn btn-success ml-3" onclick='cancel(${id})'>Yes</button></div>
                    </div>`);
            toastr.options.timeOut = 3000;
            toastr.options.extendedTimeOut = 3000;
        })

    })
</script>
