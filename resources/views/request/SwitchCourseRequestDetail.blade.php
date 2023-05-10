@extends('request.detail')
@section('request-content')
    <div class="course-info-box">
        <p><b>User request name: </b> {{ $request->userRequest->fullname }}</p>
        <p><b>User request email: </b> {{ $request->userRequest->email }}</p>
        <p><b>Reason: </b> {{ $content->reason }}</p>
        @if ($request->status === $status['APPROVED'])
            <p><b>User approve name: </b> {{ $request->userApprove->fullname }}</p>
            <p><b>User approve email: </b> {{ $request->userApprove->email }}</p>
        @endif
        <p><b>Old course: </b> {{ $content->oldCourse->subject->name }}
            {{ $content->oldCourse->name }}</p>
        <p><b>New course: </b>{{ $content->newCourse->subject->name }}
            {{ $content->newCourse->name }}</p>
    </div>
@endsection
