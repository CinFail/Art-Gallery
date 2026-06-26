<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — Art Gallery MS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }

        .auth-header {
            background: #000000;
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .auth-header h1 {
            font-family: 'Cormorant Garamond', serif;
            color: #ffffff;
            font-size: 1.75rem;
            margin-bottom: .25rem;
        }

        .auth-header p {
            color: rgba(255,255,255,.65);
            font-size: .8rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin: 0;
        }

        .auth-body { padding: 2rem; }

        .form-label {
            font-size: .8rem;
            font-weight: 500;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #333333;
        }

        .form-control {
            border-color: #d0d0d0;
            background: white;
            color: #333333;
        }

        .form-control:focus {
            border-color: #000000;
            box-shadow: 0 0 0 .2rem rgba(0,0,0,.1);
        }

        .btn-auth {
            background: #000000;
            color: #ffffff;
            border: 1.5px solid #000000;
            width: 100%;
            padding: .65rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            letter-spacing: .05em;
            transition: all .2s;
        }

        .btn-auth:hover {
            background: #ffffff;
            color: #000000;
        }

        a { color: #000000; }
        a:hover { color: #666666; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="bi bi-palette2 me-2"></i>ArtGallery MS</h1>
            <p>Art Gallery Management System</p>
        </div>
        <div class="auth-body">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
