@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Fullname</th>
        <th>Homeroom teacher</th>
        <th>Email</th>
        <th>Joined at</th>
        @role('admin')
            @if ($course->status === 1)
                <th class="text-center">Remove</th>
            @endif
        @endrole
    </tr>
@endsection
@section('tableId', 'course-teachersTable')
<script>
    const model = 'user-course';
    const tableId = '#course-teachersTable';
    const hoomeroomTeacherId = '{{ $course->owner_id }}';
    const isAdmin = '{{ Auth::user()->hasRole('admin') }}';
    const id = '{{ $courseId }}';
    const url = `/courses/${id}/teachers/table`
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null

    };;
    // end config
    //
    // Create row for table
    const createRow = (teacher) => {
        let row = $(`<tr id="user-course-${ teacher.id    }">`);
        row.append(`<td>${ teacher.id }</td>`);
        row.append(`<td>${ teacher.fullname }</td>`);
        row.append(
            `<td class="text-center">${teacher.user_id == hoomeroomTeacherId?'<span class="text-success"><i class="fa-solid fa-check"></i></span>':""}</td>`
        );
        row.append(`<td>${ teacher.email }</td>`);
        row.append(
            `<td>${ new Date(teacher.joined_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        if (isAdmin && '{{ $course->status }}' == 1) {
            row.append(`<td class="text-danger text-center"><i class="fa-solid fa-user-xmark delete" data-id="${teacher.id}" data-name="${teacher.fullname}"
                                ></i></td>`);
        }
        return row;
    }
</script>
