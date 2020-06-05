@extends('admin.layouts.app')
@section('page-title', 'Yeni zal')


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="grid-container">
                                <div class="form-group row">
                                    <label for="hall_name_val" class="col-sm-3 col-form-label">Restoran</label>
                                    <div class="col-sm-9">
                                        <select class="custom-select mr-sm-2" id="restaurants" {{request()->has('restaurant') ? 'disabled' : ''}}>
                                            <option disabled selected value>-- Restoran seç --</option>
                                            @foreach($restaurants as $res)
                                                <option value="{{$res->id}}"
                                                        {{request()->has('restaurant') && request('restaurant') == $res->id ? 'selected' : ''}}
                                                    >
                                                    {{$res->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="hall_name_val" class="col-sm-3 col-form-label">Zal adı</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="hall_name_val" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="grid-container text-center" id="table_container">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3>Masalar</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="btn btn-sm btn-rounded btn-outline-success" id="add_table" disabled>+ Yeni</button>
                                    </div>
                                </div>
                                <div class="grid-container mt-3" id="table_list_container">
                                    <div class="row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-8" id="table_list_inputs">
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row-->

                    <div class="form-group row text-center mt-5">
                        <div class="col-sm-12">
                            <button class="btn btn-outline-success save-hall">Yadda saxla</button>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
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

        // reusable variables
        let hall_id =   null;
        let hall_name = null;
        let rest_id =   $('#restaurants option:selected').val();
        let hall_tables = {};
        let counter = 0;

        // get hall name
        $('#hall_name_val').on('change', function () {
            hall_name = $(this).val();
            if(!hall_name){
                $('#add_table').attr('disabled', true);
            }
        })

        // get restaurant id
        $('#restaurants').on('change', function () {
            rest_id = $(this).val();
        })

        $(document).on('change', '#hall_name_val, #restaurants', function(){
            if(hall_name && rest_id){
                $('#add_table').removeAttr('disabled');
            }
        })


        $('.save-hall').on('click', function () {
            if(hall_name && rest_id && !$.isEmptyObject(hall_tables)){
                filterTables(hall_tables);

                $.ajax({
                    type: 'POST',
                    url: '/halls/store',
                    data: {rest_id: rest_id, hall_name: hall_name, tables: hall_tables},
                    success: function(result){
                        if($.trim(result.data)){
                            location.reload();
                        }
                    }
                })
            }
        })

        // add new table to hall
        $('#add_table').on('click', function(){
            counter++;

            hall_tables[counter] = {};
            $('#table_list_inputs').append('' +
                '<div class="row mt-2" data-id="'+ counter +'">' +
                    '<div class="col-sm-2 mt-1"><span>Say</span></div>' +
                    '<div class="col-sm-3">' +
                        '<input type="text" class="form-control people-amount" placeholder="adam sayi"' +
                        ' data-id="'+ counter +'" required>' +
                    '</div>' +
                    '<div class="col-sm-2 mt-1"><span>No.</span></div>' +
                    '<div class="col-sm-3">' +
                        ' <input type="text" class="form-control table-number" placeholder="masa nömrəsi"' +
                        ' data-id="'+ counter +'" required>' +
                    '</div>' +
                    '<div class="col-sm-2">' +
                        '<button class="btn btn-sm btn-outline-danger mt-1 delete-table"> Sil </button>' +
                    '</div>' +
                '</div>'
            )
        })

        // remove table from list
        $(document).on('click', '.delete-table', function(){
            let table_id = $(this).attr('data-id')
            if (hall_tables[counter]){
                delete hall_tables[counter];
            }
            $(this).parent('div').parent('div').remove()
            counter--;
        })

        // save table_number changes
        $(document).on('change', '.table-number', function(){
            let new_table_number = $(this).val();
            let table_counter = $(this).data('id');
            if(new_table_number && $.isNumeric(new_table_number)){
                hall_tables[table_counter]['table_number'] = new_table_number;
            }
        })

        // save people amount changes
        $(document).on('change', '.people-amount', function(){
            let people_amount = $(this).val();
            let table_counter = $(this).data('id');
            if(people_amount && $.isNumeric(people_amount)){
                hall_tables[table_counter]['people_amount'] = people_amount;
            }

        })

        function filterTables(tables){
            for (let [key, val] of Object.entries(tables)){
                if(val.people_amount === undefined || val.table_number === undefined){
                    delete tables[key]
                }
            }
        }
    </script>
@endsection
