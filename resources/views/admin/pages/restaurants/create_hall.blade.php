@extends('admin.layouts.app')
@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Yeni zal</h4>

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="grid-container">
                                <div class="form-group row">
                                    <label for="hall_name_val" class="col-sm-3 col-form-label">Restoran</label>
                                    <div class="col-sm-9">
                                        <select class="custom-select mr-sm-2" id="restaurants">
                                            <option disabled selected value>-- Restoran seç --</option>
                                            @foreach($restaurants as $res)
                                                <option value="{{$res->id}}">{{$res->name}}</option>
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
        let rest_id =   null;
        let hall_tables = {};
        let counter = 0;

        // save hall name
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
                let tables = $.map(hall_tables, function(value, index){
                    return [value];
                })
                $.ajax({
                    type: 'POST',
                    url: '/halls/store',
                    data: {rest_id: rest_id, hall_name: hall_name, tables: tables},
                    success: function(result){
                        if($.trim(result.status === 200)){
                            console.log(result.data)
                            location.reload();
                        }
                    }
                })
            }
        })

        // add new table to hall
        $('#add_table').on('click', function(){
            counter++;
            hall_tables[counter] = '';
            $('#table_list_inputs').prepend('' +
                '<div class="input-group mt-2" data-id="'+ counter +'">' +
                ' <input type="text" class="form-control table-number"' +
                'data-id="'+ counter +'"' +
                '  placeholder="masa nömrəsi">' +
                ' <div class="input-group-append">' +
                '    <button class="btn btn-outline-danger btn-rounded btn-sm delete_table" type="button">-</button>' +
                '</div></div>'
            )
        })

        // remove table from list
        $(document).on('click', '.delete_table', function(){
            let table_id = $(this).attr('data-id')
            delete hall_tables[counter];
            $(this).parent('div').parent('div').remove()
            console.log(hall_tables);
            counter--;
        })

        // save table_number changes
        $(document).on('change', '.table-number', function(){
            let new_table_number = $(this).val();
            if(new_table_number && $.isNumeric(new_table_number)){
                hall_tables[counter] = new_table_number
            }

        })


    </script>
@endsection
