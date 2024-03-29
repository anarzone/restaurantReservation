@extends('admin.layouts.app')
@section('page-title', 'Zal Redaktə')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('manage.halls.update', $hall->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="restaurant_name" class="col-sm-2 col-form-label">Ad <code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" id="restaurant_name" name="name" value="{{$hall->name}}" placeholder="restoran adı" class="form-control">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success">Yenilə</button>
                            @if(!$has_reservation)
                                <button class="btn btn-danger hall-delete" data-hall-id="{{$hall->id}}">Sil</button>
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

        $('.hall-delete').on('click', function (e) {
            e.preventDefault();
            let hall_id = $(this).data('hall-id');
            deleteEl({}, '/manage/halls/destroy/' + hall_id, 'Silmək istədiyinizdən əminsiniz?', '/manage/halls')
        })
    </script>
@endsection
