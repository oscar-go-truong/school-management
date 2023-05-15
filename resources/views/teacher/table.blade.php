@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Fullname</th>
        <th>Homeroom teacher</th>
        <th>Email</th>
        <th>Joined at</th>
        @role('admin')
            {{-- <th>Status</th> --}}
            <th class="text-center">Remove</th>
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

    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (teacher) => {
        let row = $(`<tr id="user-course-${ teacher.id    }">`);
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
            // row.append(`<td><div class="form-check form-switch">
            //         <input class="form-check-input status" type="checkbox" id="${ teacher.id }"
            //         data-id="${ teacher.id }" ${ teacher.status === 1 ? 'checked' : '' }>
            //         <label class="form-check-label" for="${ teacher.id }">
            //         ${ teacher.status === 1 ? 'active' : 'blocked' }
            //         </label>
            //         </div>
            //         </td>`);
            row.append(`<td class="text-danger text-center"><i class="fa-solid fa-user-xmark delete" data-id="${teacher.id}" data-name="${teacher.user.fullname}"
                                ></i></td>`);
        }
        return row;
    }
</script>
