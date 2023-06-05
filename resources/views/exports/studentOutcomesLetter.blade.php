<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="mb-4">
        <h1 class="text-2xl font-bold inline">XSCHOOL</h1>
        <p class="text-xs inline" style="margin-left:400px">Report Date: {{ now() }}</p>
        <h1 class="text-2xl font-bold text-center">Student Report Card</h1>
    </div>
    <div class="mb-8">
        <p class="inline text-xs">Fullname:</p>
        <h2 class="text-sm  mb-2 inline">{{ $student->fullname }}</h2>
        <br />
        <p class="inline text-xs">Email:</p>
        <h2 class="text-sm  mb-2 inline">{{ $student->email }}</h2>
        <br />
        <p class="inline text-xs">Year:</p>
        <h2 class="text-sm  mb-2 inline">{{ $year ? $year : 'All' }}</h2>
        <br />

    </div>
    <div class="mb-8 text-xs">
        <p>This summary report outlines your overall performance and highlights key areas of strength and improvement.
            The report includes your final grade, as well as detailed information on your assignment scores,
            participation, and any additional feedback from your instructor. Use this report to reflect on your progress
            and identify areas for future growth.
        </p>
    </div>
    <table class="w-full border-collapse border border-gray-400 mb-8 text-xs">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-400 px-2 py-2">#</th>
                <th class="border border-gray-400 px-2 py-2">Subject Name</th>
                <th class="border border-gray-400 px-2 py-2">Course</th>
                <th class="border border-gray-400 px-2 py-2">Grade Points</th>
                <th class="border border-gray-400 px-2 py-2">Grade Letter</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($outcomes as $outcome)
                <tr>
                    <td class="border border-gray-400 px-2 py-2">{{ array_search($outcome, $outcomes) }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ $outcome->subject }}</td>
                    <td class="border border-gray-400 px-2 py-2">{{ $outcome->course }}</td>
                    <td class="border border-gray-400 px-2 py-2 text-center">{{ $outcome->grade_points }}</td>
                    <td class="border border-gray-400 px-2 py-2 text-center">{{ $outcome->grade_letter }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class=" mb-8" style="margin-left:80%">
        <div class="text-xs">
            <h3 class="text-xs font-medium mb-1">School Contact</h3>
            <p class="mb-1">123 Main Street</p>
            <p class="mb-1">Anytown, USA 12345</p>
            <p class="mb-1">Phone: (555) 555-1212</p>
            <p class="mb-1">Email: info@xschool.com</p>
        </div>
    </div>
</body>

</html>
