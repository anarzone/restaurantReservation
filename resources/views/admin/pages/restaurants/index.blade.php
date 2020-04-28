@extends('admin.layouts.app')
@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Restoranlar və Zallar</h4>
                    <div class="row">
                        <div class="col-sm-3 mb-2 mb-sm-0">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                 aria-orientation="vertical">
                                @foreach($restaurants as $res)
                                    <a class="nav-link" id="v-pills-{{$res->id}}-tab" data-toggle="pill"
                                       href="#v-pills-{{$res->id}}" role="tab" aria-controls="v-pills-{{$res->id}}"
                                       aria-selected="true">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">{{$res->name}}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div> <!-- end col-->

                        <div class="col-sm-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach($restaurants as $res)
                                    <div class="tab-pane fade" id="v-pills-{{$res->id}}" role="tabpanel"
                                         aria-labelledby="v-pills-{{$res->id}}-tab">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="bg-primary text-white">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Ad</th>
                                                                <th>Masa sayı</th>
                                                                <th>Əməliyyat</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($res->halls as $hall)
                                                                <tr>
                                                                    <td>{{$hall->id}}</td>
                                                                    <td>{{$hall->name}}</td>
                                                                    <td>{{$tables_by_hall_id[$hall->id]}}</td>
                                                                    <td>
                                                                        <button
                                                                            class="btn btn-rounded btn-sm btn-outline-success hall_details"
                                                                            type="submit"
                                                                            data-hall-id="{{ $hall->id }}"
                                                                            data-rest-id="{{ $res->id }}"
                                                                            data-hall-name="{{ $hall->name }}"
                                                                            data-toggle="modal"
                                                                            data-target="#bs-example-modal-lg"
                                                                        >
                                                                            Ətraflı</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div> <!-- end tab-content-->
                        </div> <!-- end col-->
                    </div>
                    <!-- end row-->
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    </div>

    <!--  Modal content for the above example -->
    <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button type="button" class="btn btn-sm btn-outline-success save-changes" data-dismiss="modal"
                            aria-hidden="true">Yadda Saxla</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="grid-container">
                                <div class="form-group row">
                                    <label for="hall_name_val" class="col-sm-2 col-form-label">Ad</label>
                                    <div class="col-sm-10">
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
                                        <button class="btn btn-sm btn-rounded btn-outline-success" id="add_table">+ Yeni</button>
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
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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

    // modal click actions
    $('.hall_details').on('click', function(){
        hall_id = $(this).attr('data-hall-id');
        hall_name = $(this).attr('data-hall-name');
        rest_id = $(this).attr('data-rest-id');

        $('#hall_name_val').val(hall_name)


        // show & hide hall tables

        $.ajax({
            type: 'POST',
            url: '/get_tables_by_hall_id',
            data: {hall_id: hall_id},
            success: function (result) {
                if($.trim(result.data) && result.status === 200){
                    $.each(result.data, function (key, val) {
                        $('#table_list_inputs').append('' +
                            '<div class="input-group mt-2" data-id="'+ val.id +'">' +
                            ' <input type="text" class="form-control table-number" value="'+ val.table_number +'" id="'+ val.id +'"' +
                            '  placeholder="masa nömrəsi">' +
                            ' <div class="input-group-append">' +
                            '    <button class="btn btn-outline-danger btn-rounded btn-sm delete_table" id="'+ val.id +'" type="button">-</button>' +
                            '</div></div>'
                        )
                    })
                }
            }
        })
    })


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

    // add new table to hall
    $('#add_table').on('click', function(){
        $('#table_list_inputs').prepend('' +
            '<div class="input-group mt-2">' +
            ' <input type="text" class="form-control table-number"' +
            '  placeholder="masa nömrəsi">' +
            ' <div class="input-group-append">' +
            '    <button class="btn btn-outline-danger btn-rounded btn-sm delete_table" type="button">-</button>' +
            '</div></div>'
        )
    })


    // save table_number changes
    $(document).on('change', '.table-number', function(){
        let new_table_number = $(this).val();
        let table_id = $(this).attr('id')
        let url = table_id ? '/change_table_number' : '/add_new_table';

        $.ajax({
            type: 'POST',
            url: url,
            data: {table_id: table_id, table_number: new_table_number, rest_id: rest_id, hall_id: hall_id},
            success: function(result){
                if($.trim(result.data) && result.status === 200){
                    console.log('success')
                }
            }
        })
    })

    // remove table from list
    $(document).on('click', '.delete_table', function(){

        let table_id = $(this).attr('id')
        if(table_id){
            $.ajax({
                type: 'DELETE',
                url: '/tables/destroy/'+table_id,
                data: {table_id: table_id},
                success: function(result){
                    if(result.message){
                        $('.input-group[data-id="'+ table_id +'"]').remove()
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
</script>

@endsection
