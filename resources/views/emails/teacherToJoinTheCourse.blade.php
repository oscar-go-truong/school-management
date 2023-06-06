<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto my-10 p-5 bg-white rounded-lg shadow-md">
        <p class="text-gray-700 text-lg font-bold mb-5">Dear {{ $user->fullname }},</p>

        <p class="text-gray-700 text-base leading-7 mb-6"> I hope this email finds you well. I am writing to inform you
            that you have been added to the
            {{ $course->subject->name }}
            {{ $course->name }} class as a teacher. Congratulations!</p>

        <p class="text-gray-700 text-base leading-7 mb-6">As the new semester approaches, I would like to remind you to
            check your schedule and course materials regularly on the university's website or portal for any updates.
            Please make sure to also adhere to the class schedule to ensure you arrive on time for all lectures and
            activities.
            <br />
            For more information on the course visit the link below:
        </p>

        <a href="{{ asset('courses/' . $course->id) }}"
            class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-base inline-block mb-6">Go to
            Course</a>

        <p class="text-gray-700 text-base leading-7 mb-6">If you have any questions or concerns regarding the course or
            your enrollment, please do not hesitate to reach out to us. We are here to help you throughout your academic
            journey.</p>

        <p class="text-gray-700 text-base leading-7 mb-6"> Once again, welcome to the team, and thank you for your
            commitment to our students' education. We appreciate
            your dedication and hard work.</p>

        <p class="text-gray-700 text-base leading-7 mb-2">Best regards,</p>
        <p class="text-gray-700 text-base leading-7 mb-2">Trainning department.</p>
        <img src="https://res.cloudinary.com/dcdzae97y/image/upload/v1684745930/mail-logo_eza1y0.png" class="w-40" />
        <p class="text-gray-700 text-sm leading-7 mb-2 font-bold">Empowering Minds, Igniting Futures.</p>
        <div class="mt-4">
            <p>Contact with us:</p>
            <ul class="list-none inline-flex">
                <li><a href="#" class="text-blue-500 hover:text-blue-700">Website</a></li>
                <li class="mx-4"><a href="#" class="text-blue-500 hover:text-blue-700">Facebook</a></li>
                <li><a href="#" class="text-blue-500 hover:text-blue-700">Twitter</a></li>
                <li class="mx-4"><a href="#" class="text-blue-500 hover:text-blue-700">LinkedIn</a></li>
            </ul>
        </div>
    </div>
</body>

</html>
