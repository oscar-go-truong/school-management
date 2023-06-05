@extends('components.layout')
@section('content')
    <div class="row">
        <div class="text-3xl font-bold col-md-6">
            Outcomes
        </div>
        <div class="col-md-6 d-flex justify-end">
            <div>
                <div class='inline-block  mr-3'>
                    {{-- filter by year --}}
                    <select
                        class="form-select
                                                w-24 text-sm filter inline-block"
                        data-column="year" id="filter-year">
                        <option value="">
                            All years
                        </option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" @if ($year == date('Y')) selected @endif>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="inline">
                    <button class="btn btn-primary rounded pb-1 " id="export">Export <i
                            class="fa-solid fa-cloud-arrow-down text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->

    <div class="table100 ver4 m-b-110">
        <div class="table100-body js-pscroll ps ps--active-y" id="outcomesTable">
            <table class="table table-hover">
                <thead>
                    <tr class="row100 head">
                        <th class="cell100">#</th>
                        <th class="cell100">Subject</th>
                        <th class="cell100">Course</th>
                        <th class="cell100">Homeroom Teacher</th>
                        <th class="cell100 text-center">Grade Points</th>
                        <th class="cell100 text-center">Grade Letter</th>
                        <th class="cell100 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody id="outcomesTableContents">
                </tbody>
            </table>
        </div>
    </div>
    </script>
    <script>
        let queryData = {
            year: new Date().getFullYear(),
        };

        const createRow = (outcome) => {
            let row = $(`<tr id="outcome-${ outcome.id    }">`);
            row.append(`<td>${ outcome.id }</td>`);
            row.append(`<td>${ outcome.subject }</td>`);
            row.append(`<td>${ outcome.course }</td>`);
            row.append(`<td>${ outcome.homeroom_teacher }</td>`);
            row.append(
                `<td class='text-center'>${ outcome.grade_points === null?"":outcome.grade_points }</td>`
            );
            row.append(
                `<td class='text-center'>${ outcome.grade_letter === null?"":outcome.grade_letter }</td>`
            );
            row.append(`<td class='text-center'>${ outcome.grade === null?"":outcome.grade }</td>`);
            return row;
        }

        const getTable = (createRow) => {
            // hidden data on table
            toastr.clear();
            toastr.info('Loading, please wait...');
            $('#outcomesTableContents').hide();
            $.ajax({
                type: "GET",
                url: '/outcomes/table',
                data: queryData,
                dataType: "json",
                success: function(resp) {
                    // clear old data
                    $('#outcomesTableContents').show();
                    $('#outcomesTableContents').html("");
                    const outcomes = resp;
                    // loading new data
                    if (outcomes.length === 0)
                        $('#outcomesTableContents').append($(
                            `<tr>
                            <td colspan="100%" class="text-center">
                                No matching records found
                            </td>
                        </tr>`
                        ));
                    else {
                        for (let i = 0; i < outcomes.length; i++) {
                            const outcome = createRow(outcomes[i]);
                            $('#outcomesTableContents').append(outcome);
                        }
                    }
                    toastr.clear();
                },
                error: function() {
                    $('#outcomesTableContents').show();
                    toastr.clear();
                    toastr.error('Error, Please try again later!');
                }
            });
        }

        $(document).ready(function() {
            getTable(createRow);
            $('#export').click(function() {
                toastr.info('Exporting...');
                window.location.href = "/outcomes/export?year=" + (queryData.year ? queryData.year : "");
            });

            $('#filter-year').change(function() {
                if ($(this).val()) {
                    queryData.year = $(this).val();

                } else {
                    queryData.year = null;

                }
                getTable(createRow);
            });
        })
    </script>
@endsection
