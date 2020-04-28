@extends('admin.layouts.app')

@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('users.store')}}" method="POST">
                        @csrf
                        <div class="text-center mt-2 mb-4">
                            <h4 id="hall_name">Əməkdaş yarat</h4>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Ad</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name" placeholder="istifadəçi adı">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email" placeholder="aktiv email olmalıdır">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Şifrə</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="şifrəni daxil edin">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Rol</label>
                            <div class="col-sm-10">
                                <select class="custom-select mr-sm-2 form-control" name="role_id" id="roles">
                                </select>
                            </div>
                        </div>

                        <div class="btn-list text-center mt-4">
                            <button class="btn btn-block btn-outline-success save-user" type="submit">Yarat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $.ajax({
            type: 'GET',
            url: "/roles/all",
            success: function (result) {
                if($.trim(result.data)){
                    $.each(result.data, function(key, val){
                        $('#roles').append('' +
                            '<option value="'+ val.id + '"'+
                            '>'+ val.name +
                            '</option>'
                        )
                    })
                }
            }
        })
    </script>
@endsection
