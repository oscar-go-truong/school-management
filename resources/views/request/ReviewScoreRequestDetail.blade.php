@extends('request.detail')
@section('type-name', 'Reivew score')
@section('request-content')
    <div class="request-info-box">
        <p><b>User request name: </b> {{ $request->userRequest->fullname }}</p>
        <p><b>User request email: </b> {{ $request->userRequest->email }}</p>
        @if ($request->status === $status['APPROVED'])
            <p><b>User approve name: </b> {{ $request->userApprove->fullname }}</p>
            <p><b>User approve email: </b> {{ $request->userApprove->email }}</p>
        @endif
        <p><b>Subject: </b> {{ $content->exam->course->subject->name }}</p>
        <p><b>Course: </b> {{ $content->exam->course->name }}</p>
        <p><b>Exam: </b> {{ $content->exam->type }}</p>
        </span>
    </div>
@endsection
