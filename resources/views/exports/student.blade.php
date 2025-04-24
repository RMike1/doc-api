<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #686868;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1 style="text-align: center">Students Data</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>First-Name</th>
                <th>Last-Name</th>
                <th>Age</th>
                <th>Student No</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $student)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $student['first_name'] }}</td>
                    <td>{{ $student['last_name'] }}</td>
                    <td>{{ $student['age'] }}</td>
                    <td>{{ $student['student_no'] }}</td>
                    <td>{{ $student['level'] }}</td>
                </tr>
            @empty
                <tr>
                    <td style="text-align: center" colspan="7">
                        <h3>there is no student data yet</h3>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
