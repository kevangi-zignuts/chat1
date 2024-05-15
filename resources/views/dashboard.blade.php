<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <a href="{{ route('auth.logout') }}">Logout</a>
    @foreach ($users as $user)
        <a href="{{ route('chat', ['id' => $user->id]) }}">{{ $user->name }}</a>
    @endforeach
</body>

</html>
