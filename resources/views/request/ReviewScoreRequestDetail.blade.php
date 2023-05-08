@extends('request.detail')
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
        @if ($request->status === $status['APPROVED'])
            <p><b>New score: </b> {{ $content->new_score }}</p>
        @endif
        </span>
    </div>
@endsection
