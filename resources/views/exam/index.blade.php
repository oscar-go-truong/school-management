@extends('components.layout')
@section('content')
    <div class="row">
        <div class="text-3xl font-bold col-md-6">
            Exams
        </div>
        <div class="col-md-6 d-flex justify-end">
            <div>
                <div class='inline-block  mr-3'>
                    {{-- filter by subject --}}
                    <select class="form-select w-60 text-sm filter inline-block" data-column="course" id="filter-course">
                        <option value="">
                            All courses
                        </option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if ($course->id == $courseId) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('exam.table')
    <script>
        $(document).ready(function() {
            $('#filter-course').select2();
            $('#filter-course').change(function() {
                const select = $(this);
                const val = select.val();
                queryData.courseId = val ? val : null;
                getTable(createRow)
            });
            $('#filter-year').change(function() {
                const select = $(this);
                const val = select.val();
                queryData.year = val ? val : null;
                getTable(createRow)
            });
        })
    </script>
@endsection
