<table>
    <thead>
        <tr>
            <th class="text-bold bg-gray-400">#</th>
            <th class="text-bold bg-gray-400">Fullname</th>
            <th class="text-bold bg-gray-400">Email</th>
            <th class="text-bold bg-gray-400">Score</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr>
                <th>{{ $student->user->id }}</th>
                <th>{{ $student->user->fullname }}</th>
                <th>{{ $student->user->email }}</th>
                <th></th>
            </tr>
        @endforeach
    </tbody>
</table>
