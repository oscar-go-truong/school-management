<p>Dear {{ $user->fullname }}, </p>
<p>
    I am writing to inform you that you have been successfully enrolled in {{ $course->subject->name }}
    {{ $course->name }}. Congratulations on your enrollment!
</p>

<p>
    As the new semester approaches, I would like to remind you to check your schedule and course materials regularly
    on
    the university's website or portal for any updates. Please make sure to also adhere to the class schedule to
    ensure you
    arrive on time for all lectures and activities.
</p>

<p>For more information about the course please click the link below:</p>

<p>
    For more information on the course visit the link below:
</p>
<a href="https://x-school.azurewebsites.net/courses/{{ $course->id }}">
    Go to Course.
</a>

<p>
    If you have any questions or concerns regarding the course or your enrollment, please do not hesitate to reach
    out
    to us. We are here to help you throughout your academic journey.
</p>

<p>
    Once again, congratulations on your enrollment, and we look forward to seeing you in class soon!
</p>

<p>
    Best regards,
</p>

<p>
    X School
</p>
