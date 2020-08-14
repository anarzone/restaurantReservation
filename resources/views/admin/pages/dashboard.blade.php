@extends('admin.layouts.app')
@section('css')
  <link rel="stylesheet" href="{{asset('back/dist/css/font-awesome/font-awesome-5.13.0.min.css')}}"/>
  <link rel="stylesheet" href="{{asset('back/dist/css/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css')}}"/>
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

  .imagemaps-wrapper{
    width: 915px;
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
  </div>

@endsection
@section('js')
  <script type="text/javascript" src="{{asset('back/dist/js/moment/moment-2.26.0.js')}}"></script>
  <script type="text/javascript" src="{{asset('back/dist/js/moment/moment-with-locales.js')}}"></script>
  <script type="text/javascript"
  src="{{asset('back/dist/js/tempusdominus-bootstrap-4/tempusdominus-bootstrap-5.0.1.min.js')}}"></script>
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

  if (!isNaN(rest_id)) {
    getHalls(rest_id)
  }

  $('#restaurants').on('change', function () {
    $('.plan-alert').remove();
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
    $('.loader').show()
    $('#map_card').fadeIn();
    $('.reservation-info').empty()
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();
    $('.tableDiv').remove()

    hall_id = $(this).val();
    if (hall_id) {
      $.ajax({
        type: 'GET',
        url: '/tables/getPlanByHallId/' + hall_id,
        dataType: 'json',
        success: function (result) {
          $('.loader').hide()
          if ($.trim(result.data.tables)) {

            $('.plan-alert').remove();

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
              onclick="showTableInfo('${val.table_id}')" ondblclick="busyDblTable(this)"
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


    $(document).on('click', '.reservation_done', function () {
      let reservation_id = $(this).data('reservation-id')
      let table_id = $(this).data('table-id')

      let el = $(this).parent();

      deleteEl({status: 'done', reservation_id, table_id},
      '/reservations/status/update',
      'Arxivə göndərilsin?',
      '',
      el,
      'POST',
      decreaseTableCounter
    )
  })

  function decreaseTableCounter(table_id){
    let currentTable = $(`.tableDiv[data-table-id="${table_id}"]`);
    currentTable.attr('data-table-res-amount', parseInt(currentTable.attr('data-table-res-amount')) - 1)

    let table_res_amount = parseInt(currentTable.attr('data-table-res-amount'));
    let backgroundColor = table_res_amount ? 'green' : 'grey';

    currentTable.css('background-color', backgroundColor)

    let countEl = currentTable.children()
    countEl.text(parseInt(countEl.text()) - 1)
  }

  //fix it
  function showTableInfo(table_id) {
    $(".loader-res").show()
    table_with_date = {table_id, date: $('[name="res_date_info"]').val()}

    $('.table-reservations').empty();
    $('.table-number').empty();

    if (table_id) {
      $.ajax({
        type: 'GET',
        url: '/reservations/table/' + table_id + '/all',
        success: function (result) {
          $(".loader-res").hide()
          let busyTableBtn = $(`<button class="btn btn-sm btn-secondary waves-effect waves-light book-table" type="button"
          data-table-id="${table_id}"
          data-table-busy="${result.data.table.status}"
          >
          <span class="btn-label"><i class="fas fa-check"></i></span> Masanı məşğul et
          </button><br>`)

          if ($.trim(result.data.reservations)) {
            $.each(result.data.reservations, function (i, val) {
              let html = `
              <h4>
              <span class="badge badge-pill badge-danger reservation_done"
              style="cursor: pointer"
              data-table-id="${table_id}"
              data-reservation-id="${val.id}"
              >x</span>
              ${moment(val.datetime).format("DD MMMM YYYY HH:mm")}
              </h4>
              `
              $('.table-reservations').prepend(html);
            })

            $('.table-number').append(busyTableBtn)
            $('.table-number').append('Masa #' + result.data.table.table_number)

          } else {
            $('.table-number').append(busyTableBtn)
            $('.table-number').append('Masa #' + result.data.table.table_number)
            $('.table-reservations').html('<div class="alert alert-warning default-alert-message no_reservation_alert">\
            Rezervasiya yoxdur\
            </div>\
            ');
          }
        }
      })
    }
  }


  function saveReservation(table_id, date) {
    $.ajax({
      type: 'POST',
      url: '{{route('reservation.quick.reservation')}}',
      data: {table_id, date, rest_id, hall_id},
      success: function (response) {
        let currentTable = $(`.tableDiv[data-table-id="${table_id}"]`);
        let countEl = currentTable.children()

        currentTable.css('background-color', 'green')

        if($.trim(response.message)){
          $('.no_reservation_alert').remove()

          countEl.text(parseInt(countEl.text()) + 1)
          currentTable.attr('data-table-res-amount', parseInt(currentTable.attr('data-table-res-amount')) + 1)

          let html = `
          <h4>
          <span class="badge badge-pill badge-danger reservation_done"
          style="cursor: pointer"
          data-table-id="${table_id}"
          data-reservation-id="${response.reservation.id}"
          >x</span>
          ${moment(date).format("DD MMMM YYYY HH:mm")}
          </h4>
          `
          $('.table-reservations').prepend(html);

        }

        $.trim(response.message) ? toastr.success(response.data) : toastr.error(response.data);
      }
    })
  }

  function busyDblTable(obj){
    let _this = $(obj)
    let table_id = $(obj).data('table-id')
    let table_status = $(obj).data('table-busy')
    busyTable(_this,table_id,table_status);
  }

  $(document).on('click', '.book-table', function () {
    let _this = $(this)
    let table_id = $(this).data('table-id')
    let table_status = $(this).data('table-busy')
    busyTable(_this,table_id,table_status);
  });

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

  $('#datetimepickerInfo').datetimepicker({
    format: 'YYYY-MM-DD HH:mm',
  });
  </script>

@endsection
