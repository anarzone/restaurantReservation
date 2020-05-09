@extends('admin.layouts.app')

@section('page-title', 'Profil Redaktə')
@section('css')
    <link rel="stylesheet" href="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-10">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.users.profile.update')}}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Ad<code>*</code></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{$userdata->name}}" name="name" id="name" placeholder="istifadəçi adı">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email<code>*</code></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" value="{{$userdata->email}}" id="email" name="email" placeholder="aktiv email olmalıdır">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Şifrə</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="şifrəni daxil edin">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Yeni şifrə</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="şifrəni daxil edin">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Yeni şifrənin təkrarı</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="şifrəni daxil edin">
                            </div>
                        </div>
                        <div class="btn-list text-center mt-4">
                            <button class="btn btn-block btn-outline-success save-user" type="submit">Yenilə</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        toastr.options = {
            "preventDuplicates": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
        }
        @if(session('msg'))
            toastr.success("{{ session('msg') }}");
        @endif
    </script>
@endsection
