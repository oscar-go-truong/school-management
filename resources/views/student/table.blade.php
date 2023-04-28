@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Fullname</th>
        <th>Email</th>
        <th>Joined at</th>
        @if (Auth::user()->isAdministrator())
            <th>Status</th>
            <th class="text-center">Remove</th>
        @endif
    </tr>
@endsection
@section('tableId', 'course-studentsTable')
<script>
    const isAdmin = '{{ Auth::user()->isAdministrator() }}';
    const model = 'course-student';
    const tableId = '#course-studentsTable';
    const id = '{{ $courseId }}';
    const url = `/courses/${id}/students/table`
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
    const createRow = (student) => {
        let row = $(`<tr id="student-${ student.id    }">`);
        row.append(`<td>${ student.id }</td>`);
        row.append(`<td>${ student.user.fullname }</td>`);
        row.append(`<td>${ student.user.email }</td>`);
        row.append(
            `<td>${  new Date(student.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        if (isAdmin) {
            row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="${ student.id }"
                    data-id="${ student.id }" ${ student.status === 1 ? 'checked' : '' }>
                    <label class="form-check-label" for="${ student.id }">
                    ${ student.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);
            row.append(`<td class="text-danger text-center"><i class="fa-solid fa-user-xmark"
                                ></i></i></td>`);
        }
        return row;
    }
</script>
