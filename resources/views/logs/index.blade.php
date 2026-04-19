<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>API Logs</h2>

    <table border="1">
    <tr>
        <th>Endpoint</th>
        <th>Method</th>
        <th>IP</th>
        <th>User</th>
    </tr>

    @foreach($logs as $log)
    <tr>
        <td>{{ $log->endpoint }}</td>
        <td>{{ $log->method }}</td>
        <td>{{ $log->ip }}</td>
        <td>{{ $log->user_id }}</td>
    </tr>
    @endforeach

    </table>
</body>
</html>
