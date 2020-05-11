@extends('admin.layouts.app')
@section('page-title','Zallar')
@section('content')
    <div class="row">
        <div class="col-8">
            <div class="list-goup">
                @foreach($halls as $hall)
                    <a href="{{route('admin.halls.edit', $hall->id)}}">
                        <li class="list-group-item list-group-item-success">
                            {{$hall->name}}
                        </li>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
