@extends('admin.layouts.app')
@section('page-title', 'Restoran Redaktə')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('manage.restaurants.update', $restaurant->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="restaurant_name" class="col-sm-2 col-form-label">Ad <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" id="restaurant_name" name="name" value="{{$restaurant->name}}" placeholder="restoran adı" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="restaurant_address" class="col-sm-2 col-form-label">Ünvan</label>
                            <div class="col-sm-10">
                                <input type="text" id="restaurant_address" name="address" value="{{$restaurant->address}}" placeholder="yerləşdiyi ünvan" class="form-control">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success">Yenilə</button>
                            @if(!isset($restaurant->reservations[0]))
                                <button class="btn btn-danger rest-delete"
                                        data-rest-id="{{$restaurant->id}}"
                                        data-rest-status="{{$restaurant->status}}"
                                >Sil</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        toastr.options = {
            "preventDuplicates": true,
            "positionClass": "toast-top-center",
        }

        @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{$error}}');
            @endforeach
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        $('.rest-delete').on('click', function (e) {
            e.preventDefault();
            let rest_id = $(this).data('rest-id');
            let status = $(this).data('rest-status');
            deleteEl({status}, '/manage/restaurants/destroy/' + rest_id, 'Silmək istədiyinizdən əminsiniz?', '/manage/restaurants/all')
        })
    </script>
@endsection
