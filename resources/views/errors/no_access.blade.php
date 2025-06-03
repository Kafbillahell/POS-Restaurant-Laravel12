<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>403 - Akses Ditolak</title>
    <style>
        body {
            background: #f8fafc;
            color: #333;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .container {
            max-width: 400px;
        }
        h1 {
            font-size: 72px;
            margin-bottom: 0;
            color: #e3342f;
        }
        p {
            font-size: 20px;
            margin: 10px 0 20px;
        }
        a {
            color: #3490dc;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>403</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url('/dashboard') }}">Home</a>
    </div>
</body>
</html>
