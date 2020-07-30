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
            width: 915px;
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
                            <th scope="col">Qeyd</th>
                            <th scope="col">M-Qeyd</th>
                            <th scope="col">Tarix</th>
                            <th scope="col">Masa</th>
                            <th scope="col">Əməliyyat</th>
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
                                @switch($res->status)
                                    @case(0)
                                        @php $colors = 'bg-white text-secondary' @endphp
                                        @break
                                    @case(1)
                                        @php $colors = 'bg-success text-white' @endphp
                                        @break
                                @endswitch
                                <tr data-tr-id="{{$res->id}}" class="{{$colors}} reservation_row" >
                                    <th scope="row">{{$res->id}}</th>
                                    <td>
                                        {{$res->res_firstname}} {{$res->res_lastname}}
                                        @if(key_exists($res->customer_id, $reservations_by_customers)
                                            && $reservations_by_customers[$res->customer_id] > 1)
                                            <i class="text-primary fas fa-certificate"
                                               data-toggle="tooltip"
                                               title="Daimi müştəri"
                                               data-placement="top"
                                               data-original-title="Daimi müştəri"></i>
                                        @endif
                                    </td>
                                    <td>{{$res->res_phone}}</td>
                                    <td>{{$res->res_people}}</td>
                                    <td>{{$res->restaurants->name}}</td>
                                    <td>{{$res->halls->name}}</td>
                                    <td class="text-center">
                                        <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                                           data-original-title="{{trim($res->note) ? $res->note : 'Qeyd yoxdur'}}"></i>
                                    </td>
                                    <td class="text-center">
                                        @if(key_exists($res->customer_id, $notes_by_customers)
                                            && $notes_by_customers[$res->customer_id])
                                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title="{{$notes_by_customers[$res->customer_id]}}"></i>
                                        @else
                                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title=""
                                               data-original-title="{{'Qeyd yoxdur'}}"></i>
                                        @endif
                                    </td>
                                    <td>{{Carbon\Carbon::createFromDate($res->datetime)->format('Y/F/j - H:i')}}</td>
                                    <td id="{{$res->id}}">{{ $res->table ? $res->table->table_number : "" }}</td>
                                    <td>
                                        <div class="row">
                                            <button class="btn btn-sm btn-dark choose-table small-btn"
                                                    data-hall-id = "{{$res->halls->id}}"
                                                    data-hall-name = "{{$res->halls->name}}"
                                                    data-table-status = "{{ $res->table ? $res->table->status : null}}"
                                                    data-res-id = "{{$res->id}}"
                                                    data-table-id = "{{$res->table ? $res->table->id : null}}"
                                                    data-table-number= "{{$res->table ? $res->table->table_number : null}}"
                                                    data-res-date = "{{$res->datetime}}"
                                                    data-res-fullname = "{{$res->res_firstname}} {{$res->res_lastname}}"
                                                    data-toggle="modal"
                                                    data-target="#full-width-modal"
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

    <div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="hall-name">Masalar</h4>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Bağla</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row reservation-info"></div>
                        <hr>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="imagemaps-wrapper">
                                    <img class="hall-plan-image" src="" draggable="false" usemap="#hallmap">
                                    <map class="imagemaps" name="hallmap">
                                    </map>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="res-table-info text-center unselectable">
                                    <h4 class="bg-danger text-light">Rezervasiyalar</h4>
                                    <img src="{{asset('images/loader.gif')}}" alt="loader" class="loader-res"
                                         style="
                                            position:absolute;
                                            left:0;
                                            right:0;
                                            top:0;
                                            bottom:0;
                                            margin:auto;
                                            display: none;
                                    ">
                                    <span class="table-number"></span>
                                    <hr>
                                    <div class="table-reservations">
                                      <div class="alert alert-warning default-alert-message">
                                        Masa seçilməyib
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    $('.choose-table').on('click', function () {
        $('.loader').show()
        // make empty before initialized
        $('.plan-alert').remove()
        $('.hall-plan-image').attr('src', '')
        $('.imagemaps').empty()
        $('#tables').empty()
        $('.reservation-info').empty()

        let fullname = $(this).data('res-fullname');

        table_number = $(this).data('table-number');
        hall_id = $(this).data('hall-id');
        reservation_id = $(this).data('res-id');
        res_date = $(this).data('res-date');
        previous_table = reserved_table_id = $(this).data('table-id');


        if (table_number){
            showTableInfo(reserved_table_id, false)
        }

        $('#hall-name').text($(this).data('hall-name')+' - masalar');
        if(hall_id){
            $.ajax({
                type: 'GET',
                url:  '/tables/getPlanByHallId/' + hall_id,
                dataType: 'json',
                success: function (result) {
                    $('.loader').hide()
                    if($.trim(result.data.tables)){
                        let src = "{{url('storage/back/images')}}/" + result.data.plan_image
                        $('.hall-plan-image').attr('src', src)
                        $.each(result.data.tables, function (i, val) {

                            let table_status = result.data.table_have_reservations[val.table_id]

                            let coords = val.coords.split(',')

                            // table div parameters
                            let top    = coords[1]+'px'
                            let left   = (parseInt(coords[0])+16 ) +'px'
                            let width  = (coords[2] - coords[0])+'px'
                            let height = (coords[3] - coords[1])+'px'
                            let backgroundColor = table_status ? 'green' : 'grey'
                            let opacity = '.6'

                            let tableDiv = $(`<div class="tableDiv" data-table-id="${val.table_id}"
                                                    onclick="showTableInfo('${val.table_id}')" ondblclick="selectTable(${val.table_id})"
                                                    style="position: absolute;
                                                           top:${top};
                                                           left:${left};
                                                           width:${width};
                                                           height:${height};
                                                           background-color: ${backgroundColor};
                                                           opacity: ${opacity};
                                                          "
                                                >

                                                </div>`)

                            $('.imagemaps-wrapper').append(tableDiv)
                        })
                        let reservation_info_html = `
                            <div class="col-md-4 col-sm-4">
                                <h4>${fullname}</h4>
                            </div>
                            <div class="col-md-4 col-sm-4 table-number">
                                <h4>Masa #${table_number}</h4>
                            </div>
                            <div class="col-md-4 col-sm-4 res-info-wrapper">
                                <h4 class="res-d">
                                    ${moment(res_date).format("DD MMMM YYYY HH:mm")}
                                    <span class="badge badge-warning" onclick="editDate('${res_date}')" style="cursor: pointer">Dəyişdir</span>
                                </h4>
                            </div>
                        `;

                        $('.reservation-info').append(reservation_info_html)

                    }else{
                        let planAlert = $(`
                                <div class="alert alert-warning plan-alert">
                                    Plan tapılmadı
                                </div>
                            `)

                        $('.imagemaps-wrapper').prepend(planAlert)
                    }
                }
            })
        }
    })

    function selectTable(table_id, showMessage = true){
        if(table_id){
            let current_res_date = edited_date ?? res_date;

            $.ajax({
                type: 'PUT',
                url:  '/reservations/'+ reservation_id +'/update/table',
                data: {table_id, 'date': current_res_date},
                success: function (result) {
                    console.log(previous_table)

                    hasTableReservations(previous_table, changeTableBackgroundColor)

                    $(`[data-table-id=${table_id}]`).css('background-color', 'green')

                    previous_table = table_id;

                    if(showMessage){
                        $.trim(result.message) ? toastr.success(result.data) : toastr.error(result.data);
                    }
                }
            })
        }
    }

    function hasTableReservations(table_id, callback) {
        let hasReservation = 0;
        if(table_id){
            $.ajax({
                type: 'GET',
                url: '/tables/hasReservations/'+table_id,
                success: function (response) {
                    if($.trim(response.data)){
                        callback(table_id, response.data[table_id]);
                    }
                }
            })

            return hasReservation;
        }
    }

    function changeTableBackgroundColor(previous_table, has_reservation){
        if(!has_reservation){
            $(`[data-table-id=${previous_table}]`).css('background-color', 'grey')
        }
    }

    function showTableInfo(table_id, showMessage = true){
        // selectTable(table_id, showMessage)
        $('.loader-res').show()
        $('.table-reservations').empty();
        $('.table-number').empty();
        if(table_id){
            $.ajax({
                type: 'GET',
                url:  '/reservations/table/'+ table_id +'/all',
                success: function (result) {
                    $('.loader-res').hide()
                    if($.trim(result.data.reservations)){
                        $.each(result.data.reservations, function (i, val) {
                            let html = `
                                <h4>${moment(val.datetime).format("DD MMMM YYYY HH:mm")}</h4>
                            `
                            $('.table-reservations').append(html);
                        })
                        $('.table-number').append('Masa #' + result.data.table.table_number)
                    }else{
                        $('.table-reservations').html(`<div class="alert alert-warning default-alert-message no_reservation_alert">
                                                            Rezervasiya yoxdur
                                                        </div>
                                                     `);
                    }
                }
            })
        }
    }

    function editDate(date){
        let html = `
            <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="datetimepickerInfo" data-target-input="nearest">
                <input type="text" name="res_date_info" value="${date}" class="form-control datetimepicker-input" data-target="#datetimepickerInfo"/>
                <div class="input-group-append" data-target="#datetimepickerInfo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <span class="badge badge-success save-date" style="cursor: pointer">Yadda saxla</span>
            <span class="badge badge-secondary cancel-date" style="cursor: pointer">İmtina et</span>
        `

        $('.res-d').remove()
        $('.res-info-wrapper').append(html)

        $('#datetimepickerInfo').datetimepicker({
            format: 'YYYY-MM-Do, HH:mm',
        });

    }

    function cancelDate(date){
        let html = `
            <h4 class="res-d">
                ${moment(date).format("DD MMMM YYYY HH:mm")}
                <span class="badge badge-warning" onclick="editDate('${date}')" style="cursor: pointer">Edit</span>
            </h4>
        `;
        $('#datetimepickerInfo').remove()
        $('.save-date').remove()
        $('.cancel-date').remove()
        $('.res-info-wrapper').append(html)
    }

    $(document).on('change.datetimepicker', function (e) {
        edited_date = moment(e.date).format('YYYY-MM-DD HH:mm')
    })

    $(document).on('click', '.save-date', function () {
        if(edited_date && reservation_id){
            $.ajax({
                type: 'PUT',
                url: '/reservations/'+ reservation_id +'/update/date',
                data: {'date': edited_date},
                success: function (result) {
                    if($.trim(result.message)){
                        toastr.success(result.message)
                        cancelDate(edited_date)
                    }
                }
            });
        }
    })

    $(document).on('click', '.cancel-date', function () {
        cancelDate(res_date)
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
            if(!confirmed.value) return
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

    $('#full-width-modal').on('hidden.bs.modal', function() {
        location.reload();
    });
</script>
@endsection
