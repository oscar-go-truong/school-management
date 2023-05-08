@extends('request.detail')
@section('request-content')
    <div class="request-info-box">
        <p><b>User request name: </b> {{ $request->userRequest->fullname }}</p>
        <p><b>User request email: </b> {{ $request->userRequest->email }}</p>
        @if ($request->status === $status['APPROVED'])
            <p><b>User approve name: </b> {{ $request->userApprove->fullname }}</p>
            <p><b>User approve email: </b> {{ $request->userApprove->email }}</p>
        @endif
        <p><b>Room: </b> {{ $content->room->name }}</p>
        <p><b>From : </b> {{ $content->booking_date_start }}</p>
        <p><b>To : </b> {{ $content->booking_date_finish }}</p>
    </div>
@endsection
