@extends('admin.layouts.app')
@section('css')
    <!-- This page plugin CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@endsection
@section('page-title', 'Rezervasiyalar')
@section('content')
    <!-- basic table -->
    @if ($errors->any())
        <div class="row">
            <div class="col-4"></div>
            <div class="col-8">
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
                        <div class="col-4">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="/admin/reservations/?status=1" class="btn btn-rounded btn-success btn-sm">Aktiv</a>
                                <a href="/admin/reservations/?status=0" class="btn btn-rounded btn-light btn-sm">Yeni</a>
                                <a href="/admin/reservations" class="btn btn-rounded btn-info btn-sm">Hamısı</a>
                            </div>
                        </div>
                        <div class="col-8">
                            <form class="form-inline row" action="{{route('admin.filter.date')}}">
                                <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="datetimepicker" data-target-input="nearest">
                                    <input type="text" name="date_from" value="{{request('date_from')}}" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                                    <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="datetimepicker2" data-target-input="nearest">
                                    <input type="text" name="date_to" value="{{request('date_to')}}" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
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
                        <th scope="col">Əməliyyat</th>
                    </tr>
                    </thead>
                    <tbody style='font-family: "Roboto", "Arial", "Helvetica Neue", sans-serif'>
                        @foreach($reservations as $res)
                            @if($res->restaurants)
                                @switch($res->status)
                                @case(0)
                                    @php $colors = 'bg-white text-secondary' @endphp
                                    @break
                                @case(1)
                                    @php $colors = 'bg-success text-white' @endphp
                                    @break
                            @endswitch
                                <tr data-tr-id="{{$res->id}}" class="{{$colors}}">
                                    <th scope="row">{{$res->id}}</th>
                                    <td>{{$res->res_firstname}} {{$res->res_lastname}}</td>
                                    <td>{{$res->res_phone}}</td>
                                    <td>{{$res->res_people}}</td>
                                    <td>{{$res->restaurants->name}}</td>
                                    <td>{{$res->halls->name}}</td>
                                    <td>{{date('Y/m/d -  H:m', strtotime($res->datetime)) }}</td>
                                    <td>{{ $res->table ? $res->table->table_number : "" }}</td>
                                    <td>
                                        <div class="row">
                                            <button class="btn btn-sm btn-dark choose-table"
                                                    data-hall-id = "{{$res->halls->id}}"
                                                    data-hall-name = "{{$res->halls->name}}"
                                                    data-table-status = "{{ $res->table ? $res->table->status : null}}"
                                                    data-res-id = "{{$res->id}}"
                                                    data-table-id = "{{$res->table ? $res->table->id : null}}"
                                                    data-toggle="modal"
                                                    data-target="#bs-example-modal-lg"
                                            >Masa seç
                                            </button>
                                            <button class="btn btn-sm btn-warning reservation-done"
                                                data-reservation-id = "{{$res->id}}"
                                                data-table-id = "{{$res->table ? $res->table->id : null}}"
                                            ><i class="fas fa-check-circle"></i></button>
                                        </div>

                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4"></div>
        <div class="col-6">
            {{$reservations->appends(['date_from'=> request('date_from'), 'date_to' => request('date_to')])->links()}}
        </div>
    </div>
    <!--  Modal content for the above example -->
    <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <h4 class="modal-title" id="hall-name">Masalar</h4>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-outline-primary btn-sm save-table" data-dismiss="modal">Yadda saxla</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Bağla</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body" id="tables">

                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    let reservation_id = null;
    let selected_table_id = null;
    let reserved_table_id = null;

    $('.choose-table').on('click', function () {
        $('#tables').empty()
        hall_id = $(this).data('hall-id');
        reservation_id = $(this).data('res-id');

        let table_status = $(this).data('table-status');
        reserved_table_id = $(this).data('table-id');
        $('#hall-name').text($(this).data('hall-name')+' - masalar');
        if(hall_id){
            $.ajax({
                type: 'GET',
                url:  '/tables/get_by_hall_id/' + hall_id,
                dataType: 'json',
                success: function (result) {
                    if($.trim(result.data)){
                        let tables = $('#tables');
                        let html = '';
                        html += '<div class="row">';
                        $.each(result.data, function(key, val){
                            let bgColorStatus = '';
                            if(parseInt(val.status) === parseInt(table_status) && parseInt(val.id) === parseInt(reserved_table_id)) {
                                    bgColorStatus = 'bg-danger'
                            }else if(parseInt(val.status) === 1) {
                                bgColorStatus = 'bg-secondary'
                            }else{
                                bgColorStatus = 'bg-success'
                            }
                            html += `
                                <div class="col-sm-3">
                                    <div class="card mt-4 ${bgColorStatus} text-light table-properties"
                                         data-status="${val.status}"
                                         data-id="${ val.id }">
                                        <div class="card-body text-center input-properties">
                                            <h4>Masa #${val.table_number}</h4>
                                            <h5>Tutum ${val.people_amount} </h5>
                                        </div>
                                    </div>
                                </div>
                            `
                        })
                        html += '</div';
                        tables.append(html)
                    }
                }
            })
        }
    })

    $(document).on('click', '.table-properties', function () {
        let table_id = $(this).data('id')
        let table_status = $(this).data('status')
        let table_cards = document.getElementsByClassName('table-properties');

        for(let t = 0; t < table_cards.length; t++){
            if(table_status === 0){
                if(table_id === parseInt(table_cards[t].getAttribute('data-id'))){
                    selected_table_id = table_id;

                    $(this).addClass('bg-danger')
                    $(this).removeClass('bg-success');
                }else if(0 === parseInt(table_cards[t].getAttribute('data-status'))){
                    $(table_cards[t].classList.add('bg-success'));
                    $(table_cards[t].classList.remove('bg-danger'))
                }
            }

        }
    })

    $('.save-table').on('click', function () {
        if(selected_table_id){
            console.log(selected_table_id)
            $.ajax({
                type: 'POST',
                url: '/reservations/update',
                data: {table_id: selected_table_id, reservation_id, reserved_table_id},
                success: function (result) {
                    if($.trim(result.data)){
                        location.reload();
                    }
                }
            })
        }
    })

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

    $('.reservation-done').on('click', function () {
        let reservation_id = $(this).data('reservation-id')
        let table_id = $(this).data('table-id')
        Swal.fire({
                title: "Arxivə göndərilsin?",
                showCancelButton: true,
                confirmButtonColor: "#dd6b55",
                confirmButtonText: "Göndər!",
                cancelButtonText: 'Imtina et',
        }).then((confirmed)=>{
            if(!confirmed.value || !table_id) return
            $.ajax({
                type: 'POST',
                url:  '/reservations/status/update',
                data: {status: 'done', reservation_id, table_id},
                success: function (result) {
                    if($.trim(result.data)){
                        location.reload()
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Xəta baş verdi!',
                    })
                }
            })
        })


    })
</script>
@endsection
