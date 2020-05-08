@extends('admin.layouts.app')
@section('page-title','Qruplar')
@section('content')
    <div class="row">
        <div class="col-8">
            <div class="list-goup">
                @foreach($groups as $group)
                    <a href="{{route('admin.groups.edit', $group->id)}}">
                        <li class="list-group-item {{$group->status === 1 ? 'list-group-item-success' : "list-group-item-secondary"}}">
                            {{$group->group_name}}
                        </li>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
