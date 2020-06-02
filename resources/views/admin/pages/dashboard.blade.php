@extends('admin.layouts.app')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
  <style>
  .hall-plan-image{
    max-height:100%;
    max-width:100%;
    object-fit: cover;
  }

  area {
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
            let src = "{{url('storage/back/images')}}/" + result.data.plan_image
            $('.hall-plan-image').attr('src', src)
            $.each(result.data.tables, function (i, val) {
              let mapDiv = $(`<area   shape="rect"
              data-table-id="${val.table_id}"
              coords="${val.coords}"
              onclick="showTableInfo('${val.table_id}');"
              >
              `)
              $('.imagemaps').append(mapDiv)
            })
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

  </script>

@endsection
