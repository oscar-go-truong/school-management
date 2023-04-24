<div class="table-content"">
    <div class="d-flex justify-content-between mb-1">
        <div class="inline-block ml-5">
            <span class="font-medium">Show : <span>
                    <div class='inline-block'>
                        <select class="form-select  w-20  text-sm" aria-label="Default select example" id="itemPerPage">

                            <option value="10">
                                10
                            </option>
                            <option value="25">
                                25
                            </option>
                            <option value="50">
                                50
                            </option>
                            <option value="100">
                                100
                            </option>
                        </select>
                    </div>
                    <span class="font-medium"> entries <span>
        </div>
        @if (isset($searchColumns))
            <div class="inline-block ">
                {{-- select column for search --}}

                <select class="form-select  w-40  text-sm inline-block translate-x-[12px]" id="searchColumn">
                    <option value="">
                        Select column
                    </option>
                    @foreach ($searchColumns as $key => $value)
                        <option value="{{ $key }}">
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                <input type="text" class="form-control w-60 h-8 inline py-[16px] " id='searchKey'>
            </div>
        @endif
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            @yield('th')
        </thead>
        <tbody id="@yield('tableId')">

        </tbody>
    </table>
    <div id="userPagination" class="pt-2">
        @include('components.pagination')
    </div>
</div>
@include('components.tableScript')
