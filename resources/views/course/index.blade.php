@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}')
        </script>
    @endif

    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div>
                Courses
            </div>
            <div class="inline">
                <div class='inline-block  mr-3'>
                    {{-- filter by year --}}
                    <select
                        class="form-select
                                                    w-40 text-sm filter inline-block"
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
                @role('admin')
                    <a href="/courses/create" class="inline btn btn-primary rounded pb-2">New <i
                            class="fa-solid fa-file-circle-plus"></i>
                    </a>
                @endrole
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('course.table')
@endsection
