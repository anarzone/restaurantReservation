@extends('admin.layouts.app')
@section('css')
<link rel="stylesheet" href="{{asset('back/dist/css/font-awesome/font-awesome-5.13.0.min.css')}}" />
<link rel="stylesheet" href="{{asset('back/dist/css/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css')}}" />
<style>
    .hall-plan-image {
        max-height: 100%;
        max-width: 100%;
        object-fit: cover;
    }

    .tableDiv {
        cursor: pointer;
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

    .imagemaps-wrapper {
        width: 1000px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <select class="custom-select mr-sm-2 " id="restaurants">
                            @foreach($restaurants as $rest)
                            @if(count($restaurants) == 1)
                                <option value="{{$rest->id}}" selected>{{$rest->name}}</option>
                            @else
                                <option value="{{$rest->id}}">{{$rest->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <ul class="nav nav-pills mr-sm-2" id="halls">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card" style="display:none;" id="map_card">
            <div class="row">
                <div class="col-md-9">
                    <div class="imagemaps-wrapper">
                        <img class="hall-plan-image" src="" draggable="false" usemap="#hallmap">
                        <map class="imagemaps" name="hallmap">
                        </map>
                        <div class="imagemaps-tables"></div>
                    </div>
                </div>
                <!--right segment-->
                <div class="col-md-3 right-option">
                    <div class="res-table-info text-center unselectable">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="current-reserve" data-toggle="tab" href="#currentReserve"
                                    role="tab">Rezervasiyalar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="new-reserve" data-toggle="tab" href="#newReserve"
                                    role="tab">Yeni rezervasiyalar</a>
                            </li>
                        </ul>

                        <img src="{{asset('images/loader.gif')}}" alt="loader" class="loader-res" style="
              position:absolute;
              left:0;
              right:0;
              top:0;
              bottom:0;
              margin:auto;
              display: none;
              ">

                        <div class="tab-content" id="myTabContent">
                            <!--currentReserve-->
                            <div class="tab-pane fade show active" id="currentReserve" role="tabpanel">

                                <div class="table-number"></div>

                                <div class="table-reservations">
                                    <div class="alert alert-warning default-alert-message">
                                        Masa seçilməyib
                                    </div>
                                </div>
                            </div>

                            <!--currentReserve end-->
                            <!--newReserve-->
                            <div class="tab-pane fade" id="newReserve" role="tabpanel">
                                <!-- .. load .. -->
                            </div>
                            <!--newReserve end-->
                        </div>

                    </div>
                </div>
            </div>
            <!--right segment end-->
        </div>
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
<div id="overlay"></div>
<input type="hidden" id="selectedReservation" value="0" />
<input type="hidden" id="selectedReservationDate" value="0" />
<input type="hidden" id="selectedTable" value="0" />
@endsection
@section('js')
<script type="text/javascript" src="{{asset('back/dist/js/moment/moment-2.26.0.js')}}"></script>
<script type="text/javascript" src="{{asset('back/dist/js/moment/moment-with-locales.js')}}"></script>
<script type="text/javascript"
    src="{{asset('back/dist/js/tempusdominus-bootstrap-4/tempusdominus-bootstrap-5.0.1.min.js')}}"></script>

<script>
 /**
 * ajaxSetup
 **/
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
  });



    /**
    * toastr option
    **/
    toastr.options = {
        "preventDuplicates": true,
        "positionClass": "toast-top-center",
    }

    /**
    * variables
    **/
    let selected_rest_id = $('#restaurants option:selected').val();
    let hall_id = null;

    /**
    * check restaurant
    **/
    if (!isNaN(selected_rest_id)) {
        getHalls(selected_rest_id)
    }

    /**
    * change restaurant
    **/
  $('#restaurants').on('change', function () {
    $('#map_card').hide();
    $('.reservation-info').empty()
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();
    $('.tableDiv').remove()

    rest_id = $(this).val();
    if (rest_id) {
      getHalls(rest_id)
    } else {
      $('#halls').empty();
    }
  })

  /**
    * getHalls
    **/
  function getHalls(rest_id) {
    $.ajax({
      type: 'GET',
      url: '/getHallsByRestId/' + rest_id,
      dataType: "json",
      success: function (result) {
        if (result.data) {
          $('#halls').empty().focus();
          let nav_status;

          $.each(result.data, function (key, val) {

            if(key === 0){
                nav_status='active';
                getPlan(this,val.id);
            }else{
                nav_status='';
            }

            $('#halls').append(
              '<li class="nav-item">\
              <a class="nav-link '+ nav_status +'" onClick="getPlan(this,'+ val.id +')" href="javascript:void(0)">' + val.name + '</a>\
              </li>'
            );
          });
        } else {
          $('#halls').empty();
        }
      }
    })
  }

  /**
    * getPlan
    **/
  function getPlan(obj, hall_id){
    // reset
    $('#halls .nav-link').removeClass('active');
    $(obj).addClass('active');
    $('.loader').show()
    $('#map_card').fadeIn();
    $('.reservation-info').empty();
    $('.imagemaps-tables').empty();
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();
    $('.tableDiv').remove();
    $('.table-number').empty();
    $('#selectedReservation').val(0);
    $('#selectedReservationDate').val(0);
    //hall deyishende masa secilmeyib yazilmir
    $('#selectedTable').val(0);
    $('.table-reservations').html(`<div class="alert alert-warning default-alert-message">
                                        Masa seçilməyib
                                    </div>`);


    if (hall_id) {
      $.ajax({
        type: 'GET',
        url: '/tables/getPlanByHallId/' + hall_id,
        dataType: 'json',
        success: function (result) {

          $('.loader').hide()
          if ($.trim(result.data.tables)) {

            let src = "{{url('storage/back/images')}}/" + result.data.plan_image
            $('.hall-plan-image').attr('src', src)

            $.each(result.data.tables, function (i, val) {
              let table_res_amount = result.data.table_have_reservations[val.table_id]

              let coords = val.coords.split(',')
              let busy_status;
              // table div parameters
              let top = coords[1] + 'px'
              let left = (parseInt(coords[0]) + 14) + 'px'
              let width = (coords[2] - coords[0]) + 'px'
              let height = (coords[3] - coords[1]) + 'px'

              let backgroundColor = 'grey'

              if(parseInt(val.table.status)){
                backgroundColor = '#ff0000'
              }else if(table_res_amount){
                backgroundColor = 'green'
              }

              let opacity = '.6'

              let tableDiv = $(`<div class="tableDiv text-right"
              data-table-id="${val.table_id}"
              data-table-status="${val.table.status}"
              data-table-res-amount="${table_res_amount}"
              onclick="showTableInfo('${val.table_id}')"
              ondblclick="busyDblTable(this)"
              style="position: absolute;
              top:${top};
              left:${left};
              width:${width};
              height:${height};
              background-color: ${backgroundColor};
              opacity: ${opacity};
              "
              >
              <span class="badge badge-pill badge-info">${table_res_amount}</span>
              </div>`)
              $('.imagemaps-tables').append(tableDiv)
            })
          } else {
              $('.imagemaps-tables').html(`
              <div class="alert alert-warning plan-alert">
              Plan tapılmadı
              </div>
              `)
            }
          }
        })
      }
    }


    function reservationDone(reservation_id){
        $("#openReserve").modal('hide');
        var selected_table = $('#selectedTable').val();
        var send_sms = $("#sendSMS").is(":checked") ? '1' : '0';

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
                data: {
                    status: 'done',
                    reservation_id,
                    send_sms
                },
                success: function (result) {
                    if($.trim(result.data)){
                        getHalls(selected_rest_id);
                        loadNewReservation();
                        showTableInfo(selected_table)
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


/**
 * selectTable
 **/
  function selectTable(table_id, showMessage = true){
    $('.preloader').show();
    $('.imagemaps-wrapper').removeClass('table-select-view');
    $('.imagemaps-tables').empty();

        if(table_id){
            var send_sms = $("#sendSMS").is(":checked") ? '1' : '0';
            var reservation_id = $('#selectedReservation').val();
            var res_date = $('#selectedReservationDate').val();

            $.ajax({
                type: 'PUT',
                url:  '/reservations/'+ reservation_id +'/update/table',
                data: {table_id, 'date': res_date, send_sms},
                success: function (result) {

                    $(`.tableDiv[data-table-id="${table_id}"]`).css('background-color', 'green')

                    if(showMessage){
                        $.trim(result.message) ? toastr.success(result.data) : toastr.error(result.data);
                    }

                //close table-select-view
                $('#overlay').empty();
                $('.imagemaps-wrapper').removeClass('table-select-view');
                $('#selectedReservation').val(0);
                $('#selectedReservationDate').val(0);
                getHalls(selected_rest_id)
                loadNewReservation()
                $('.preloader').hide();

                }
            })
        }

    }


/**
 * showTableInfo
 **/
  function showTableInfo(table_id) {

    var checkSelect = $('.imagemaps-wrapper').hasClass('table-select-view');

    $('#selectedTable').val(table_id);

    if(checkSelect){
        selectTable(table_id, true);
    }else{

    $(".loader-res").show();

    //tab change
    $('.nav-tabs a[href="#currentReserve"]').tab('show');

    $('.table-reservations').empty();
    $('.table-number').empty();

    if (table_id) {
      $.ajax({
        type: 'GET',
        url: '/reservations/table/' + table_id + '/all',
        success: function (result) {
          $(".loader-res").hide();

          if ($.trim(result.data.reservations)) {
            $.each(result.data.reservations, function (i, val) {
              $('.table-reservations').append(`
             <div class="reserve-box" onclick="getReserve(${val.id})" data-toggle="modal" data-target="#openReserve">
                <div class="reserve-box-time">
                    <span>${moment(val.datetime).format("HH:mm")}</span>
                </div>
                <div class="reserve-box-description">
                    <div class="reserve-box-title">
                        ${(val.res_lastname) ? val.res_lastname : ''}
                        ${(val.res_firstname) ? val.res_firstname : ''}
                    </div>
                    <div class="reserve-box-table-info">
                        Zal: <span>${val.halls.name}</span> |
                        Masa: <span>${table_id}</span> |
                        Adam sayı: <span>${val.res_people}</span>
                    </div>
                </div>
            </div>`);
            })

          } else {
            $('.table-reservations').html('<div class="alert alert-warning default-alert-message no_reservation_alert">\
            Rezervasiya yoxdur\
            </div>\
            ');
          }

          $('.table-number').append(`
            <h2>Masa # ${result.data.table.table_number}</h2>
            <button class="btn btn-success book-table" type="button"
                    data-table-id="${table_id}"
                    data-table-busy="${result.data.table.status}"
                 >
                    <span class="btn-label"><i class="fas fa-check"></i></span> Masanı məşğul et
                </button>
           </div>`)

        }
      })
        } //if table
    } //check select
  }


/**
 * busyDblTable
 **/
  function busyDblTable(obj){
    let _this = $(obj);
    let table_id = $(obj).data('table-id');
    let table_status = $(obj).data('table-busy');
    var checkSelect = $('.imagemaps-wrapper').hasClass('table-select-view');

    if(!checkSelect){
        busyTable(_this,table_id,table_status);
    }

  }

  /**
 * book-table
 **/
  $(document).on('click', '.book-table', function () {
    let _this = $(this)
    let table_id = $(this).data('table-id')
    let table_status = $(this).data('table-busy')
    busyTable(_this,table_id,table_status);
  });

/**
 * busyTable
 **/
  function busyTable(_this,table_id,table_status){

    if(table_id){
      $.ajax({
        type: 'POST',
        url:  '/tables/changeStatus',
        data: {table_id, table_status},
        success: (response) => {
          $(_this).data('table-busy', response.table.status)

          let currentTable = $(`.tableDiv[data-table-id="${table_id}"]`);
          let currentColor = currentTable.data('table-res-amount') ? 'green' : 'grey';

          if(parseInt(response.table.status) === 1){
            currentTable.css('background-color', '#ff0000')
          }else{
            currentTable.css('background-color', currentColor)
          }

        }
      })
    }
  }


  /**
 * loadNewReservation
 **/
  function loadNewReservation(){
    $('#newReserve').empty();
    $.ajax({
        url:'/reservations/getNewReservation',
        method:'GET',
        success:function(response){
            if(response.length >0){
            $.each(response, function( index, value ) {
            $('#newReserve').append(`
            <div class="reserve-box" onclick="getReserve(${value.id})" data-toggle="modal" data-target="#openReserve">
                        <div class="reserve-box-time">

                            <span>${moment(value.datetime).format("HH:mm")}</span>
                        </div>
                        <div class="reserve-box-description">
                            <div class="reserve-box-title">
                                ${(value.res_lastname) ? value.res_lastname : ''}
                                ${(value.res_firstname) ? value.res_firstname : ''}
                            </div>
                            <div class="reserve-box-table-info">
                                Zal: <span>${value.halls.name}</span> |
                                Adam sayı: <span>${value.res_people}</span>
                            </div>
                        </div>
                    </div>
            `);
                });
            }else{
                $('#newReserve').html(`<div class="alert alert-warning default-alert-message">
                    Yeni rezervasiya yoxdur
                    </div>
                    `);
            }
        },
        error:function(response){
            console.log('error',response);
        }
    });
  }

/**
 * Run
 **/
  loadNewReservation()

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
                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <input id="sendSMS" type="checkbox" name="sendSMS" value="1" ${(data.status === 0) ? 'checked':''}/>
                                <label for="sendSMS">SMS bildiriş göndər</label>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" onClick="reservationDone(${id})" class="btn btn-danger btn-block">Sonlandır</button>
                            </div>
                        <div class="col-md-6">
                            <button type="button" onClick="tableSelectView(${id},'${data.datetime}')" class="btn btn-success btn-block">Masa seç</button>
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
 * tableSelectView
 **/
  function tableSelectView(reserve_id, datetime){
      $('#openReserve').modal('hide');
      $('.imagemaps-wrapper').addClass('table-select-view');
      $('#selectedReservation').val(reserve_id);
      $('#selectedReservationDate').val(datetime);
      $('#overlay').append('<div class="modal-backdrop fade show"></div>')
  }

// ====

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
        var selected_table = $('#selectedTable').val();
        $('.reserve-detail-curdate').html(updateReserveDate);

        $.ajax({
                type: 'PUT',
                url: '/reservations/'+ reservation_id +'/update/date',
                data: {'date': updateReserveDate},
                success: function (result) {
                    if($.trim(result.message)){
                        toastr.success(result.message)
                        cancelDate();
                        showTableInfo(selected_table)
                    }
                }
            });
    }

</script>

@endsection
