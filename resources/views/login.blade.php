<!-- resources/views/login.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
@if($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif
</body>
</html>
