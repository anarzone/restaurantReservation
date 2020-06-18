@extends('admin.layouts.app')
@section('page-title', 'Masalar')
@section('css')
    <link rel="stylesheet" href="{{asset('back/dist/css/font-awesome/font-awesome-5.13.0.min.css')}}" />
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="mr-sm-2">Restoran</label>
                            <select class="custom-select mr-sm-2"
                                    id="restaurants"
                                    {{request()->has('restaurant') && request()->has('hall') ? 'disabled' : ''}}>
                                <option disabled selected> -- Restoran seç</option>
                                @foreach($restaurants as $rest)
                                    <option value="{{$rest->id}}"
                                        {{request()->has('restaurant') && request('restaurant') == $rest->id ? 'selected' : ''}}
                                    >
                                        {{$rest->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="mr-sm-2">Zal</label>
                            <select class="custom-select mr-sm-2" id="halls">
                            </select>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-success add-table" disabled>+ Masa əlavə et</button>
                            <button class="btn btn-warning edit-plan" disabled >
                                Plan redaktə et
                                <span class="badge badge-warning"><i class="fas fa-external-link-alt"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card" style="display:none" id="resultCard">
                <div class="card-body" id="tables">

                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script src="{{asset('back/dist/js/dry_functions.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    // resuable variables
    let hall_id = '{{request()->has("hall") ? request('hall') : null}}';
    let hall_name = null;
    let rest_id = $('#restaurants option:selected').val();
    let table_values = {};
    let new_table_values = {};
    let counter = 0;

    if(!isNaN(rest_id)){
        $('#halls').attr('disabled', true)
        $('.add-table').removeAttr('disabled');

        getHalls(rest_id, hall_id)
        getTables(hall_id)
    }

    $('#restaurants').on('change', function () {
        $('.edit-plan').attr('disabled', true);
        rest_id = $(this).val();
        if(rest_id){
            getHalls(rest_id)
        }else{
            $('#halls').empty();
        }
    })

    $('#halls').on('change', function () {
        $('#tables').empty()
        $('#resultCard').hide()

        hall_id = $(this).val();
        if(hall_id){
            getTables(hall_id)
        }
    })

    // activate add table button
    $(document).on('change', '#halls, #restaurants', function(){
        if(hall_id && rest_id){
            $('.add-table').removeAttr('disabled');
        }
    })

    $(document).on('click', '.edit-table', function (e) {
        e.stopPropagation();
        let table_id  = $(this).data('id')
        let table_number = $(this).data('number')
        let people_amount = $(this).data('amount')
        let inputPlace = $(this).closest('.card').find('.input-properties');
        let footer = $(this).parent();
        let status = $(this).closest('.table-properties').data('status')

        table_values[table_id] = {
            'prev_number': table_number,
            'prev_amount': people_amount,
            'current_number': table_number,
            'current_amount': people_amount,
            status,
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
        let table_counter = $(this).parent('div').parent('div').data('counter')
        if(table_id){
            table_values[table_id].current_number = parseInt($(this).val());
        }else{
            new_table_values[table_counter].number = parseInt($(this).val());
        }
    })

    // get people amount
    $(document).on('change', '.people-amount', function(){
        let table_id = $(this).data('table-id')
        let table_counter = $(this).parent('div').parent('div').data('counter')
        if(table_id){
            table_values[table_id].current_amount = parseInt($(this).val());
        }else{
            new_table_values[table_counter].people_amount = parseInt($(this).val());
        }
    })

    $(document).on('click', '.cancel-table', function () {
        let inputPlace = $(this).closest('div.card');
        let table_id = $(this).data('table-id');
        let table_number = table_values[table_id].prev_number
        let people_amount = table_values[table_id].prev_amount

        let table_status = table_values[table_id].status
        let bgColorStatus = table_status === 1 ? 'bg-success' : 'bg-secondary';

        let html = '';
        html += `
                <div class="card-body text-center input-properties ${bgColorStatus}">
                    <h4>Masa:    ${table_number}</h4>
                    <h4>Adam sayı: ${people_amount}</h4>
                </div>
                <div class="card-footer text-right ${bgColorStatus}">
                    <button class="btn btn-sm edit-table"
                        data-id="${table_id}"
                        data-number ="${table_number}"
                        data-amount ="${people_amount}"
                    >
                        <span class="badge badge-warning"><i class="fas fa-pen"></i> Redaktə</span>
                    </button>
                    <button class="btn btn-sm delete-table" data-table-id="${table_id}">
                        <span class="badge badge-light">Sil</span>
                    </button>
                </div>
            `;

        html += '</div';
        inputPlace.html(html);
    })

    $(document).on('click', '.save-table', function () {
        let inputPlace = $(this).closest('div.card');
        let table_id = $(this).data('table-id');
        let new_table_counter = $(this).parent().parent().data('counter')

        let table_number  = table_id ? table_values[table_id].current_number : new_table_values[new_table_counter].number
        let people_amount = table_id ? table_values[table_id].current_amount : new_table_values[new_table_counter].people_amount

        let html = '';
        let url = table_id ? '/tables/change_number' : '/tables/store'
        let data = table_id ? {table_id, table_number, people_amount} : {rest_id, hall_id, table_number, people_amount}

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (result) {
                if($.trim(result.data)){
                    console.log(result.data)
                    let table_status = table_values[table_id] ? table_values[table_id].status : 0
                    let bgColorStatus = table_status === 1 ? 'bg-success' : 'bg-secondary';
                    html += `
                        <div class="card-body text-center input-properties ${bgColorStatus} ">
                            <h4>Masa:    ${table_number}</h4>
                            <h4>Adam sayı: ${people_amount}</h4>
                        </div>
                        <div class="card-footer text-right ${bgColorStatus}">
                            <button class="btn btn-sm edit-table"
                                data-id="${table_id ?? result.data.id}"
                                data-number="${table_number}"
                                data-amount="${people_amount}"
                            >
                                <span class="badge badge-warning"><i class="fas fa-pen"></i> Redaktə et</span>
                            </button>
                            <button class="btn btn-sm delete-table" data-table-id="${table_id ?? result.data.id}">
                                <span class="badge badge-light">Sil</span>
                            </button>
                        </div>
                    `;

                    html += '</div>';
                    inputPlace.removeClass('bg-secondary')
                    inputPlace.addClass('bg-success')
                    inputPlace.addClass('text-light')
                    inputPlace.html(html);
                }
            }
        })

    })

    // remove table from list
    $(document).on('click', '.delete-table', function(e){
        e.stopPropagation();
        let new_table_counter = $(this).data('counter')
        let parentDiv = $(this).parent('div').parent('div').parent('div');

        if (new_table_counter && new_table_values[new_table_counter]){
            delete new_table_values[new_table_counter];
        }

        let table_id = $(this).data('table-id')
        console.log(table_id)

        if(table_id && confirm('Silmək istədiyinizdən əminsiniz?')){
            $.ajax({
                type: 'DELETE',
                url: '/tables/destroy/'+table_id,
                data: {table_id: table_id},
                success: function(result){
                    if(result.message){
                        console.log(parentDiv)
                        parentDiv.remove()
                    }else{
                        alert('Bu masa rezerv edilib')
                    }
                }
            })
        }else if(new_table_counter){
            parentDiv.remove()
        }
    })

    $('.add-table').on('click', function(){
        counter++;
        new_table_values[counter] = {
            'number': '',
            'people_amount': ''
        };
        let html = `
            <div class="col-sm-3">
                <div class="card mt-4 bg-secondary table-properties" data-counter="${counter}">
                    <div class="card-body text-center input-properties">
                        <input class="form-control form-control-sm table-number" placeholder="masa nömrəsi">
                        <input class="form-control form-control-sm mt-3 people-amount" placeholder="tutum">
                    </div>
                    <div class="card-footer text-center">
                        <button type="button" class="btn btn-primary btn-sm save-table"><i class="fas fa-save"> Saxla</i></button>
                        <button type="button" class="btn btn-danger btn-sm delete-table" data-counter="${counter}">Sil</i></button>
                    </div>
                </div>
            </div>
        `
        $('.table-rows').append(html)
    })

    $('.edit-plan').on('click', function () {
        let plan_id = $(this).data('plan-id');

        if(plan_id){
            location.href = '/admin/plans/'+ plan_id +'/edit'
        }
    })

    function getTables(hall_id){
        $.ajax({
            type: 'GET',
            url:  '/tables/get_by_hall_id/' + hall_id,
            dataType: 'json',
            success: function (result) {
                if($.trim(result.data)){
                    $("#resultCard").fadeIn();
                    if(result.data.has_plan){
                        $('.edit-plan').removeAttr('disabled').attr('data-plan-id', result.data.has_plan.id);
                    }else{
                        $('.edit-plan').attr('disabled', true);
                    }
                    let tables = $('#tables');
                    let html = '';
                    html += '<div class="row table-rows">';
                    $.each(result.data.tables, function(key, val){
                        let table_status = result.data.table_have_reservations[val.id]
                        let bgColorStatus = table_status === 1 ? 'bg-success' : 'bg-secondary';
                        html += `
                                <div class="col-sm-3">
                                    <div class="card mt-4 ${bgColorStatus} text-light table-properties" data-status="${table_status}">
                                        <div class="card-body text-center input-properties">
                                            <h4>Masa:    ${val.table_number}</h4>
                                            <h4>Tutum: ${val.people_amount}</h4>
                                        </div>
                                        <div class="card-footer text-right">
                                    `
                        if(parseInt(val.status) === 0){
                            html += `<button class="btn btn-sm edit-table"
                                                data-id="${val.id}"
                                                data-number ="${val.table_number}"
                                                data-amount ="${val.people_amount}"
                                            >
                                            <span class="badge badge-warning"><i class="fas fa-pen"></i> Redaktə</span>
                                        </button>
                                        <button class="btn btn-sm delete-table" data-table-id="${val.id}">
                                            <span class="badge badge-light">Sil</span>
                                        </button>
                                        `;
                        }
                        html += `
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
</script>

@endsection
