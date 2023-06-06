@extends('components.layout')
@section('content')
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
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Subjects</div>
            @role('admin')
                <a href="/subjects/create" class="inline btn btn-primary rounded pb-2"> New <i
                        class="fa-solid fa-file-circle-plus"></i> </a>
            @endrole
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('subject.table')
@endsection
