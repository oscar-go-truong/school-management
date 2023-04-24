@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Exam type</th>
        <th>My score</th>
        <th class="text-center">Scores</th>
        <th>Created at</th>
        <th>Status</th>
        <th class="text-center">Request review score</th>
        <th class="text-center">Delete</th>
    </tr>
@endsection
@section('tableId', 'course-examsTable')
<script>
    const model = 'course-exam';
    const tableId = '#course-examsTable';
    const courseId = '{{ request()->query('courseId') }}';
    const url = `/exams/table`;
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null,
        courseId: !isNaN(courseId) && courseId ? courseId : null

    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (exam) => {
        let row = $(`<tr id="exam-${ exam.id    }">`);
        row.append(`<td>${ exam.id }</td>`);
        row.append(`<td>${ exam.type }</td>`);
        row.append(`<td></td>`);
        row.append(` <td class="text-center text-secondary">${exam.score_count}</td>`);
        row.append(
            `<td>${  new Date(exam.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="${ exam.id }"
                    data-id="${ exam.id }" ${ exam.status === 1 ? 'checked' : '' }>
                    <label class="form-check-label" for="${ exam.id }">
                    ${ exam.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);
        row.append(`<td class="text-primary text-center"><i class="fa-solid fa-up-right-from-square"></i> </td>`);
        row.append(`<td class="text-danger text-center"><i class="fa-solid fa-user-xmark"
                                ></i></i></td>`);
        return row;
    }
</script>
