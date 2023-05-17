@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Fullname</th>
        <th>Email</th>
        <th class="text-center">Score</th>
        <th hidden id="demo-remove-user"></th>
    </tr>
@endsection
@section('tableId', 'scoresTable')
<script>
    const model = 'score';
    const tableId = '#scoresTable';
    const url = '/exams/' + '{{ $exam->id }}' + '/scores/table';
    const isStudent = '{{ Auth::User()->hasRole('student') }}';
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null,
    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (score) => {
        let row = $(`<tr id="score-${ score.id    }">`);
        row.append(`<td>${ score.id }</td>`);
        row.append(`<td>${ score.fullname }</td>`);
        row.append(`<td>${ score.email }</td>`);
        row.append(`<td>${ score.total }</td>`);
        return row;
    }
</script>
