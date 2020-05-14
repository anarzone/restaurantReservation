<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Amburan Reservation</title>
    <!-- Custom CSS -->
    <link href="{{asset('back/dist/css/style.min.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="main-wrapper">

    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-8 col-md-7 bg-white bg-auth">
                <div class="p-3">
                    <div class="text-center">
                        <img width="150" src="{{asset('back/assets/images/logo.png')}}" alt="">
                    </div>
                    <form class="mt-4" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark" for="uname">Email</label>
                                    <input class="form-control" id="email" type="email" name="email"
                                           placeholder="email adresinizi daxil edin">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Şifrə</label>
                                    <input class="form-control" id="pwd" type="password" name="password"
                                           placeholder="şifrənizi daxil edin">
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-block btn-dark">Daxil ol</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('back/assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('back/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('back/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>

</body>

</html>
