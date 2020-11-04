@extends('admin.layouts.app')
@section('page-title', 'Rezervasiya Formu')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        form .error {
            color: #ff0000;
            line-height: normal;
            display: inline;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="mx-auto col-sm-6">
            <!-- form user info -->
            <div class="card">
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
                            <label class="col-lg-3 col-form-label form-control-label">Qeyd</label>
                            <div class="col-lg-9">
                                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <input type="submit" name="submit" class="btn btn-outline-primary check_table" value="Göndər">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /form user info -->
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{asset('back/dist/js/moment/moment-2.26.0.js')}}"></script>
    <<script type="text/javascript" src="{{asset('back/dist/js/moment/moment-with-locales.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{asset('back/dist/js/tempusdominus-bootstrap-4/tempusdominus-bootstrap-5.0.1.min.js')}}"></script>
    <script src="{{asset('back/dist/js/inputmask/jquery.inputmask.min.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                displayMessage('{{$error}}', 'danger')
            @endforeach
        @endif

        $(document).ready(function () {
            $('#phone').inputmask({
                mask: "([0]99)-999-99-99"
            })
        })

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
                            $('.halls').empty()
                                        .focus()
                                        .append('<option disabled selected value> -- Zal seçin -- </option>');
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
                            required: false,
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
                            required: "Ad daxil edilməyib",
                            minlength: jQuery.validator.format('Ən az {0} hərf daxil edilməlidir')
                        },
                        lastname:  {
                            required: "Soyad daxil edilməyib",
                            minlength: jQuery.validator.format('Ən az {0} hərf daxil edilməlidir')
                        },
                        phone: {
                            required: "Telefon nömrəsi daxil edilməyib"
                        },
                        restaurants: "Restoran seçilməyib",
                        halls: "Zal seçilməyib",
                        people: "Qonaq sayı yazılmayıb"
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
                                    toastr.success('Qeydə alındı')
                                    document.forms[1].reset();
                                }
                            }
                        })
                    }
                }
            );

            $('#datetimepicker2').datetimepicker({
                locale: 'en',
                format: 'MMMM Do YYYY, HH:mm'
            });

            $('#datetimepicker2').on("change.datetimepicker", function (e) {
                fd.append('reservation_date',moment(e.date).format('YYYY-MM-DD HH:mm'));
            });
        });

    </script>
@endsection
