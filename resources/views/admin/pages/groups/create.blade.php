@extends('admin.layouts.app')

@section('page-title', 'Qrup yarat')
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('manage.groups.store')}}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="group-name" class="col-sm-3 col-form-label">Qrup adı</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="group_name" id="group-name" placeholder="qrup adı" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group text-center"><h3>Restoranlar</h3></div>
                        @foreach($restaurants as $rest)
                            <div class="form-group row mt-2">
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="restaurants[]"
                                               class="custom-control-input"
                                               id="id-{{$rest->id}}"
                                               value="{{$rest->id}}"
                                        >
                                        <label class="custom-control-label" for="id-{{$rest->id}}">{{$rest->name}}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="btn-list text-center mt-4">
                            <button class="btn btn-block btn-outline-success save-group" type="submit">Yarat</button>
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
