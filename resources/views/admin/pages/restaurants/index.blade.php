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

    // resuable variables
    let hall_id = null;
    let hall_name = null;
    let rest_id = null;
    let table_values = {};

    // save hall name
    $('#hall_name_val').on('change', function () {
        let new_hall_name = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/halls/update/name',
            data: {hall_id: hall_id, hall_name: new_hall_name},
            success: function(result){
                if($.trim(result.status === 200)){
                    $('#hall_name_val').addClass('is-valid')
                    setTimeout(function (){
                        $('#hall_name_val').removeClass('is-valid');
                    }, 1500)
                }
            }
        })
    })

    // remove table from list
    $(document).on('click', '.delete-table', function(){

        let table_id = $(this).attr('id')
        if(table_id){
            $.ajax({
                type: 'DELETE',
                url: '/tables/destroy/'+table_id,
                data: {table_id: table_id},
                success: function(result){
                    if(result.message){
                        $('.input-group[data-id="'+ table_id +'"]').remove()
                    }else{
                        alert('Bu masa rezerv edilib')
                    }
                }
            })
        }else{
            $(this).parent('div').parent('div').remove()
        }

    })

    $('.save-changes').on('click', function(){
        location.reload();
    })

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
                                <div class="col-sm-3">
                                    <div class="card mt-4 ${bgColorStatus} text-light table-properties">
                                        <div class="card-body text-center input-properties">
                                            <h4>Masa:    ${val.table_number}</h4>
                                            <h4>Tutum: ${val.people_amount}</h4>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button class="btn btn-sm edit-table"
                                                data-id="${val.id}"
                                                data-number ="${val.table_number}"
                                                data-amount ="${val.people_amount}"
                                            >
                                    `
                            if(val.status === 0){
                                html += `
                                        <span class="badge badge-warning"><i class="fas fa-pen"></i> Redaktə</span>
                                        <span class="badge badge-light"><i class="fas fa-remove"></i> Sil</span>
                                        `;
                            }
                            html += `
                                            </button>
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

    $(document).on('click', '.edit-table', function () {
        let table_id  = $(this).data('id')
        let table_number = $(this).data('number')
        let people_amount = $(this).data('amount')
        let inputPlace = $(this).closest('.card').find('.input-properties');
        let footer = $(this).parent();

        table_values[table_id] = {
            'prev_number': table_number,
            'prev_amount': people_amount,
            'current_number': table_number,
            'current_amount': people_amount
        }

        let inputsHtml = `
            <input class="form-control form-control-sm table-number" data-table-id="${table_id}" value="${table_number}" placeholder="masa nömrəsi">
            <input class="form-control form-control-sm mt-3 people-amount" data-table-id="${table_id}" value="${people_amount}" placeholder="adam sayı">
        `
        inputPlace.html(inputsHtml)

        let footerHtml = `
            <button type="button" class="btn btn-primary btn-sm save-table" data-table-id="${table_id}"><i class="fas fa-save"> Saxla</i></button>
            <button type="button" class="btn btn-secondary btn-sm cancel-table" data-table-id="${table_id}"><i class="fas fa-window-close"> İmtina</i></button>
        `;
        footer.html(footerHtml)
    })

    // get table number
    $(document).on('change', '.table-number', function(){
        let table_id = $(this).data('table-id')

        table_values[table_id].current_number = parseInt($(this).val());
    })

    // get people amount
    $(document).on('change', '.people-amount', function(){
        let table_id = $(this).data('table-id')

        table_values[table_id].current_amount = parseInt($(this).val());
    })


    $(document).on('click', '.cancel-table', function () {
        let inputPlace = $(this).closest('div.card');
        let table_id = $(this).data('table-id');
        let table_number = table_values[table_id].prev_number
        let people_amount = table_values[table_id].prev_amount
        let html = '';
        html += `
                <div class="card-body text-center input-properties">
                    <h4>Masa:    ${table_number}</h4>
                    <h4>Adam sayı: ${people_amount}</h4>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-sm edit-table"
                        data-id="${table_id}"
                        data-number ="${table_number}"
                        data-amount ="${people_amount}"
                    >
                        <span class="badge badge-warning"><i class="fas fa-pen"></i> Edit</span>
                    </button>
                </div>
            `;

        html += '</div';
        inputPlace.html(html);
    })


    $(document).on('click', '.save-table', function () {
        let inputPlace = $(this).closest('div.card');
        let table_id = $(this).data('table-id');
        let table_number = table_values[table_id].current_number
        let people_amount = table_values[table_id].current_amount
        let html = '';

        $.ajax({
            type: 'POST',
            url: '/tables/change_number',
            data: {table_id, table_number, people_amount},
            success: function (result) {
                if($.trim(result.data)){
                    console.log(result.data)

                    html += `
                        <div class="card-body text-center input-properties">
                            <h4>Masa:    ${table_number}</h4>
                            <h4>Adam sayı: ${people_amount}</h4>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-sm edit-table"
                                data-id="${table_id}"
                                data-number ="${table_number}"
                                data-amount ="${people_amount}"
                            >
                                <span class="badge badge-warning"><i class="fas fa-pen"></i> Edit</span>
                            </button>
                        </div>
                    `;

                    html += '</div';
                    inputPlace.html(html);
                }
            }
        })

    })
</script>

@endsection
