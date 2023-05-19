@extends('request.detail')
@section('type-name', 'Switch course')
@section('request-content')
    <div class="course-info-box">
        <div><b>User request name: </b> {{ $request->userRequest->fullname }}</div>
        <div><b>User request email: </b> {{ $request->userRequest->email }}</div>
        <div><b>Reason: </b> {{ $content->reason }}</div>
        @if ($request->status === $status['APPROVED'])
            <div><b>User approve name: </b> {{ $request->userApprove->fullname }}</div>
            <div><b>User approve email: </b> {{ $request->userApprove->email }}</div>
        @endif
        <div><b>Old course: </b> {{ $content->oldCourse->subject->name }}
            {{ $content->oldCourse->name }}</div>
        <div><b>New course: </b>{{ $content->newCourse->subject->name }}
            {{ $content->newCourse->name }}</div>
    </div>
@endsection
