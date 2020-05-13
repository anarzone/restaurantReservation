@extends('admin.layouts.app')
@section('page-title','Rollar')
@section('content')
    <div class="row">
        <div class="col-8">
            <div class="list-goup">
                @foreach($roles as $role)
{{--                    <a href="{{route('admin.roles.edit', $role->id)}}">--}}
                        <li class="list-group-item list-group-item-secondary">
                            {{$role->name}}
                        </li>
{{--                    </a>--}}
                @endforeach
            </div>
        </div>
    </div>
@endsection
