<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto my-10 p-5 bg-white rounded-lg shadow-md">
        <p class="text-gray-700 text-lg font-bold mb-5">Dear {{ $user->fullname }},</p>

        <p class="text-gray-700 text-base leading-7 mb-6"> We are delighted to invite you to our upcoming event,
            {{ $event->name }}, which will take place on
            {{ $event->date }} from {{ $event->start_time }} to {{ $event->end_time }} at
            room {{ $event->room->name }}.
            The content of the meeting will be about {{ $event->description }}.</p>

        <p class="text-gray-700 text-base leading-7 mb-6">Please find attached the detailed schedule and other
            information about the event. If you have any questions or
            concerns, please do not hesitate to reach out to us.
        </p>



        <p class="text-gray-700 text-base leading-7 mb-6">We look forward to seeing you at the event!</p>

        <p class="text-gray-700 text-base leading-7 mb-2">Best regards,</p>
        <p class="text-gray-700 text-base leading-7 mb-2">{{ $event->owner->fullname }}.</p>
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
