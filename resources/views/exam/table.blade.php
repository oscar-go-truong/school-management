@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Subject</th>
        <th>Course</th>
        <th>Exam type</th>
        @if (Auth::User()->isStudent())
            <th>My score</th>
        @endif
        <th class="text-center">Scores</th>
        <th>Created at</th>
        @if (Auth::user()->isAdministrator())
            <th>Status</th>
        @endif
        @if (Auth::User()->isStudent())
            <th class="text-center">Request review score</th>
        @endif
    </tr>
@endsection
@section('tableId', 'course-examsTable')
<script>
    const model = 'course-exam';
    const tableId = '#course-examsTable';
    const courseId = '{{ request()->query('courseId') }}';
    const url = `/exams/table`;
    const isStudent = '{{ Auth::User()->isStudent() }}';
    const isAdmin = '{{ Auth::User()->isAdministrator() }}';
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
        row.append(`<td>${ exam.course.subject.name }</td>`);
        row.append(`<td>${ exam.course.name }</td>`);
        row.append(`<td>${ exam.type }</td>`);
        if (isStudent)
            row.append(`<td>${exam.score[0].total}</td>`);
        row.append(
            ` <td class="text-center text-secondary"><a href="/scores?examId=${exam.id}">${exam.score_count}</a></td>`
        );
        row.append(
            `<td>${  new Date(exam.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        if (isAdmin)
            row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="${ exam.id }"
                    data-id="${ exam.id }" ${ exam.status === 1 ? 'checked' : '' }>
                    <label class="form-check-label" for="${ exam.id }">
                    ${ exam.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);
        if (isStudent)
            row.append(
                `<td class="text-primary text-center"><i class="fa-solid fa-up-right-from-square"></i> </td>`);
        return row;
    }
</script>
