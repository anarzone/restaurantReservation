@extends('admin.layouts.app')
@section('page-title','Zallar')
@section('content')
    <div class="row">
        <div class="col-8">
            <div class="list-goup">
                @foreach($halls as $hall)
                    <a href="{{route('admin.halls.edit', [
                                'hall' => $hall->id,
                                'has_reservation' => isset($hall->reservations[0]) ? 1:0
                            ])}}
                    ">

                        <li class="list-group-item {{ isset($hall->reservations[0]) ? 'list-group-item-success': 'list-group-item-secondary'}}">
                            {{$hall->name}}
                        </li>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
