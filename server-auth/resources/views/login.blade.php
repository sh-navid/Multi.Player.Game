<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/login" method="POST">
        @csrf
        <input type="text" name="email" placeholder="Email" required/>
        <input type="password" name="password" placeholder="Password" required/>
        <input type="submit" value="Login">
    </form>
    <a href="/register">Go to register page</a>
</body>
</html>