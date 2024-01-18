<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/login/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="title">
            Login Form
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="POST">
            @csrf
            <div class="field">
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                <label>Email</label>
            </div>
            <div class="field">
                <input type="password" name="password" class="form-control" required>
                <label>Password</label>
            </div>
            <div class="field mt-5">
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</body>

</html>