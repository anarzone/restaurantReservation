@extends('admin.layouts.app')
@section('css')
  <link rel="stylesheet" href="{{asset('back/dist/css/font-awesome/font-awesome-5.13.0.min.css')}}"
  integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous"/>
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
  </style>
@endsection

@section('content')
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <label class="mr-sm-2">Restoran</label>
              <select class="custom-select mr-sm-2 " id="restaurants">
                <option disabled selected> -- Restoran seç</option>
                @foreach($restaurants as $rest)
                  @if(count($restaurants) == 1)
                    <option value="{{$rest->id}}" selected>{{$rest->name}}</option>
                  @else
                    <option value="{{$rest->id}}">{{$rest->name}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-sm-4">
              <label class="mr-sm-2">Zal</label>
              <select class="custom-select mr-sm-2" id="halls">
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <div class="card" style="display:none;" id="map_card">
        <div class="row">
          <div class="col-md-10">
            <div class="imagemaps-wrapper">
              <img class="hall-plan-image" src="" draggable="false" usemap="#hallmap">
              <map class="imagemaps" name="hallmap">
              </map>
            </div>
          </div>

          <div class="col-md-2 right-option">
            <div class="res-table-info text-center unselectable">
              <h4 class="bg-danger text-light">Rezervasiyalar</h4>
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
  </div>

  <div id="quickReservationModal" class="modal fade unselectable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Masa rezerv et</h4>
          <button type="button" class="btn btn-sm btn-outline-dark" data-dismiss="modal" aria-hidden="true">Bağla</button>
        </div>
        <div class="modal-body">
          <div class="mb-2 mr-sm-2 input-group date col-xs-4" id="datetimepickerInfo" data-target-input="nearest">
            <input type="text" name="res_date_info" value="{{\Carbon\Carbon::now('Asia/Baku')->toDateTimeString()}}" class="form-control datetimepicker-input" data-target="#datetimepickerInfo"/>
            <div class="input-group-append" data-target="#datetimepickerInfo" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
          <hr>
          <div class="text-center">
            <button class="btn btn-sm btn-success save-reservation">Yadda saxla</button>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('js')
  <script type="text/javascript" src="{{asset('back/dist/js/moment/moment-2.26.0.js')}}"></script>
  <script type="text/javascript" src="{{asset('back/dist/js/moment/moment-with-locales.js')}}"></script>
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

  let rest_id = $('#restaurants option:selected').val();
  let hall_id = null;
  let table_with_date = {}

  if (!isNaN(rest_id)){
    getHalls(rest_id)
  }

  $('#restaurants').on('change', function () {
    $('.plan-alert').remove();
    $('#map_card').hide();
    $('.reservation-info').empty()
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();

    rest_id = $(this).val();
    if (rest_id) {
      getHalls(rest_id)
    } else {
      $('#halls').empty();
    }
  })

  function getHalls(rest_id) {
    $.ajax({
      type: 'GET',
      url: '/getHallsByRestId/' + rest_id,
      dataType: "json",
      success: function (result) {
        if (result.data) {
          $('#halls').empty().focus();
          $('#halls').append('<option disabled selected value> -- Zal seçin -- </option>');
          $.each(result.data, function (key, val) {
            $('#halls').append(
              '<option value="' + val.id + '"> ' + val.name + '</option>'
            );
          });
        } else {
          $('#halls').empty();
        }
      }
    })
  }

  $('#halls').on('change', function () {
    $('#map_card').fadeIn();
    $('.reservation-info').empty()
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();

    hall_id = $(this).val();
    if (hall_id) {
      $.ajax({
        type: 'GET',
        url: '/tables/getPlanByHallId/' + hall_id,
        dataType: 'json',
        success: function (result) {
          if ($.trim(result.data.tables)) {
            $('.plan-alert').remove();

            let src = "{{url('storage/back/images')}}/" + result.data.plan_image
            $('.hall-plan-image').attr('src', src)

            $.each(result.data.tables, function (i, val) {
              let mapDiv = $(`<area   shape="rect"
              coords="${val.coords}"
              onclick="showTableInfo('${val.table_id}'); "
              >
              `)

              $('.imagemaps').append(mapDiv)

              let table_status = result.data.table_have_reservations[val.table_id]

              let coords = val.coords.split(',')

              // table div parameters
              let top = coords[1] + 'px'
              let left = (parseInt(coords[0]) + 14) + 'px'
              let width = (coords[2] - coords[0]) + 'px'
              let height = (coords[3] - coords[1]) + 'px'
              let backgroundColor = table_status ? 'green' : 'grey'
              let opacity = '.6'

              let tableDiv = $(`<div class="tableDiv"
              data-table-id="${val.table_id}"
              data-table-status="${table_status}"
              data-target="#quickReservationModal"
              onclick="showTableInfo('${val.table_id}')" ondblclick="reserveGuest('${val.table_id}')"
              style="position: absolute;
              top:${top};
              left:${left};
              width:${width};
              height:${height};
              background-color: ${backgroundColor};
              opacity: ${opacity};
              ">

              </div>`)
              $('.imagemaps-wrapper').append(tableDiv)
            })
          } else {
            $('.tableDiv').remove()
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

    $(document).on('change.datetimepicker', function (e) {
      table_with_date.date = moment(e.date).format('YYYY-MM-DD HH:mm')
    })

    $('.save-reservation').on('click', function () {
      if (!$.isEmptyObject(table_with_date)) {
        saveReservation(table_with_date.table_id, table_with_date.date)
      }
    })

    $(document).on('click', '.reserveBtn', function () {
      console.log(table_with_date)
      $('#quickReservationModal').modal('toggle')
    })

    //fix it
    function showTableInfo(table_id) {
      table_with_date = {table_id, date: $('[name="res_date_info"]').val()}

      $('.table-reservations').empty();
      $('.table-number').empty();
      if (table_id) {
        $.ajax({
          type: 'GET',
          url: '/reservations/table/' + table_id + '/all',
          success: function (result) {
            if ($.trim(result.data.reservations)) {
              $.each(result.data.reservations, function (i, val) {
                let html = `
                <h4><span>${i + 1}. </span> ${val.datetime}</h4>
                `
                $('.table-reservations').append(html);
              })
              $('.table-number').append('Masa #' + result.data.table.table_number)

              let reservationBtn = $(`
                <button class="btn btn-sm btn-info reserveBtn">Rezerv et</button>
                `);

                $('.table-reservations').append(reservationBtn)
              }else{
                $('.table-number').append('Masa #' + result.data.table.table_number)
                $('.table-reservations').html('<div class="alert alert-warning default-alert-message">\
                Rezervasiya yoxdur\
                </div>\
                <button class="btn btn-sm btn-info reserveBtn">Rezerv et</button>\
                ');
              }
            }
          })
        }
      }

      function reserveGuest(table_id) {
        table_with_date = {table_id, date: $('[name="res_date_info"]').val() }

        $('#quickReservationModal').modal('toggle')
      }

      function saveReservation(table_id, date){
        $.ajax({
          type: 'POST',
          url: '{{route('reservation.quick.reservation')}}',
          data: {table_id, date, rest_id, hall_id},
          success: function (response) {
            $(`[data-table-id=${table_id}]`).css('background-color', 'green')
            $.trim(response.message) ? toastr.success(response.data) : toastr.error(response.data);
          }
        })
      }

      $('#datetimepickerInfo').datetimepicker({
        format: 'YYYY-MM-Do, HH:mm',
      });
      </script>

    @endsection
