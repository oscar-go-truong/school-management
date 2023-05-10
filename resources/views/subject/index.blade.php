@extends('components.layout')
@section('content')
    <div id="wrapper">
        @if (session('error'))
            <script>
                toastr.error('{{ session('error') }}')
            </script>
        @endif
        @if (session('success'))
            <script>
                toastr.success('{{ session('success') }}')
            </script>
        @endif
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div> Subjects</div>
                        @if (Auth::User()->isAdministrator())
                            <a href="/subjects/create" class="inline btn btn-primary rounded pb-2"> New <i
                                    class="fa-solid fa-file-circle-plus"></i> </a>
                        @endif
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                @include('subject.table')

            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
