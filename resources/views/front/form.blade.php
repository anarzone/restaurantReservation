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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
              integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
              integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
        <link rel="stylesheet" href="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    </head>

<body>
<style>
    form .error {
        color: #ff0000;
        line-height: normal;
        display: inline;
    }
</style>
<div class="container pt-1">
    <div class="container py-3">
        <div class="row">
            <div class="mx-auto col-sm-6">
                <!-- form user info -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Rezervasiya</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-reservation" role="form" autocomplete="off">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Ad</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="firstname" id="firstname" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Soyad</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="lastname" id="lastname" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Telefon</label>
                                <div class="col-lg-9">
                                    <input class="form-control" name="phone" id="phone" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Restoran</label>
                                <div class="col-lg-9">
                                    <select name="restaurants" class="form-control restaurants" size="0">
                                        <option disabled selected value> -- Restoran seçin -- </option>
                                        @foreach($restaurants as $data)
                                            <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Zal</label>
                                <div class="col-lg-9">
                                    <select name="halls" class="form-control halls" size="0">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Adamlar</label>
                                <div class="col-lg-9">
                                    <input class="form-control" id="people" name="people" type="number" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Tarix</label>
                                <div class="col-lg-9 input-group date" id="datetimepicker2" data-target-input="nearest">
                                    <input type="text" name="reservation_date" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-9">
                                    <input type="submit" name="submit" class="btn btn-outline-primary check_table" value="Yoxla">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /form user info -->
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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
</body>

</html>
<!-- end document-->
