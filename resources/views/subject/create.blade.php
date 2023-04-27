@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold">
                        Create Subject
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content relative">
                    <form class="container" method="POST" action='{{ route('subjects.store') }}' id="create">
                        @csrf
                        <div class="form-group mt-3">
                            <label for="name" class="font-bold mb-1">Subject name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" aria-describedby="nameHelp" placeholder="Enter course name">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="descriptions" class="font-bold mb-1">Descriptions <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('descriptions') is-invalid @enderror" id="descriptions" name="descriptions"
                                placeholder="Enter course descriptions" rows="8"></textarea>
                            @error('descriptions')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                    <button type="submit" class=" btn bg-black text-white p-3 rounded-lg w-32 mb-5 float-right"
                        id="submit">submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.select2').select2();
        // validate form
        const validate = (name, descriptions) => {
            $('.form-control').removeClass('is-invalid');
            if (!name || !descriptions) {
                // Missing name
                if (!name) {
                    toastr.warning('Course name field is requried.');
                    $('#name').addClass('is-invalid');
                } else {
                    $('#name').addClass('is-valid');
                };
                // Missing descriptions
                if (!descriptions) {
                    toastr.warning('Descriptions field is requried.');
                    $('#descriptions').addClass('is-invalid');
                } else {
                    $('#descriptions').addClass('is-valid');
                };
                return false;
            }
            return true;
        }
        // hanle submit form
        $(document).ready(function() {
            $('#submit').click(function() {
                $(this).prop("disabled", true);
                const _token = '{{ csrf_token() }}';
                const name = $('#name').val();
                const descriptions = $('#descriptions').val();
                const isValid = validate(name, descriptions);
                if (isValid) {
                    $('#create').submit();
                } else {
                    $(this).prop("disabled", "");
                }
            });
        });
    </script>
@endsection
