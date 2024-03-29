@extends('admin.layouts.app')
@section('page-title','Restoranlar')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <span class="badge list-group-item-success">Rezervasiya var</span>
                            <span class="badge list-group-item-secondary">Rezervasiya yoxdur</span>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{route('manage.restaurants.create')}}" class="btn btn-sm btn-success">Yeni restoran</a>
                        </div>
                    </div>

                </div>
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ad</th>
                            <th scope="col">Zallar</th>
                            <th scope="col">Əməliyyat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restaurants as $rest)
                            <tr class="{{isset($rest->reservations[0]) ? 'list-group-item-success' : 'list-group-item-secondary'}}">
                                <th scope="row">{{ $rest->id }}</th>
                                <td>{{ $rest->name }}</td>
                                <td>
                                    <a href="{{url("/manage/halls/")}}/?restaurant={{$rest->id}}" class="btn btn-sm btn-info">
                                        Zallar <i class="fas fa-external-link-square-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{route('manage.restaurants.edit', $rest->id)}}">
                                        Redaktə et  <i class="far fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        @if(session('message'))
            displayMessage('{{session("message")}}')
        @endif
    </script>
@endsection
