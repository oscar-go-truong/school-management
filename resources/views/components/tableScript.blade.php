<script>
    const PAGINATION_LIMIT = 7;
    toastr.options = {
        maxOpened: 1,
    };
    const getTable = (createRow) => {
        // hidden data on table
        toastr.clear();
        toastr.info('Loading, please wait...');
        $(tableId).hide();
        $('#paginations').hide();
        // active current page 
        $('.pageIndex').removeClass('bg-gray-300');
        $('#page-' + queryData.page).removeClass('bg-white');
        $('#page-' + queryData.page).addClass('bg-gray-300');
        // get data 
        $.ajax({
            type: "GET",
            url: url,
            data: queryData,
            dataType: "json",
            success: function(resp) {
                // clear old data
                $(tableId).show();
                $(tableId).html("");
                const models = resp.data;
                // loading new data
                if (models.length === 0)
                    $(tableId).append($(
                        `<tr>
                            <td colspan="9" class="text-center">
                                No matching records found
                            </td>
                        </tr>`
                    ));
                else {
                    $('#paginations').show();
                    for (let i = 0; i < models.length; i++) {
                        const model = createRow(models[i]);
                        $(tableId).append(model);
                    }
                    last_page = resp.last_page;
                    // update pagination
                    $('#from').text(resp.from);
                    $('#to').text(resp.to);
                    $('#total').text(resp
                        .total);
                    $('#pagination').html("");
                    let pages = [queryData.page];
                    let k = 1;
                    while (pages.length < PAGINATION_LIMIT && (queryData.page - k > 0 || queryData
                            .page +
                            k <=
                            last_page)) {
                        if (queryData.page - k > 0) pages.unshift(queryData.page - k);
                        if (queryData.page + k <= last_page) pages.push(queryData.page + k);
                        k++;
                    }
                    if (pages[0] > 1)
                        $('#pagination').append(`<span 
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700  border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                          >
                                            ...
                                        </span>`);
                    for (let i = 0; i < pages.length; i++) {
                        $('#pagination').append(`<span id="page-${pages[i]}"
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 ${pages[i] === queryData.page ? "bg-gray-300":""} border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                            data-index="${pages[i]}">
                                            ${pages[i]}
                                        </span>`);
                    }
                    if (pages[pages.length - 1] < last_page)
                        $('#pagination').append(`<span 
                                            class="pageIndex relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700  border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                          >
                                            ...
                                        </span>`);
                }
                toastr.clear();
                toastr.success('Done!');
            },
            error: function() {
                $(tableId).show();
                toastr.clear();
                toastr.error('Error, Please try again later!');
            }
        });
    }
    const submitDelete = (id) => {
        toastr.clear();
        toastr.info(`Deleting ${model}!`);
        $.ajax({
            type: "DELETE",
            url: "/" + model + "s/" + id,
            dataType: "json",
            success: function(resp) {
                if (resp.data) {
                    toastr.clear();
                    toastr.success(resp.message);
                    $(`#${model}-` + id).remove();
                } else {
                    toastr.clear();
                    toastr.error(resp.message);
                }
            },
            error: function() {
                toastr.clear();
                toastr.error('Error, Please try again later!');
            }
        });;
    }
    $(document).ready(function() {
        getTable(createRow);
        // update user's status
        $('#itemPerPage').change(function() {
            queryData['limit'] = $(this).val();
            queryData['page'] = 1;
            getTable(createRow);
        });
        // 
        $(document).on('click', '.pageIndex', function() {
            queryData.page = $(this).data('index');
            $('.pageIndex').removeClass('bg-gray-300');
            $(this).removeClass('bg-white');
            $(this).addClass('bg-gray-300')
            getTable(createRow);
        });
        // Go to next page
        $('#next').click(function() {
            if (!last_page || queryData.page < last_page) {
                queryData.page++;
                getTable(createRow);
            }
        })
        // Go to prev page
        $('#prev').click(function() {
            if (queryData.page != 1) {
                queryData.page--;
                getTable(createRow);
            }
        })
        // Sorting column
        $('.sort').click(function() {
            $('.sort').removeClass('sorting_desc');
            $('.sort').removeClass('sorting_asc');
            const column = $(this).data('column');
            if (queryData.orderBy === column) {
                if (queryData.orderDirect === 'asc') {
                    queryData.orderDirect = "desc";
                    $(this).addClass('sorting_desc');
                } else {
                    queryData.orderDirect = "asc";
                    $(this).addClass('sorting_asc');
                }
            } else {
                queryData.orderBy = column;
                queryData.orderDirect = "asc";
                $(this).addClass('sorting_asc');
            }
            getTable(createRow);
        })
        // search
        $('#searchKey').change(function() {
            const val = $(this).val()
            if (queryData.search) {
                queryData.search.key = val;
                queryData.page = 1;
                getTable(createRow);
            } else if (val) {
                toastr.clear();
                toastr.warning('Select column before search!');
            }
        });
        $('#searchColumn').change(function() {
            const val = $(this).val();
            if (val) {
                queryData.search = {
                    column: val,
                    type: 'like',
                    key: $('#searchKey').val()
                };
                queryData.page = 1;
                if ($('#searchKey').val())
                    getTable(createRow);
            } else {
                queryData.search = null;
                getTable(createRow);
            }
        })

        // Change model status
        $(document).on('change', '.status', function() {
            toastr.clear();
            toastr.info('Updating status!');
            let id = $(this).data('id');
            let status = $(this).is(':checked') ? 1 : 0;
            $(this).parent().children('label').text(status ? "active" : "blocked");
            $.ajax({
                type: "PATCH",
                url: "/" + model + "s/status/" + id,
                data: {
                    status: status,
                },
                dataType: "json",
                success: function(resp) {
                    if (resp.data) {
                        toastr.clear();
                        toastr.success(resp.message);
                    } else {
                        toastr.clear();
                        toastr.error(resp.message);
                    }
                },
                error: function() {
                    toastr.clear();
                    toastr.error('Error, Please try again later!');
                }
            });
        });
        // delete user
        $(document).on('click', '.delete', function() {
            const name = $(this).data('name');
            const id = $(this).data('id');
            toastr.clear();
            toastr.options.timeOut = 0;
            toastr.options.extendedTimeOut = 0;
            toastr.options.closeButton = true;
            toastr.options.preventDuplicates = true;
            toastr.warning(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to delete ${model} <b>${name}!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-secondary mr-3">cancel</button> 
                        <button class="btn btn-danger ml-3 submit-delete" onclick='submitDelete(${id})'>delete</button></div>
                    </div>`);
            toastr.options.timeOut = 600;
            toastr.options.extendedTimeOut = 600;
        });
    });
</script>
