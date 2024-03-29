@extends('admin.layouts.app')
@section('css')
    <!-- This page plugin CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@endsection
@section('page-title', 'Rezervasiyalar arxivi')
@section('content')
    <!-- basic table -->
    @if ($errors->any())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <form class="form-inline row" action="{{route('manage.filter.date')}}" method="GET">
{{--                                @csrf--}}
                                <input type="hidden" name="archive" value="true">
                                <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="datetimepicker" data-target-input="nearest">
                                    <input autocomplete="off" type="text" name="date_from" value="{{request('date_from')}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="datetimepicker2" data-target-input="nearest">
                                    <input autocomplete="off" type="text" name="date_to" value="{{request('date_to')}}" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-info mb-2">Axtar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Ad soyad</th>
                        <th scope="col">Telefon</th>
                        <th scope="col">Qonaq</th>
                        <th scope="col">Restoran</th>
                        <th scope="col">Zal</th>
                        <th scope="col">Tarix</th>
                        <th scope="col">Masa</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($reservations && count($reservations) > 0)
                        @foreach($reservations as $res)
                            @if($res->restaurants)
                                <tr data-tr-id="{{$res->id}}">
                                    <th scope="row">{{$res->id}}</th>
                                    <td>{{$res->res_firstname}} {{$res->res_lastname}}</td>
                                    <td>{{$res->res_phone}}</td>
                                    <td>{{$res->res_people}}</td>
                                    <td>{{$res->restaurants->name}}</td>
                                    <td>{{$res->halls->name}}</td>
                                    <td>{{date('Y/m/d -  H:m', strtotime($res->datetime)) }}</td>
                                    <td>{{ $res->table ? $res->table->table_number : "" }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">
                                <div class="alert alert-warning">
                                    Arxiv rezervasiyalar tapılmadı
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4"></div>
        <div class="col-6">
            {{$reservations->appends(['archive' => 'true', 'date_from'=> request('date_from'), 'date_to' => request('date_to')])->links()}}
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('back/dist/js/moment/moment-2.26.0.js')}}"></script>
    <<script type="text/javascript" src="{{asset('back/dist/js/moment/moment-with-locales.js')}}"></script>
    <script type="text/javascript" src="{{asset('back/dist/js/tempusdominus-bootstrap-4/tempusdominus-bootstrap-5.0.1.min.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });


        $(function () {
            $('#datetimepicker').datetimepicker({
                format: 'MMMM Do YYYY, HH:mm'
            });
            $('#datetimepicker2').datetimepicker({
                format: 'MMMM Do YYYY, HH:mm',
                useCurrent: false
            });
            $("#datetimepicker").on("change.datetimepicker", function (e) {
                $('#datetimepicker2').datetimepicker('minDate', e.date);
            });
            $("#datetimepicker2").on("change.datetimepicker", function (e) {
                $('#datetimepicker').datetimepicker('maxDate', e.date);
            });
        });


    </script>
@endsection
