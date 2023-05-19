<p>Dear {{ $user->fullname }}, </p>
<p>
    I hope this email finds you well. I am writing to inform you that you have been added to the
    {{ $course->subject->name }}
    {{ $course->name }} class as a teacher. Congratulations!
</p>

<p>
    As a part of the teaching team for this course, we believe that your expertise and experience will be of great
    value to our students. We are excited to have you on board and look forward to working together to provide an
    excellent learning experience for our students.
</p>
<p>
    For more information on the course visit the link below:
</p>
<a href="https://x-school.azurewebsites.net/courses/{{ $course->id }}">
    Go to Course.
</a>

<p>
    Please let us know if you need
    any assistance in preparing for the class or if you have any questions or concerns.
</p>

<p>
    Once again, welcome to the team, and thank you for your commitment to our students' education. We appreciate
    your dedication and hard work.
</p>

<p>
    Best regards,
</p>

<p>
    X School
</p>
