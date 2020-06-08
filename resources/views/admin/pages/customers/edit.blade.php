@extends('admin.layouts.app')
@section('page-title', 'Müştəri redaktə et')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        form .error {
            color: #ff0000;
            line-height: normal;
            display: inline;
        }

        .bootstrap-datetimepicker-widget{
            z-index: 9999999999999999999999 !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="mx-auto col-md-8 col-sm-12">
            <!-- form user info -->
            <div class="card">
                <div class="card-title text-right">
                    <button class="btn btn-sm btn-danger m-1 delete_customer">Sil</button>
                </div>
                <div class="card-body">
                    <form class="form-reservation" action="{{route('admin.customer.update', $customer->id)}}" role="form"
                          autocomplete="off" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Ad</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="firstname" id="firstname" type="text"
                                       value="{{$customer->firstname}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Soyad</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="lastname" id="lastname" type="text"
                                       value="{{$customer->lastname}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Telefon</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="phone" id="phone" type="text"
                                       value="{{$customer->phone}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Doğum tarixi</label>
                            <div class="col-lg-9 input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" name="birthdate" class="form-control datetimepicker-input"
                                       data-target="#datetimepicker2"
                                       value="{{\Carbon\Carbon::parse($customer->birthdate)->toDateString()}}" />
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Qeyd</label>
                            <div class="col-lg-9">
                                <textarea class="form-control" id="note" name="note" rows="3">{{$customer->note}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <input type="submit" name="submit" class="btn btn-success" value="Yadda saxla">
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                displayMessage("{{ $error }}", 'error')
            @endforeach
        @endif

        $('#datetimepicker2').datetimepicker({
            locale: 'en',
            format: 'MMMM Do YYYY'
        });

        $('.delete_customer').on('click', function () {
            deleteEl('', '{{route('admin.customer.destroy', $customer->id)}}', 'Silmək istədiynizdən əminsiniz?',
                         '/admin/customers')
        })
    </script>
@endsection
