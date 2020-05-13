@extends('admin.layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
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
            <div class="card">
                <div class="card-body" id="tables">

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
            $('#tables').empty()
            hall_id = $(this).val();
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
                                let bgColorStatus = val.status === 0 ? 'bg-success' : 'bg-danger';
                                html += `
                                <div class="col-sm-2">
                                    <div class="card mt-4 ${bgColorStatus} text-light table-properties">
                                        <div class="card-body text-center input-properties">
                                            <h4>Masa:    ${val.table_number}</h4>
                                            <h4>Tutum: ${val.people_amount}</h4>
                                        </div>
                                    </div>
                                </div>`

                            })
                            html += '</div';
                            tables.append(html)
                        }
                    }
                })
            }
        })

    </script>

@endsection
