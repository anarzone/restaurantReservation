@extends('admin.layouts.app')

@section('page-title', 'Qrup yarat')
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('manage.groups.update', $role->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="group-name" class="col-sm-3 col-form-label">Rol adı</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="role_name" id="role-name"
                                       value="{{$role->name}}"
                                       placeholder="rol adı" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group text-center"><h3>İcazələr</h3></div>
                        @foreach($role->permissions as $permission)
                            <div class="form-group row mt-2">
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="restaurants[]"
                                               class="custom-control-input"
                                               id="id-{{$permission->id}}"
                                               value="{{$permission->id}}"
                                               checked
                                        >
                                        <label class="custom-control-label" for="id-{{$permission->id}}">{{$permission->name}}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach($unassosiated_permissions as $permission)
                            <div class="form-group row mt-2">
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="restaurants[]"
                                               class="custom-control-input"
                                               id="id-{{$permission->id}}"
                                               value="{{$permission->id}}"
                                        >
                                        <label class="custom-control-label" for="id-{{$permission->id}}">{{$permission->name}}</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="btn-list text-center mt-4">
                            <button class="btn btn-block btn-outline-success save-group" type="submit">Yenilə</button>
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
@endsection
