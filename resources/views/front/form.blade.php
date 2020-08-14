<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Required meta tags-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="NetGroup">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Title Page-->
        <title>Amburan Rezervasiya</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('css/t-datepicker.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/t-datepicker-yellow.css')}}">
        <link rel="stylesheet" href="{{asset('css/gilroy.css')}}">
        <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <link rel="stylesheet" href="{{asset('css/mobile.css')}}">
        <link rel="stylesheet" href="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    </head>

    <style>
        /* The container */
        .custom-radio-button {
            display: block;
            position: relative;
            padding-left: 28px;
            margin-bottom: 12px;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            font-style: normal;
            font-weight: 800;
            font-size: 16px;
            color: #313131;
            font-family: 'Libre Franklin', sans-serif;
        }

        /* Hide the browser's default radio button */
        .custom-radio-button input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom radio button */
        .checkmark {
            position: absolute;
            top: 3px;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 50%;
        }

        /* On mouse-over, add a grey background color */
        .custom-radio-button:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .custom-radio-button input:checked ~ .checkmark {
            background-color: #efb20e;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .custom-radio-button input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .custom-radio-button .checkmark:after {
            top: 6px;
            left: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
        }
    </style>

<body>
    <section class="reservation_box">
    <div class="bg-image">
        <img src="{{asset('images/bg_image.jpg')}}" alt="">
    </div>
    <div class="form-box">
        <div class="lang-flex-box">
            <div class="lang-item">
                <a href="#">AZ</a>
            </div>
            <div class="lang-item">
                <a href="#">EN</a>
            </div>
            <div class="lang-item">
                <a href="#">RU</a>
            </div>
        </div>
        <div class="form-center">
            <div class="center-logo">
                <img src="{{asset('images/logo.svg')}}" alt="">
            </div>
            <div class="title-box">
                <h1>RESERVATION</h1>
            </div>
            <div class="form-section input-box">
                <div class="input-box">
                    <p class="half-label">FIRST NAME</p>
                    <input type="text" class="form-inp" name="firstname" id="firstname" onblur="validationForm(this, 1)">
                    <div class="info-validation">
                        <i class="fa fa-question"></i>
                        <div class="box-validation">
                            <p>Ad minimum 3 hərifdən ibarət olmalıdır.</p>
                        </div>
                    </div>
                </div>
                <div class="input-box">
                    <p class="half-label">LAST NAME</p>
                    <input type="text" class="form-inp" name="lastname" id="lastname">
                </div>
                <div class="input-box">
                    <p class="half-label">PHONE</p>

                    <div class="flex-input-box">
                        <input type="text" onpaste="event.preventDefault()" id="phone" name="phone" class="form-inp" onblur="validationForm(this,3)">
                    </div>

                    <div class="info-validation">
                        <i class="fa fa-question"></i>
                        <div class="box-validation">
                            <p>Telefon nömrəsi düzgün qeyd olunmayıb.</p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="label-text">
                RESTAURANTS
            </p>
            <div class="radios-container">
                <div class="row">
                    @foreach($restaurants as $res)
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">

{{--                            <label class="" for="amburan_beach_club">{{$res->name}}--}}
{{--                            </label>--}}
{{--                            <input type="radio" name="radio_inp" value="{{$res->id}}"  id="amburan_beach_club">--}}

                            <label class="custom-radio-button" for="{{$res->name}}">{{$res->name}}
                                <input type="radio" name="radio_inp" value="{{$res->id}}"  id="{{$res->name}}">
                                <span class="checkmark"></span>
                            </label>
                        </div>

                    @endforeach
                </div>
            </div>
            <div class="custom-select-item">
                <p class="half-label">HALL</p>
                <div class="select-btn">
                    <p class="title">Zallar</p>
                    <i class="figure-icon fa fa-angle-down"></i>
                </div>
                <div class="content-custom-select">
                </div>
            </div>
            <div class="form-section input-box">
                <div class="input-box">
                    <p class="half-label">SEATS</p>
                    <input type="text" class="form-inp" name="people" onblur="validationForm(this, 4)">
                    <div class="info-validation">
                        <i class="fa fa-question"></i>
                        <div class="box-validation">
                            <p>Minimum 1 nəfər olmalıdır.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="date-box">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-6 col-xl-6">
                        <p class="half-label">DATE</p>
                        <div class="t-datepicker">
                            <div class="t-check-in"></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6 col-xl-6">
                        <p class="half-label">TIME</p>
                        <div class="box-data-clock">
                            <div class="custom-date-picker">
                                <input type="text" class="clock-value-inp" name="reservation_date" hidden>
                                <p class="value-clock"></p>
                                <div class="time-control">
                                    <div class="flex-control-time justify-content-center">
                                        <p class="time-text text-hours">
                                            12
                                        </p>
                                        <div class="control-box">
                                            <i class="fa fa-angle-up" onclick="minusTime(1, 1)">
                                            </i>
                                            <i class="fa fa-angle-down" onclick="minusTime(1, 2)">
                                            </i>
                                        </div>
                                        <p class="time-text text-minute">
                                            00
                                        </p>
                                        <div class="control-box">
                                            <i class="fa fa-angle-up" onclick="minusTime(2, 1)">
                                            </i>
                                            <i class="fa fa-angle-down" onclick="minusTime(2, 2)">
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="message-box">
                <p class="half-label">SPECIAL REQUESTS</p>
                <textarea class="message-area" name="note"></textarea>
            </div>

        </div>
        <div class="success-section">
        </div>
        <button class="submit-btn">
            BOOK NOW
        </button>
        <button class="back-btn">
            BACK
        </button>
    </div>
</section>
    <footer class="partner-footer">
    <div class="flex-partner">
        <div class="social-media-footer">
            <div class="item-media">
                <img src="{{asset('images/blue-location.svg')}}" alt="">
                <p class="title">
                    Bilgah district, Baku
                </p>
            </div>
            <div class="item-media">
                <img src="{{asset('images/whatsapp-bue.svg')}}" alt="">
                <p class="title">
                    Bilgah district, Baku
                </p>
            </div>
        </div>
        <div class="logo-box">
            <img src="{{asset('images/logo-footer.svg')}}" alt="">
        </div>
        <div class="logo-box">
            <img src="{{asset('images/logo-footer.svg')}}" alt="">
        </div>
        <div class="logo-box">
            <img src="{{asset('images/logo-footer-2.svg')}}" alt="">
        </div>
        <div class="logo-box">
            <img src="{{asset('images/logo-footer-3.svg')}}" alt="">
        </div>
        <div class="logo-box">
            <img src="{{asset('images/logo-footer-4.svg')}}" alt="">
        </div>
        <div class="flex-media">
            <p>Follow us </p>
            <a href="#">
                <img src="{{asset('images/instagram.svg')}}" alt="">
            </a>
            <a href="#">
                <img src="{{asset('images/facebook.svg')}}" alt="">
            </a>
        </div>
    </div>
</footer>
</body>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/t-datepicker.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="{{asset('back/dist/js/inputmask/jquery.inputmask.min.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#phone').inputmask({
                mask: "([0]99)-999-99-99"
            })
        })
    </script>
</html>
<!-- end document-->
