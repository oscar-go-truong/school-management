@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif

    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Requests</div>
            <div class="inline">
                <select
                    class="form-select
                                                    w-40 text-sm filter inline-block"
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
                <select
                    class="form-select
                                                    w-40 text-sm filter inline-block"
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
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('request.table')
@endsection
