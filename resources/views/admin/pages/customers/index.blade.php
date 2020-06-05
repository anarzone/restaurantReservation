@extends('admin.layouts.app')

@section('page-title', 'Müştərilər')

@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                </div>
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ad Soyad</th>
                            <th scope="col">Telefon</th>
                            <th scope="col">Rezervasiyalar</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <th scope="row">{{$customer->id}}</th>
                            <td>{{$customer->firstname}} {{$customer->lastname}}</td>
                            <td>{{$customer->phone}}</td>
                            <td>
                                <button class="btn btn-sm btn-info show-reservations"
                                        data-customer-id="{{$customer->id}}"
                                        data-toggle="modal"
                                        data-target="#full-width-modal"
                                >Göstər</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4"></div>
        <div class="col-6">
            {{$customers->links()}}
        </div>
    </div>

    <div class="modal fade" id="full-width-modal" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title">Rezervasiyalar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Ad soyad</th>
                                <th scope="col">Telefon</th>
                                <th scope="col">Qonaq</th>
                                <th scope="col">Restoran</th>
                                <th scope="col">Zal</th>
                                <th scope="col">Masa</th>
                                <th scope="col">Qeyd</th>
                                <th scope="col">Tarix</th>
                            </tr>
                        </thead>
                        <tbody class="reservations">

                        </tbody>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('js')
    <script>
        $('.show-reservations').on('click', function () {
            $('.reservations').empty()

            let customer_id = $(this).data('customer-id');

            if(customer_id){
                $.ajax({
                    type: 'GET',
                    url:  '/admin/customers/'+ customer_id +'/reservations',
                    success: function (result) {
                        if($.trim(result.data)){
                            $.each(result.data, function (i, val) {
                                let html = `
                                    <tr>
                                        <th scope="row">${val.id}</th>
                                        <td>${val.res_firstname} ${val.res_lastname}</td>
                                        <td>${val.res_phone}</td>
                                        <td>${val.res_people}</td>
                                        <td>${val.restaurant_name}</td>
                                        <td>${val.hall_name}</td>
                                        <td>${val.table_number ?? ''}</td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle tooltip"
                                                data-toggle="tooltip"
                                                title="${val.note}"
                                                data-placement="top"
                                                data-original-title="'${val.note}'"></i>
                                        </td>
                                        <td>${val.datetime}</td>
                                    </tr>
                                `
                                $('.reservations').append(html)
                            })
                        }
                    }
                })
            }
        })

    </script>
@endsection
