@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Subject</th>
        <th>Course</th>
        <th>Exam type</th>
        <th>Fullname</th>
        <th>Email</th>
        <th class="text-center">Score</th>
        <th>Created at</th>
        {{-- <th>Status</th> --}}
    </tr>
@endsection
@section('tableId', 'scoresTable')
<script>
    const model = 'score';
    const tableId = '#scoresTable';
    const examId = '{{ request()->query('examId') }}';
    const url = `/scores/table`;
    const isStudent = '{{ Auth::User()->isStudent() }}';
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null,
        examId: !isNaN(examId) && examId ? examId : null

    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (score) => {
        let row = $(`<tr id="score-${ score.id    }">`);
        row.append(`<td>${ score.id }</td>`);
        row.append(`<td>${ score.exam.course.subject.name }</td>`);
        row.append(`<td>${ score.exam.course.name }</td>`);
        row.append(`<td>${ score.exam.type }</td>`);
        row.append(`<td>${ score.user.fullname }</td>`);
        row.append(`<td>${ score.user.email }</td>`);
        row.append(`<td>${ score.total }</td>`);
        row.append(
            `<td>${  new Date(score.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        // row.append(`<td><div class="form-check form-switch">
        //             <input class="form-check-input" type="checkbox" id="${ score.id }"
        //             data-id="${ score.id }" ${ score.status === 1 ? 'checked' : '' }>
        //             <label class="form-check-label" for="${ score.id }">
        //             ${ score.status === 1 ? 'Open' : 'Close' }
        //             </label>
        //             </div>
        //             </td>`);
        if (isStudent)
            row.append(
                `<td class="text-primary text-center"><i class="fa-solid fa-up-right-from-square"></i> </td>`);
        return row;
    }
</script>
