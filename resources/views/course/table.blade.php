@extends('components.tableLayout')
@section('th')
    <tr>
        <th class="sort sorting" data-column="id">#</th>
        <th class="sort sorting" data-column="name">Name</th>
        <th class="sort sorting" data-column="subject_id">Subject</th>
        <th>Schedules</th>
        <th class="sort sorting sorting_desc" data-column="created_at">Year</th>
        <th>Teachers</th>
        <th>Students</th>
        <th>Exams</th>
        <th class="text-center">Detail</th>
        @role('admin')
            <th>Status</th>
            <th class="text-center">Update</th>
        @endrole
    </tr>
@endsection
@section('tableId', 'coursesTable')
<script>
    const model = 'course';
    const isAdmin = '{{ Auth::User()->hasRole('admin') }}';
    const subjectId = "{{ Request::get('subjectId') }}";
    const tableId = '#coursesTable';
    const url = '/courses/table';
    let queryData = {
        page: 1,
        orderBy: 'created_at',
        orderDirect: 'desc',
        search: null,
        role: [],
        status: null,
        year: new Date().getFullYear(),
        subjectId: subjectId
    };
    // end config
    //
    // Create row for table
    const createRow = (course) => {
        let row = $(`<tr id="course-${ course.id    }">`);
        row.append(`<td>${ course.id }</td>`);
        row.append(`<td>${ course.name }</td>`);
        row.append(`<td>${ course.subject}</td>`);
        let schedules = $(`<td>`);
        for (let i = 0; i < course.schedules.length; i++) {
            schedules.append(
                `<div class="mt-2">${course.schedules[i].start + '-' + course.schedules[i].end +', '+ course.schedules[i].weekday + " - "+course.schedules[i].room }</div>`
            );
        }
        row.append(schedules);
        row.append(`<td>${course.year}</td>`);
        row.append(
            `<td class="dark-link text-center"><a href="/courses/${course.id}/teachers">${course.teachersCount}</a></td>`
        );
        row.append(
            `<td class="dark-link text-center"><a href="/courses/${course.id}/students">${course.studentsCount}</a></td>`
        );
        row.append(
            `<td class="dark-link text-center"><a href="/exams?courseId=${course.id}">${course.examsCount}</a></td>`
        );
        row.append(` <td class="text-info text-2xl text-center">
                        <a href='/courses/${ course.id }'><i class="fa-sharp fa-solid fa-circle-info"></i></a>
                    </td>`);
        if (isAdmin) {
            row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input status" type="checkbox" id="${ course.id }"
                    data-id="${ course.id }" ${ course.status === 1 ? 'checked' : '' } ${ isAdmin ? '' : 'disabled' }>
                    <label class="form-check-label" for="${ course.id }">
                    ${ course.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);
            row.append(`<td class="text-primary"><a href="/courses/${ course.id }/edit"><i
                                        class="fa-solid fa-pen-to-square"></i></a></td>`);
        }
        return row;
    }
    $(document).ready(function() {
        $('#filter-year').change(function() {
            if ($(this).val())
                queryData.year = $(this).val();
            else
                queryData.year = null;
            getTable(createRow);
        });
    })
</script>
