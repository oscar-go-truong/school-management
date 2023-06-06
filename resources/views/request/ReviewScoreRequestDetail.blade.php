@extends('request.detail')
@section('type-name', 'Reivew score')
@section('request-content')
    <div class="request-info-box">
        <div><b>User request name: </b> {{ $request->userRequest->fullname }}</div>
        <div><b>User request email: </b> {{ $request->userRequest->email }}</div>
        @if ($request->status === $status['APPROVED'])
            <div><b>User approve name: </b> {{ $request->userApprove->fullname }}</div>
            <div><b>User approve email: </b> {{ $request->userApprove->email }}</div>
        @endif
        <div><b>Subject: </b> {{ $content->exam->course->subject->name }}</div>
        <div><b>Course: </b> {{ $content->exam->course->name }}</div>
        <div><b>Exam: </b> {{ $content->exam->type }}</div>
        </span>
    </div>
@endsection
