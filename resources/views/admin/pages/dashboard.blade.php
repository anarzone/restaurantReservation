@extends('admin.layouts.app')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
  <style>
  .hall-plan-image{
    max-height:100%;
    max-width:100%;
    object-fit: cover;
  }

  .tableDiv{
      cursor: pointer;
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
                  <option value="{{$rest->id}}">{{$rest->name}}</option>
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
            <div class="res-table-info text-center">
              <h4 class="bg-danger text-light">Rezervasiyalar</h4>
              <span class="table-number"></span>
              <hr>
              <div class="table-reservations"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('js')

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

  let rest_id = null;
  let hall_id = null;

  $('#restaurants').on('change', function () {
    $('#map_card').hide();
    $('.reservation-info').empty()
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();

    rest_id = $(this).val();
    if(rest_id){
      $.ajax({
        type: 'GET',
        url: '/getHallsByRestId/' + rest_id,
        dataType: "json",
        success: function (result) {
          if(result.data){
            $('#halls').empty().focus();
            $('#halls').append('<option disabled selected value> -- Zal seçin -- </option>');
            $.each(result.data, function(key, val){
              $('#halls').append(
                '<option value="'+ val.id + '"> ' + val.name + '</option>'
              );
            });
          }else{
            $('#halls').empty();
          }
        }
      })
    }else{
      $('#halls').empty();
    }
  })

  $('#halls').on('change', function () {
    $('#map_card').fadeIn();
    $('.reservation-info').empty()
    $('.hall-plan-image').attr('src', '');
    $('.imagemaps').empty();

    hall_id = $(this).val();
    if(hall_id){
      $.ajax({
        type: 'GET',
        url:  '/tables/getPlanByHallId/' + hall_id,
        dataType: 'json',
        success: function (result) {
          if($.trim(result.data.tables)){
            $('#plan-alert').remove();

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
                let top    = coords[1]+'px'
                let left   = (parseInt(coords[0])+14 ) +'px'
                let width  = (coords[2] - coords[0])+'px'
                let height = (coords[3] - coords[1])+'px'
                let backgroundColor = table_status ? 'green' : 'grey'
                let opacity = '.6'

                let tableDiv = $(`<div class="tableDiv"
                                    data-table-id="${val.table_id}"
                                    data-table-status="${table_status}"
                                    onclick="showTableInfo('${val.table_id}'); reserveGuest('${val.table_id}', '${table_status}')"
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
          }else{
              let planAlert = $(`
                    <div class="alert alert-warning" id="plan-alert">
                        Rezervasiyalar tapılmadı
                    </div>
                `)

              $('.imagemaps-wrapper').prepend(planAlert)
          }
        }
      })
    }
  })

    function showTableInfo(table_id){
    $('.table-reservations').empty();
    $('.table-number').empty();
    if(table_id){
      $.ajax({
        type: 'GET',
        url:  '/reservations/table/'+ table_id +'/all',
        success: function (result) {
          if($.trim(result.data.reservations)){
            $.each(result.data.reservations, function (i, val) {
              let html = `
              <h4><span>${i+1}. </span> ${val.datetime}</h4>
              `
              $('.table-reservations').append(html);
            })
            $('.table-number').append('Masa #' + result.data.table.table_number)
          }
        }
      })
    }
  }

    function reserveGuest(table_id, table_status){
        if(table_id){
            $.ajax({
                type: 'POST',
                url: '{{route('reservation.quick.reservation')}}',
                data: {table_id, table_status, rest_id, hall_id},
                success: function (response) {
                    $(`[data-table-id=${table_id}]`).css('background-color', 'green')
                    $.trim(response.message) ? toastr.success(response.data) : toastr.error(response.data);
                }
            })
        }
    }
  </script>

@endsection
