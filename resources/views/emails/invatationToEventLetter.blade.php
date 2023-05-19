<div>
    <p>Dear {{ $user->fullname }},</p>
    <p>We are delighted to invite you to our upcoming event, {{ $event->name }}, which will take place on
        {{ $event->date }} from {{ $event->start_time }} to {{ $event->end_time }} at
        room {{ $event->room->name }}.
        The content of the meeting will be about {{ $event->description }}</p>
    <p>Please find attached the detailed schedule and other information about the event. If you have any questions or
        concerns, please do not hesitate to reach out to us.</p>
    <p>We look forward to seeing you at the event!</p>
    <p>Sincerely,</p>
    <p>{{ $event->owner->fullname }},<br>XSchool</p>
</div>
