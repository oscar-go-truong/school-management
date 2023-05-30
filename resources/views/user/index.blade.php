@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    <div>
        <div class="row p-0">
            <div class="col-md-4 text-3xl font-bold "> Users</div>
            <div class="col-md-8 row p-0">
                <div class='col-md-10 d-flex justify-end filter-user'>
                    <div>
                        {{-- filter by status --}}
                        <select
                            class="form-select
                                                w-40 text-sm filter inline-block"
                            data-column="status" id="filter-status">
                            <option value="">
                                Select status
                            </option>
                            @foreach ($status as $name => $id)
                                <option value="{{ $id }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        {{-- filter by role --}}
                        <div class="dropdown inline-block">
                            <select class="select2 w-36" id="selectRole" multiple>

                                <option id="all-role" value="">
                                    All roles
                                </option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" id="role-{{ $role->name }}" class="role-option">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-flex justify-end mt-2">
                    <a class="btn btn-primary rounded pb-2" href='/users/create' class="text-gray-400">New <i
                            class="fa-solid fa-user-plus inline"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('user.table')
    <script>
        $(document).ready(function() {
            $('#selectRole').select2({
                placeholder: "Select roles",
                allowClear: true
            });
        })
    </script>
@endsection
