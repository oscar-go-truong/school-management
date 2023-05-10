@extends('components.layout')
@section('content')
    <div id="wrapper">
        @if (session('error'))
            <script>
                toastr.error('{{ session('error') }}')
            </script>
        @endif
        <div id="page-wrapper">
            <div id="page-inner">
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
                                    <option value="{{ $value }}" @if (Auth::User()->isAdministrator() && $key === 'PENDING') selected @endif>
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
                                    <option value="{{ $value }}" @if (Auth::User()->isAdministrator() && $key === 'PENDING') selected @endif>
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
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
