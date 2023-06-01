@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Fullname</th>
        <th>Email</th>
        <th>Joined at</th>
        @role('admin')
            @if ($course->status === 1)
                <th class="text-center">Remove</th>
            @endif
        @endrole
    </tr>
@endsection
@section('tableId', 'course-studentsTable')
<script>
    const isAdmin = '{{ Auth::user()->hasRole('admin') }}';
    const model = 'user-course';
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
        let row = $(`<tr id="user-course-${ student.id    }">`);
        row.append(`<td>${ student.id }</td>`);
        row.append(`<td>${ student.fullname }</td>`);
        row.append(`<td>${ student.email }</td>`);
        row.append(
            `<td>${  new Date(student.joined_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        if (isAdmin && '{{ $course->status }}' == 1) {
            row.append(`<td class="text-danger text-center"><i class="fa-solid fa-user-xmark delete" data-id="${student.id}" data-name="${student.fullname}"
                                ></i></td>`);
        }
        return row;
    }
</script>
