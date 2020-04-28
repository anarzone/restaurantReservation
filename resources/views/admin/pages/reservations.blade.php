@extends('admin.layouts.app')
@section('css')
    <!-- This page plugin CSS -->
    <link href="{{asset('back/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('content')
    <!-- basic table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered no-wrap">
                            <thead>
                            <tr>
                                <th>Ad</th>
                                <th>Adam sayı</th>
                                <th>Telefon</th>
                                <th>Restoran</th>
                                <th>Zal</th>
                                <th>Masa</th>
                                <th>Tarix</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservations as $res)
                                <tr>
                                    <td>{{ $res->res_firstname. ' ' .$res->res_lastname }}</td>
                                    <td>{{ $res->res_people }}</td>
                                    <td>{{ $res->res_phone }}</td>
                                    <td>{{ $res->res_name }}</td>
                                    <td>{{ $res->hall_name }}</td>
                                    <td>{{ $res->table_number }}</td>
                                    <td>{{ $res->datetime }}</td>
                                    @if($res->status == '0')
                                        <td>
                                            <a
                                                href="#"
                                                type="button"
                                                class="btn btn-rounded btn-outline-secondary btn-sm changeReservationStatus"
                                                data-toggle="modal"
                                                data-target="#change-status-modal"
                                                data-table-id="{{$res->table_id}}"
                                                data-table-number="{{$res->table_number}}"
                                                data-res-id="{{$res->id}}"
                                                data-hall-id="{{$res->res_hall_id}}"
                                                data-rest-id="{{$res->res_restaurant_id}}"
                                                data-hall-name="{{$res->hall_name}}"
                                            >
                                                gözləyir
                                            </a>
                                        </td>
                                    @elseif($res->status == '1')
                                        <td>
                                            <a type="button" class="btn btn-rounded btn-outline-success btn-sm changeReservationStatus"
                                               data-toggle="modal"
                                               data-target="#change-status-modal"
                                               data-table-id="{{$res->table_id}}"
                                               data-table-number="{{$res->table_number}}"
                                               data-res-id="{{$res->id}}"
                                               data-hall-id="{{$res->res_hall_id}}"
                                               data-rest-id="{{$res->res_restaurant_id}}"
                                               data-hall-name="{{$res->hall_name}}"
                                            >
                                                təsdiqlənib
                                            </a>
                                        </td>
                                    @else
                                        <td>
                                            <a type="button" class="btn btn-rounded btn-outline-danger btn-sm changeReservationStatus"
                                               data-toggle="modal"
                                               data-target="#change-status-modal"
                                               data-table-id="{{$res->table_id}}"
                                               data-table-number="{{$res->table_number}}"
                                               data-res-id="{{$res->id}}"
                                               data-hall-id="{{$res->res_hall_id}}"
                                               data-rest-id="{{$res->res_restaurant_id}}"
                                               data-hall-name="{{$res->hall_name}}"
                                            >
                                                imtina e.
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Ad</th>
                                <th>Adam sayı</th>
                                <th>Telefon</th>
                                <th>Restoran</th>
                                <th>Zal</th>
                                <th>Tarix</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- changeReservationStatus modal content -->
    <div id="change-status-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4 id="hall_name">Zal</h4>
                    </div>

{{--                    <form action="#" class="pl-3 pr-3">--}}

                        <div class="form-group mb-4">
                            <label for="empty-tables">Boş masalar</label>
                            <select class="custom-select mr-sm-2" id="empty-tables">
                            </select>
                        </div>

                        <div class="btn-list text-center">
                            <button class="btn btn-rounded btn-outline-success accept_res" type="submit">Təsdiq et</button>
                            <button class="btn btn-rounded btn-outline-danger reject_res" type="submit">İmtina et</button>
                        </div>

{{--                    </form>--}}

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- order table -->
@endsection

@section('js')
<!--This page plugins -->
<script src="{{asset('back/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('back/dist/js/pages/datatables/datatable-basic.init.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    let res_id = null;
    let table_id = null;
    let reserved_table_id = null;
    let reserved_table_num = null;

    $('.changeReservationStatus').on('click',function(event){
        let empty_tables = $('#empty-tables').empty();

        res_id = $(this).attr('data-res-id');
        reserved_table_num = $(this).attr('data-table-number');
        reserved_table_id = $(this).attr('data-table-id');

        let hall_id = $(this).attr('data-hall-id');
        let rest_id = $(this).attr('data-rest-id');
        let hall_name = $(this).attr('data-hall-name');

        $('#change-status-modal').find('#hall_name').html(hall_name)

        if(hall_id && rest_id){
            $.ajax({
                type: "POST",
                url: "/tables/get_by",
                data: {hall_id: hall_id, rest_id: rest_id},
                success: function(result){
                    empty_tables.focus()
                    if($.trim(result.data) && result.status === 200){
                        empty_tables.append('<option disabled selected value> --Masa seçin-- </option>')
                        $.each(result.data, function(key, val){
                            empty_tables.append(
                                '<option value="'+ val.id + '"> ' + val.table_number + '</option>'
                            );
                        })
                        empty_tables.append('<option value="' +  + '" ')
                    }else{
                        empty_tables.empty();
                        empty_tables.append(
                            '<option selected disabled value> Boş masa yoxdur </option>'
                        );
                    }
                }
            });
        }else{
            empty_tables.empty();
        }
    })

    $('#empty-tables').on('change', function(){
        table_id = $(this).val();
    });

    $('.accept_res').on('click', function(){
        if(table_id){
            $.ajax({
                type: 'POST',
                url: '/tables/update',
                data: {book: 1, table_id: table_id, res_id: res_id, res_table_id: reserved_table_id},
                success: function(result){
                    if(result.status === 200){
                        table_id = null;
                        location.reload()
                    }
                }
            })
        }
    });

    $('.reject_res').on('click', function(){
        $.ajax({
            type: 'POST',
            url: '/update_table',
            data: {book: 0, table_id: reserved_table_id, res_id: res_id},
            success: function(result){
                if(result.status === 200){
                    table_id = null;
                    location.reload()
                }
            }
        })
    });

</script>
@endsection
