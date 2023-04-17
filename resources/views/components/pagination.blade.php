@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5 inline">
                    {!! __('Showing') !!}
                    <span class="font-medium" id="from">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium" id="to">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium" id="total">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
                <div class="inline-block ml-5">
                    <span class="font-medium">Items per page: <span>
                            <div class='inline-block'>
                                <select class="form-select  w-40  text-sm" aria-label="Default select example"
                                    id="itemPerPage">
                                    @foreach ($options as $option)
                                        {
                                        <option value="{{ $option }}"
                                            @if ($option === $paginator->perPage()) selected @endif>{{ $option }}
                                        </option>
                                        }
                                    @endforeach

                                </select>
                            </div>
                </div>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}

                    <div id="prev"
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                        aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>


                    {{-- Pagination Elements --}}
                    <span id="pagination" class="relative z-0 inline-flex shadow-sm rounded-md">
                        @foreach ($elements as $element)
                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span id="page-{{ $page }}"
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-gray-300 border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                            data-index="{{ $page }}">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <span id="page-{{ $page }}"
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                            data-index="{{ $page }}">
                                            {{ $page }}
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </span>

                    {{-- Next Page Link --}}
                    <div id="next"
                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                        aria-label="{{ __('pagination.next') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
            </div>
        </div>
    </nav>
@endif
