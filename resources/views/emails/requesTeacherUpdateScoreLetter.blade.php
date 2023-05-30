<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto my-10 p-5 bg-white rounded-lg shadow-md">
        <p class="text-gray-700 text-lg font-bold mb-5">Dear {{ $user->fullname }},</p>

        <p class="text-gray-700 text-base leading-7 mb-6">I hope this email finds you in good health and high spirits. I
            am writing to request a review of my grade in {{ $score->exam->course->subject->name }}
            {{ $score->exam->course->name }} for student {{ $score->user->fullname }}. Although I did my best
            throughout the
            semester, I was disappointed with my final grade as it does not reflect my effort and academic performance.
        </p>

        <p class="text-gray-700 text-base leading-7 mb-6">I understand that grades are determined based on various
            factors such as assignments, exams, participation, and attendance. However, I believe that there may have
            been an error or miscalculation in my grade, which is why I am requesting a review.
        </p>
        <p class="text-gray-700 text-base leading-7 mb-6">If possible, could you please review my grades and provide any
            feedback or clarification on how they were calculated? I would be happy to discuss any concerns you may have
            and provide additional information if necessary.
            <br />
            For more information visit the link below:
        </p>
        <a href="{{ asset('scores/update/' . $score->edit_key) }}"
            class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-base inline-block mb-6">Review
            score</a>


        <p class="text-gray-700 text-base leading-7 mb-6">Thank you for your consideration and understanding. I look
            forward to hearing from you soon.</p>

        <p class="text-gray-700 text-base leading-7 mb-2">Best regards,</p>
        <p class="text-gray-700 text-base leading-7 mb-2">Training department.</p>
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
