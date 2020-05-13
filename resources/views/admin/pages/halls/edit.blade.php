@extends('admin.layouts.app')
@section('page-title', 'Zal Redaktə')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.halls.update', $hall->id)}}" method="POST">
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        toastr.options = {
            "preventDuplicates": true,
            "positionClass": "toast-top-center",
        }
        @if(session('message'))
            toastr.success("{{ session('message') }}");
        @endif

        $('.hall-delete').on('click', function (e) {
            e.preventDefault();
            let hall_id = $(this).data('hall-id');
            deleteEl({}, '/admin/halls/destroy/' + hall_id, 'Silmək istədiyinizdən əminsiniz?', '/admin/halls')
        })
    </script>
@endsection
