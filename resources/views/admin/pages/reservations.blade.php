@extends('admin.layouts.app')
@section('css')
    <!-- This page plugin CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        .hall-plan-image{
            max-height:100%;
            max-width:100%;
            object-fit: cover;
        }
        .tableDiv{
            cursor: pointer;
        }

        .small-btn{
            padding: .2rem;
        }

        .modal-full-width{
            width: 94.5%;
        }

        .unselectable {
            -moz-user-select: -moz-none;
            -khtml-user-select: none;
            -webkit-user-select: none;

            /*
            Introduced in IE 10.
            See http://ie.microsoft.com/testdrive/HTML5/msUserSelect/
            */
            -ms-user-select: none;
            user-select: none;
        }

        .imagemaps-wrapper{
            width: 1000px;
        }

    </style>
@endsection
@section('page-title', 'Rezervasiyalar')
@section('content')
    <!-- display errors -->
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
                        <div class="col-4">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="/manage/reservations/?status=1" class="btn btn-rounded btn-success btn-sm">Aktiv</a>
                                <a href="/manage/reservations/?status=0" class="btn btn-rounded btn-light btn-sm">Yeni</a>
                                <a href="/manage/reservations" class="btn btn-rounded btn-info btn-sm">Hamısı</a>
                            </div>
                        </div>
                        <div class="col-8">
                            <form class="form-inline row" action="{{route('manage.filter.date')}}">
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
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                      @if($reservations && count($reservations) < 1)
                        <tr>
                          <td colspan="10">
                            <div class="alert alert-warning">
                              Rezervasiyalar tapılmadı
                            </div>
                          </td>
                        </tr>
                      @else
                        @foreach($reservations as $res)
                            @if($res->restaurants)
                                <tr data-tr-id="{{$res->id}}" class="reservation_row" >
                                    <th scope="row">{{$res->id}}</th>
                                    <td>
                                        {{$res->res_firstname}} {{$res->res_lastname}}
                                    </td>
                                    <td>{{$res->res_phone}}</td>
                                    <td>{{$res->res_people}}</td>
                                    <td>{{$res->restaurants->name}}</td>
                                    <td>{{$res->halls->name}}</td>
                                    <td>{{Carbon\Carbon::createFromDate($res->datetime)->translatedFormat('j-F-Y - H:i')}}</td>
                                    <td id="{{$res->id}}">{{ $res->table ? $res->table->table_number : "" }}</td>
                                    <td>
                                        {!!($res->status === 0) ? '<span class="badge badge-warning">Gözləyir</span>' : ''!!}
                                        {!!($res->status === 1) ? '<span class="badge badge-success">Masa seçilib</span>' : ''!!}
                                        {!!($res->status === 2) ? '<span class="badge badge-info">Sona çatıb</span>' : ''!!}
                                    </td>
                                    <td>
                                            <button class="btn btn-sm btn-success"
                                            onclick="getReserve({{$res->id}})"
                                            data-toggle="modal" data-target="#openReserve"
                                            ><i class="fas fa-eye"></i> Ətraflı
                                        </button>

                                            <button class="btn btn-sm btn-warning"
                                            onClick="reservationDone({{$res->id}})"
                                            >
                                            <i class="fas fa-check-circle"></i> Sonlandır
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                      @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4"></div>
        <div class="col-6">
            {{$reservations->appends(['date_from'=> request('date_from'), 'date_to' => request('date_to')])->links()}}
        </div>
    </div>


<div id="openReserve" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="openReserve" aria-hidden="true"
style="display: none;">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Rezervasiya: #<span class="reserveDetailID"></span></h4>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Bağla</button>
            </div>
        </div>
        <div class="modal-body">
            <div class="card reserveDetailBody">
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
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

    toastr.options = {
        "preventDuplicates": true,
        "positionClass": "toast-top-center",
    }

    let reservation_id    = null;
    let selected_table_id = null;
    let reserved_table_id = null;
    let edited_date       = null;
    let res_date          = null;
    let table_number      = null;
    let previous_table    = null;



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



 /**
 * getReserve
 **/
 function getReserve(id){
    $(".reserveDetailID").html(id);
    $.ajax({
        url:'/reservations/getReserve/'+id,
        method:'GET',
        success:function(response){
            var data = response[0];

            $('.reserveDetailBody').html(`

            <div class="reserve-detail-title">
                ${(data.res_lastname) ? data.res_lastname : ''} ${(data.res_firstname) ? data.res_firstname : ''}
                <span>(ID: ${data.customer_id})</span>
            </div>
            <div class="reserve-detail-phone">
                ${data.res_phone}
            </div>
            <div class="reserve-detail-status">
                ${(data.status === 0) ? '<span class="badge badge-warning">Gözləyir</span>' : ''}
                ${(data.status === 1) ? '<span class="badge badge-success">Masa seçilib</span>' : ''}
                ${(data.status === 2) ? '<span class="badge badge-info">Sona çatıb</span>' : ''}
            </div>
                    <table class="table">
                        <tr>
                            <td>Rezervasiya Tarixi</td>
                            <td>
                                <span class="reserve-detail-date">
                                    <span class="reserve-detail-curdate">${data.datetime}</span>
                                    <span class="badge badge-warning" onclick="editDate(${id})" style="cursor: pointer">Dəyişdir</span>
                                </span>
                                <div class="reserve-detail-changedate" style="display:none">

                                <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="updatedatepicker" data-target-input="nearest">
                                    <input type="text" id="updateReserveDate" autocomplete="off" value="${data.datetime}" class="form-control datetimepicker-input" data-target="#updatedatepicker"/>
                                    <div class="input-group-append" data-target="#updatedatepicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>

                                <span class="badge badge-success" onclick="updateDate(${id})" style="cursor: pointer">Yadda saxla</span>
                                <span onclick="cancelDate()" class="badge badge-secondary" style="cursor: pointer">İmtina et</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Restoran</td> <td>${(data.restaurants) ? data.restaurants.name : ''}</td>
                        </tr>
                        <tr>
                            <td>Zal</td> <td>${(data.halls) ? data.halls.name : ''}</td>
                        </tr>
                        <tr>
                            <td>Adam sayı</td> <td>${data.res_people}</td>
                        </tr>
                        <tr>
                            <td>Qeyd</td> <td>${data.note}</td>
                        </tr>
                        <tr>
                            <td>Yaranma Tarixi</td> <td>${data.created_at}</td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" onClick="reservationDone(${id})" class="btn btn-danger btn-block">Sonlandır</button>
                            </div>
                    </div>
                    `);


    /**
    * datetimepicker
    **/
    $('#updatedatepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });

        },
        error:function(response){
            console.log('error',response);
        }
    });

  }


/**
 * editDate
 **/
 function editDate(id){
      $('.reserve-detail-date').hide();
      $('.reserve-detail-changedate').show();
    }
/**
 * cancelDate
 **/
    function cancelDate(){
        $('.reserve-detail-changedate').hide();
        $('.reserve-detail-date').show();
    }

/**
 * updateDate
 **/
    function updateDate(reservation_id){
        var updateReserveDate = moment($('#updateReserveDate').val()).format('YYYY-MM-DD HH:mm');
        $('.reserve-detail-curdate').html(updateReserveDate);
        $.ajax({
                type: 'PUT',
                url: '/reservations/'+ reservation_id +'/update/date',
                data: {'date': updateReserveDate},
                success: function (result) {
                    if($.trim(result.message)){
                        toastr.success(result.message)
                        cancelDate();
                        location.reload();
                    }
                }
            });
    }

    function reservationDone(reservation_id){
        $("#openReserve").modal('hide');
        Swal.fire({
                title: "Rezervasiya sonlandırılasın?",
                showCancelButton: true,
                confirmButtonColor: "#dd6b55",
                confirmButtonText: "Bəli",
                cancelButtonText: 'Xeyir',
        }).then((confirmed)=>{
            if(!confirmed.value) return
            $.ajax({
                type: 'POST',
                url:  '/reservations/status/update',
                data: {status: 'done', reservation_id},
                success: function (result) {
                    if($.trim(result.data)){
                        location.reload();
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
    }
</script>
@endsection
