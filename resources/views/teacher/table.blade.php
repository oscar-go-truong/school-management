@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Fullname</th>
        <th>Homeroom teacher</th>
        <th>Email</th>
        <th>Joined at</th>
        @if (Auth::user()->isAdministrator())
            <th>Status</th>
            <th class="text-center">Remove</th>
        @endif
    </tr>
@endsection
@section('tableId', 'course-teachersTable')
<script>
    const model = 'course-teacher';
    const tableId = '#course-teachersTable';
    const hoomeroomTeacherId = '{{ $course->owner_id }}';
    const isAdmin = '{{ Auth::user()->isAdministrator() }}';
    const id = '{{ $courseId }}';
    const url = `/courses/${id}/teachers/table`
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
    const createRow = (teacher) => {
        let row = $(`<tr id="teacher-${ teacher.id    }">`);
        row.append(`<td>${ teacher.id }</td>`);
        row.append(`<td>${ teacher.user.fullname }</td>`);
        row.append(
            `<td class="text-center">${teacher.user_id == hoomeroomTeacherId?'<span class="text-success"><i class="fa-solid fa-check"></i></span>':""}</td>`
        );
        row.append(`<td>${ teacher.user.email }</td>`);
        row.append(
            `<td>${ new Date(teacher.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        if (isAdmin) {
            row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="${ teacher.id }"
                    data-id="${ teacher.id }" ${ teacher.status === 1 ? 'checked' : '' }>
                    <label class="form-check-label" for="${ teacher.id }">
                    ${ teacher.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);
            row.append(`<td class="text-danger text-center"><i class="fa-solid fa-user-xmark"
                                ></i></i></td>`);
        }
        return row;
    }
</script>
