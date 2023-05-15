@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Scores<span class="text-2xl font-normal"> - {{ $exam->course->subject->name }}
                    {{ $exam->course->name }} - {{ $exam->type }} exams</span>
            </div>
            @if ($exam->can_edit_scores == 1 || Auth::user()->hasRole('admin'))
                <div class="inline">
                    <input type="file" id="data-sheet" class="form-control inline w-80 p-2y" name="file" accept=".csv">
                    <div class="inline">
                        <button class="btn btn-primary rounded pb-2" id="open-upload-file-btn">Upload <i
                                class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </button>
                    </div>
                    <div class="inline">
                        <button class="btn btn-secondary rounded pb-2" id="open-edit-form-btn">Update <i
                                class="fa-solid fa-pen-to-square text-xl"></i>
                        </button>
                    </div>
                </div>
            @else
                <div class="inline" id="request-update">
                    @if ($exam->isRequested === 0)
                        <button class="btn btn-success rounded pb-2" id="request-update-btn">Request Update <i
                                class="fa-sharp fa-solid fa-pen-to-square text-xl"></i>
                        </button>
                    @else
                        <div class="bg-primary text-white text-xl p-2 text-normal rounded">Requesting</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('score.table')
    <script>
        const id = '{{ $exam->id }}';
        let formData = [];
        let missingUser = [];

        const createRowDemo = (score) => {
            let row = $(`<tr id="score-demo-${score.user_id}">`);
            row.append(`<td>${ score.user_id }</td>`);
            row.append(`<td>${ score.fullname }</td>`);
            row.append(`<td>${ score.email }</td>`);
            row.append(
                `<td> <input class="form-control score-demo-input" data-id="${score.user_id}" type="number" value="${ score.score }" min="0" max="10"/></td>`
            );
            row.append(
                `<td class="text-danger text-2xl text-center"><i class="fa-solid fa-delete-left delete-score-demo" data-id="${score.user_id}"></i></td>`
            );
            return row;
        }
        const createDemoTable = () => {
            $('#table-layout-component').hide();
            $('#paginations').hide();
            $('#demo-remove-user').show();
            $('#scoresTable').html('');
            $('#uploadFileModal').modal('hide');
            $('#select-limit').hide();
            for (let i = 0; i < formData.length; i++)
                $('#scoresTable').append(createRowDemo(formData[i]));
            $('#table-layout-component').show();
            $('#custom-btns').html(
                `<div class="inline" id="AddStudentScores">
                            </div>
                            <div class="float-right inline">
                                <button class="btn btn-primary grounded" id="upload"> Submit </button>
                                <button class = "btn btn-secondary grounded" id = "cancel"> Cancel </button>
                            </div>`
            );
        }
        const closeDemoForm = () => {
            formData = [];
            missingUser = [];
            getTable(createRow);
            $('#select-limit').show();
            $('#custom-btns').html("");
            $('#demo-remove-user').hide();
        }
        // Function to parse CSV data into an array of rows
        const parseCSV = (csvData) => {
            let rows = csvData.split('\n');
            for (let i = 0; i < rows.length; i++) {
                rows[i] = rows[i].replace('\r', "");
            }
            let data = [];
            rows[0] = rows[0].toLowerCase().replace('#', 'user_id');
            let headers = rows[0].split(',');
            for (let i = 1; i < rows.length - 1; i++) {
                let cells = rows[i].split(',');
                let row = {}
                for (let j = 0; j < cells.length; j++) {
                    row[headers[j]] = cells[j];
                }
                data.push(row);
            }
            return data;
        }
        const createMissingUserSelect = () => {
            $('#AddStudentScores').html(`<select class="form-control select2 w-80" id="addStudentSelect"> 
                                    <option value ="" id = "selectDefault1">Add student </option>
                                </select> `);
            $('#addStudentSelect').select2();
            for (let i = 0; i < missingUser.length; i++) {
                $('#addStudentSelect').append(
                    `<option value = "${missingUser[i].user_id}" id="student-missing-${missingUser[i].user_id}" class="userOption">${missingUser[i].fullname}</option>`
                );
            }
        }
        const detachFile = (dataFile) => {
            $.ajax({
                method: 'GET',
                url: `/exams/${id}/scores/detach-file`,
                data: {
                    fileContent: dataFile,
                },
                dataType: 'json',
                success: function(resp) {
                    const data = resp.data;
                    const userIdList = [];
                    if (data) {
                        formData = [];
                        $('#table-layout-component').hide();
                        for (let i = 0; i < data.length; i++) {
                            formData.push(data[i]);
                            userIdList.push(data[i].user_id);
                        }
                        createDemoTable();
                        $.ajax({
                            method: "GET",
                            url: '/exams/missing-user',
                            data: {
                                exam_id: '{{ $exam->id }}',
                                user_id_list: userIdList
                            },
                            dataType: 'json',
                            success: function(resp) {
                                const data = resp.data;
                                if (resp.data.length) {
                                    for (let i = 0; i < data.length; i++) {
                                        const row = {
                                            user_id: data[i].student_id,
                                            fullname: data[i].user.fullname,
                                            email: data[i].user.email,
                                            score: data[i].total
                                        };
                                        missingUser.push(row);
                                    }
                                    createMissingUserSelect();
                                } else {
                                    $('#AddStudentScores').html(``);
                                }
                            },
                            error: function() {}
                        })
                        toastr.info('Please check the information again before submitting!');

                    } else {
                        closeDemoForm();
                        toastr.error(resp.message);
                    }

                },
                error: function() {
                    toastr.error('Error, please try again later!');
                    closeDemoForm();
                }
            })
        }
        const upload = () => {
            toastr.info('Updating!');
            $.ajax({
                method: "POST",
                url: `/exams/${id}/scores/import-file`,
                data: {
                    formData: formData
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.data) {
                        toastr.success(resp.message);
                    } else toasrt.error(message);
                    closeDemoForm();
                },
                error: function() {
                    closeDemoForm()
                    toastr.error('Error, please try again later!');
                }
            })
        }
        const requestUpdate = () => {
            toastr.info('Creating request!')
            $.ajax({
                method: 'POST',
                url: '/requests/edit-exam-scores',
                data: {
                    exam_id: '{{ $exam->id }}'
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.data) {
                        toastr.success(resp.message);
                        $('#request-update').html(
                            '<div class="bg-primary text-white text-xl p-2 text-normal rounded">Requesting</div>'
                        )
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function() {
                    toastr.error('Error, please try again later!');
                }

            })
        }
        $(document).ready(function() {
            $(document).on('click', '#cancel', function() {
                closeDemoForm();
            })
            $('#open-upload-file-btn').click(function() {
                toastr.info('Loading...');
                let btn = $(this);
                const file = $('#data-sheet')[0].files[0];
                if (!file) {
                    btn.attr('disabled', false);
                    toastr.warning('Please upload file!');
                } else {
                    $('#table-layout-component').hide();
                    $('#data-sheet').val('');
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const csvData = event.target.result;
                        let data = parseCSV(csvData);
                        detachFile(data);
                    };
                    reader.readAsText(file);
                }
            })
            $(document).on('click', '#upload', function() {
                toastr.clear();
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.options.closeButton = true;
                toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to update ?</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-secondary mr-3">cancel</button> 
                        <button class="btn btn-success ml-3" onclick='upload()'>Upload</button></div>
                    </div>`);
                toastr.options.timeOut = 600;
                toastr.options.extendedTimeOut = 600;
            })

            $(document).on('change', '.score-demo-input', function() {
                let input = $(this);
                const val = input.val();
                input.val(val);
                const id = input.data('id');
                const index = formData.findIndex(item => item.user_id == id);
                formData[index].score = val;
            })
            $(document).on('change', '#addStudentSelect', function() {
                let select = $(this);
                const user_id = select.val();
                if (user_id) {
                    const index = missingUser.findIndex(item => item.user_id == user_id);
                    let newRow = missingUser.splice(index, 1)[0];
                    if (missingUser.length === 0)
                        $('#AddStudentScores').html("");
                    formData.push(newRow);
                    $('#scoresTable').append(createRowDemo(newRow));
                    $('#student-missing-' + user_id).remove();

                }
            })
            $(document).on('click', '.delete-score-demo', function() {
                const btn = $(this);
                const user_id = btn.data('id');
                const index = formData.findIndex(item => item.user_id === user_id);
                let removeRow = formData.splice(index, 1)[0];
                missingUser.push(removeRow);
                createMissingUserSelect();
                $('#score-demo-' + removeRow.user_id).remove();
            });
            $(document).on('click', '#open-edit-form-btn', function() {
                toastr.info('Loading...');
                formData = [];
                missingUser = [];
                $('#table-layout-component').hide();
                $.ajax({
                    method: "GET",
                    url: '/exams/missing-user',
                    data: {
                        exam_id: '{{ $exam->id }}',
                        user_id_list: null
                    },
                    dataType: 'json',
                    success: function(resp) {
                        const data = resp.data;
                        if (resp.data.length) {
                            for (let i = 0; i < data.length; i++) {
                                const row = {
                                    user_id: data[i].student_id,
                                    fullname: data[i].user.fullname,
                                    email: data[i].user.email,
                                    score: data[i].total
                                };
                                formData.push(row);
                            }
                            createDemoTable();
                        } else {
                            toastr.error('Error, please try again later!');
                            closeDemoForm();
                        }
                    },
                    error: function() {}
                })
            })
            $('#request-update-btn').click(function() {
                toastr.clear();
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.options.closeButton = true;
                toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to create request update ?</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-secondary mr-3">cancel</button> 
                        <button class="btn btn-success ml-3" onclick='requestUpdate()'>Yes</button></div>
                    </div>`);
                toastr.options.timeOut = 600;
                toastr.options.extendedTimeOut = 600;
            })
        })
    </script>
@endsection
