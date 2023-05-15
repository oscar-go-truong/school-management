@extends('components.tableLayout')
@section('th')
    <tr>
        <th class="sort sorting sorting_asc" data-column="id">#</th>
        <th class="sort sorting" data-column="name">Name</th>

        <th class="sort sorting" data-column="descriptions">Descriptions</th>
        <th>Courses</th>
        <th class="text-center">Detail</th>
        @role('admin')
            <th>Status</th>
            <th class="text-center">Update</th>
        @endrole
    </tr>
@endsection
@section('tableId', 'subjectsTable')
<script>
    const model = 'subject';
    const tableId = '#subjectsTable';
    const isAdmin = '{{ Auth::User()->hasRole('admin') }}';
    const url = '/subjects/table';
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
    const createRow = (subject) => {
        let row = $(`<tr id="subject-${ subject.id    }">`);
        row.append(`<td class="min-w-xs">${ subject.id }</td>`);
        row.append(`<td>${ subject.name }</td>`);
        row.append(`<td>${ subject.descriptions }</td>`);
        row.append(
            `<td class="dark-link text-center"><a href="/courses?subjectId=${subject.id}">${subject.course_count}</a></td>`
        );
        row.append(` <td class="text-info text-2xl text-center">
                        <a href='/subjects/${ subject.id }'><i class="fa-sharp fa-solid fa-circle-info"></i></a>
                    </td>`);
        if (isAdmin) {
            row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input status" type="checkbox" id="${ subject.id }"
                    data-id="${ subject.id }" ${ subject.status === 1 ? 'checked' : '' } >
                    <label class="form-check-label" for="${ subject.id }">
                    ${ subject.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);

            row.append(`<td class="text-primary"><a href="/subjects/${ subject.id }/edit"><i
                                        class="fa-solid fa-pen-to-square"></i></a></td>`);
        }
        return row;
    }
</script>
