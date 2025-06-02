<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" style="text-align: left;">ID</th>
                <th scope="col" style="text-align: left;">Last Name</th>
                <th scope="col" style="text-align: left;">First Name</th>
                <th scope="col" style="text-align: left;">Email Address</th>
                <th scope="col" style="text-align: left;">Phone Number</th>
                <th scope="col" style="text-align: left;">Job Title</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <th scope="row" style="text-align: left;">{{ $user->id }}</th>
                    <td style="text-align: left;">{{ $user->last_name }}</td>
                    <td style="text-align: left;">{{ $user->first_name }}</td>
                    <td style="text-align: left;">{{ $user->email }}</td>
                    <td style="text-align: left;">{{ $user->phone }}</td>
                    <td style="text-align: left;">{{ $user->job }}</td>
                </tr>
            @empty
                <tr>
                    <td span="6">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
