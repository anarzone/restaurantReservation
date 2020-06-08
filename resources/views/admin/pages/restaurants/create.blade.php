@extends('admin.layouts.app')
@section('page-title', 'Restoran yarat')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                        <form action="{{route('restaurants.store')}}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="restaurant_name" class="col-sm-2 col-form-label">Ad <code>*</code></label>
                                <div class="col-sm-10">
                                    <input type="text" id="restaurant_name" name="name" placeholder="restoran adı" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="restaurant_address" class="col-sm-2 col-form-label">Ünvan</label>
                                <div class="col-sm-10">
                                    <input type="text" id="restaurant_address" name="address" placeholder="yerləşdiyi ünvan" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-block btn-outline-success">Yarat</button>
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
    </script>
@endsection
