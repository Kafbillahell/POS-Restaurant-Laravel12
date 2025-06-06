<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>Login - POS System</title>
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        .custom-bg {
            background: linear-gradient(135deg, #f5f3f0, #d2b48c);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .contact-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-weight: 500;
            color: #e1306c;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-link i {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .contact-link:hover {
            color: #ad1457;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .contact-link:hover i {
            transform: scale(1.2) rotate(5deg);
            color: #ad1457;
        }

        .custom-bg .text-center {
            margin-bottom: 0.5rem;
            /* kurangi jarak bawah animasi ke heading */
            margin-top: 0.5rem;
            /* kurangi jarak atas animasi */
        }

        .custom-bg .text-center lottie-player {
            width: 150px !important;
            height: 150px !important;
            margin: 0 auto;
            display: block;
        }

        /* Kurangi jarak heading ke paragraf */
        h2.mt-3.text-center {
            margin-top: 0.5rem;
            /* sebelumnya 1rem atau lebih, jadi kecilkan */
            margin-bottom: 0.3rem;
        }

        /* Kurangi jarak paragraf ke form */
        .custom-bg p.text-center {
            margin-top: 0;
            margin-bottom: 1rem;
            /* kecilkan dari sebelumnya */
        }

  .auth-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.auth-box {
    width: 100%;
    max-width: 750px; /* Ukuran lebih kecil dari sebelumnya */
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.modal-bg-img {
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
}

.custom-bg {
    border-top-right-radius: 15px;
    border-bottom-right-radius: 15px;
}

    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

       <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
    style="background:url('{{ asset('assets/images/big/auth-bg.jpg') }}') no-repeat center center; background-size: cover; height: 100vh; overflow: hidden;">
    <div class="auth-box row no-gutters" style="max-width: 750px;">
        <div class="col-lg-6 d-none d-lg-block modal-bg-img"
            style="background-image: url('{{ asset('assets/images/big/3.jpg') }}'); background-size: cover;">
        </div>
        <div class="col-lg-6 col-md-12 custom-bg">
            <div class="p-4">
                <div class="text-center">
                    <script src="{{ asset('js/lottie-player.js') }}"></script>
                    <lottie-player src="{{ asset('animations/lotie.json') }}" background="transparent" speed="1"
                        style="width: 120px; height: 120px; margin: 0 auto;" loop autoplay>
                    </lottie-player>
                </div>

                <h2 class="mt-3 text-center">Sign In</h2>
                <p class="text-center">Enter your email and password to access the dashboard.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form class="mt-4" method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label class="text-dark" for="email">Email</label>
                        <input class="form-control" id="email" type="email" name="email"
                            placeholder="Enter your email" required autofocus>
                    </div>
                    <div class="form-group">
                        <label class="text-dark" for="password">Password</label>
                        <input class="form-control" id="password" type="password" name="password"
                            placeholder="Enter your password" required>
                    </div>

                    <div class="text-center" id="buttonContainer">
                        <button type="submit" class="btn btn-dark btn-block">Sign In</button>
                    </div>

                    <div class="text-center mt-3" id="spinnerContainer" style="display:none;">
                        <lottie-player src="{{ asset('animations/spinner.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px; margin: 0 auto;" loop autoplay>
                        </lottie-player>
                        <p>Please wait...</p>
                    </div>

                    <div class="text-center mt-4">
                        <span>Contact me on</span>
                        <a href="https://www.instagram.com/ell.husnii/" class="contact-link" target="_blank"
                            rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
  /* Pastikan body dan html tidak bisa scroll */
  html, body {
    height: 100%;
    overflow: hidden;
    margin: 0;
  }
</style>

    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(".preloader").fadeOut();
    </script>
</body>

</html>