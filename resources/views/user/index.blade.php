@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Users</div>
            <div>
                <div class='inline-block translate-y-[-5px] mr-3'>
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
                        <button class="form-control text-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Select role
                        </button>
                        <ul class="dropdown-menu p-3 " aria-labelledby="dropdownRole" id="dropdownRole">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="all" id="all-role">
                                <label for="all-role" class="text-base font-light">
                                    All roles
                                </label>
                            </div>
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-check-input" name="role" type="checkbox"
                                        value="{{ $role->name }}" id="role-{{ $role->name }}">
                                    <label for="role-{{ $role->name }}" class="text-base font-light">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <a class="btn btn-primary rounded pb-2" href='/users/create' class="text-gray-400">New <i
                        class="fa-solid fa-user-plus inline"></i></a>
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('user.table')
@endsection
