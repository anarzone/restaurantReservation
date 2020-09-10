@extends('admin.layouts.app')

@section('page-title', 'Müştərilər')

@section('css')
@endsection

@section('content')
<div class="row">
    <div class="col-4">
        <div class="card">
            <table class="table mb-0 customer-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ad Soyad</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($customers) && count($customers) > 0)
                    @foreach($customers as $customer)
                    <tr onclick="getCustomer(this,{{$customer->id}})" style="cursor: pointer">
                        <th scope="row">{{$customer->id}}</th>
                        <td>
                            {{$customer->firstname}} {{$customer->lastname}}
                        </td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="{{route('manage.customer.edit',$customer->id)}}"><i
                                    class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10">
                            <div class="alert alert-warning">
                                Müştərilər tapılmadı
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-8">
        <div class="card customer-detail" style="display: none">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="customer-info" data-toggle="tab" href="#customerInfo"
                        role="tab">Müştəri məlumatları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="customer-reservations" data-toggle="tab" href="#customerReservations" role="tab">Rezervasiyalar</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!--currentReserve-->
                <div class="tab-pane fade show active" id="customerInfo" role="tabpanel">
                    <table class="table customer-info">
                    </table>
                </div>

                <div class="tab-pane fade" id="customerReservations" role="tabpanel">
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

            </div>

        </div>
    </div>

</div>

{{$customers->links()}}
<input type="hidden" id="selectedCustomer" value="0"/>
@endsection

@section('js')
<script>

    function getCustomer(obj, customer_id){
        $('.customer-table tr').removeClass('active-tr');
        $(obj).addClass('active-tr');
        $('.customer-detail').show();
        $('.customer-info').empty();
        $('.reservations').empty();

        if(customer_id){

        $.ajax({
                    type: 'GET',
                    url:  '/manage/customers/'+ customer_id +'/show',
                    success: function (result) {
                        if($.trim(result.data)){
                            $('.customer-info').append(`
                                    <tr>
                                        <td>Adı</td><td>${result.data.firstname}</td>
                                    </tr>
                                    <tr>
                                        <td>Soyadı</td><td>${result.data.lastname}</td>
                                    </tr>
                                    <tr>
                                        <td>Telefon</td><td>${result.data.phone}</td>
                                    </tr>
                                    <tr>
                                        <td>Doğum tarixi</td><td>${result.data.birthdate}</td>
                                    </tr>
                                    <tr>
                                        <td>Qeyd</td><td>${result.data.note}</td>
                                    </tr>
                                    <tr>
                                        <td>Əlavə olunma tarixi</td><td>${result.data.created_at}</td>
                                    </tr>
                                `)
                        }
                    }
                })

                //get reservations
                $.ajax({
                    type: 'GET',
                    url:  '/manage/customers/'+ customer_id +'/reservations',
                    success: function (result) {
                        if($.trim(result.data)){
                            $.each(result.data, function (i, val) {
                                $('.reservations').append(`
                                    <tr>
                                        <th scope="row">${val.id}</th>
                                        <td>${val.res_firstname} ${val.res_lastname ?? ''}</td>
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
                                `)
                            })
                        }
                    }
                })
            }
    }

    @if(session('message'))
            displayMessage('{{session("message")}}')
        @elseif(session('message-danger'))
            displayMessage('{{session("message-danger")}}', 'danger')
        @endif



</script>
@endsection
