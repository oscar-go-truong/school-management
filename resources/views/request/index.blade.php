@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif

    <div class="row">
        <div class="col-md-12 row">
            <div class="text-3xl font-bold col-md-4 "> Requests</div>
            <div class="col-md-8 p-0 d-flex justify-end">
                <div>
                    @role('admin')
                        <select
                            class="form-select
                                                    w-36 text-sm filter inline-block"
                            data-column="status" id="filter-type">
                            <option value="">
                                All types
                            </option>
                            @foreach ($types as $key => $value)
                                <option
                                    value="{{ $value }}"@role('admin') @if ($key === 'PENDING') selected @endif @endrole>
                                    {{ ucfirst(strtolower(str_replace('_', ' ', $key))) }}
                                </option>
                            @endforeach
                        </select>
                    @endrole
                    <select
                        class="form-select
                                                    w-36 text-sm filter inline-block"
                        data-column="status" id="filter-status">
                        <option value="">
                            All status
                        </option>
                        @foreach ($status as $key => $value)
                            <option value="{{ $value }}"
                                @role('admin') @if ($key === 'PENDING') selected @endif @endrole>
                                {{ ucfirst(strtolower($key)) }}
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
    @include('request.table')
@endsection
