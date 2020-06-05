<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Required meta tags-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="NetGroup">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Title Page-->
        <title>Restoran Rezervasiya</title>

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
                        <div class="country-box">
                            <span>+994 </span>
                        </div>
                        <input type="text" onpaste="event.preventDefault()" max="12" id="ssn" name="phone" class="number-input form-inp" onblur="validationForm(this,3)">
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
                            <div class="custom-radio">
                                <label class="" for="amburan_beach_club">
                                    <input type="radio" name="radio_inp" value="{{$res->id}}" hidden id="">
                                </label>
                                <p class="title-custom-radio">
                                    {{ $res->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="custom-select-item">
                <p class="half-label">HALL</p>
                <div class="select-btn">
                    <p class="title">Masalar</p>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        toastr.options = {
            "preventDuplicates": true
        }

        @if(count($errors) > 0)
        @foreach($errors->all() as $error)
        toastr.error("{{ $error }}");
            @endforeach
            @endif

        let fd = new FormData();

        $('#firstname').on('change paste keyup', function () {
            fd.append('firstname', $(this).val())
        })
        $('#lastname').on('change paste keyup', function () {
            fd.append('lastname', $(this).val())
        })
        $('#phone').on('change paste keyup', function () {
            fd.append('phone', $(this).val())
        })
        $('#note').on('change paste keyup', function () {
            fd.append('note', $(this).val())
        })
        $('.restaurants').on('change', function () {
            let rest_id = $(this).val();
            fd.append('restaurant_id', rest_id);
            if(rest_id){
                $.ajax({
                    type: 'GET',
                    url: '/getHallsByRestId/' + rest_id,
                    dataType: "json",
                    success: function (result) {
                        if(result.data){
                            $('.halls').empty().focus();
                            $('.halls').append('<option disabled selected value> -- Zal seçin -- </option>');
                            $.each(result.data, function(key, val){
                                $('.halls').append(
                                    '<option value="'+ val.id + '"> ' + val.name + '</option>'
                                );
                            });
                        }else{
                            $('.halls').empty();
                        }
                    }
                })
            }else{
                $('.halls').empty();
            }

        })
        $('.halls').on('change paste keyup', function () {
            fd.append('hall_id', $(this).val())
        })
        $('#people').on('change paste keyup', function () {
            fd.append('people', $(this).val())
        })

        $(function () {
            $('.form-reservation').validate({
                    rules: {
                        firstname: {
                            required: true,
                            minlength: 2
                        },
                        lastname:  {
                            required: true,
                            minlength: 2
                        },
                        phone: {
                            required: true
                        },
                        restaurants: "required",
                        halls: "required",
                        people: "required",
                        // reservation_date: "required"
                    },
                    messages: {
                        firstname: {
                            required: "Adınızı yazın",
                            minlength: jQuery.validator.format('Ən az {0} hərf daxil edilməlidir')
                        },
                        lastname:  {
                            required: "Soyadınızı yazın",
                            minlength: jQuery.validator.format('Ən az {0} hərf daxil edilməlidir')
                        },
                        phone: {
                            required: "Telefon nömrənizi qeyd edin"
                        },
                        restaurants: "Restoran seçin",
                        halls: "Zal seçin",
                        people: "Qonaq sayını yazın"
                    },
                    submitHandler: function(form){
                        $.ajax({
                            type: 'POST',
                            url: '/check_table',
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function (result) {
                                if($.trim(result.data)){
                                    toastr.success('Təşəkkür edirik, tezliklə sizinlə əlaqə saxlayacağıq', 'Qeydə alındı')
                                    document.forms[0].reset();
                                }
                            }
                        })
                    }
                }
            );

            $('#datetimepicker2').datetimepicker({
                locale: 'en',
                format: 'dddd, MMMM Do YYYY, HH:mm'
            });

            $('#datetimepicker2').on("change.datetimepicker", function (e) {
                fd.append('reservation_date',moment(e.date).format('YYYY-MM-DD HH:mm'));
            });
        });

    </script>
</html>
<!-- end document-->
