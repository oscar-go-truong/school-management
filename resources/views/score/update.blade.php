@extends('components.layout')
@section('content')
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div>
                Review Score
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    <div class="table-content relative">
        <div class="row form-group">
            <div class="col-6 font-bold">Subject: </div>
            <div class="col-6">{{ $score->exam->course->subject->name }}</div>
            <div class="col-6 font-bold">Course: </div>
            <div class="col-6">{{ $score->exam->course->name }}</div>
            <div class="col-6 font-bold">Exam: </div>
            <div class="col-6">{{ $score->examType }}</div>
            <div class="col-6 font-bold">Student : </div>
            <div class="col-6">{{ $score->user->fullname }}</div>
        </div>
        <form class="container" method="POST" id="update">
            @csrf
            {{ method_field('PATCH') }}

            <input type="hidden" name="id" value="{{ $score->id }}" />
            <input type="hidden" name="edit_key" value="{{ $score->edit_key }}" />
            <div class="form-group mt-3 row ">
                <label for="name" class="font-bold mb-1">Score <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="total" name="total" aria-describedby="nameHelp"
                    value="{{ $score->total }}">
                @error('total')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>


        </form>
        <button type="submit" class=" btn bg-black text-white p-3 rounded-lg w-32 mb-5 float-right"
            id="submit">Update</button>
    </div>
    <script>
        $(document).ready(function() {
            $('#submit').click(function() {
                const btn = $(this);
                btn.attr('disabled', true);
                const score = $('#total').val();
                if (!score) {
                    toastr.warning('Please input new score!');
                    btn.attr('disabled', false);
                } else
                    $('#update').submit();
            })
        })
    </script>
@endsection
