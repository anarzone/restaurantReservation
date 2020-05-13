@extends('admin.layouts.app')
@section('page-title','Restoranlar')
@section('content')
    <div class="row">
        <div class="col-8">
            <div class="list-goup">
                @foreach($restaurants as $rest)
                    <a href="{{route('admin.restaurants.edit', $rest->id)}}">
                        <li class="list-group-item {{$rest->status ? 'list-group-item-success' : 'list-group-item-secondary'}}">
                            {{$rest->name}}
                        </li>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
