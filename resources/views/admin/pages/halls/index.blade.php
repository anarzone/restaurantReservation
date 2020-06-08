@extends('admin.layouts.app')
@section('page-title','Zallar')
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
                            @if(request()->has('restaurant'))
                                <a href="{{route('admin.halls.create', ['restaurant' => request('restaurant')])}}" class="btn btn-sm btn-success">Yeni zal</a>
                            @else
                                <a href="{{route('admin.halls.create')}}" class="btn btn-sm btn-success">Yeni zal</a>
                            @endif
                        </div>
                    </div>
                </div>
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ad</th>
                            <th scope="col">Restoran</th>
                            <th scope="col">Masalar</th>
                            <th scope="col">Əməliyyat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($halls as $hall)
                            <tr class="{{isset($hall->reservations[0]) ? 'list-group-item-success' : 'list-group-item-secondary'}}">
                                <th scope="row">{{ $hall->id }}</th>
                                <td>{{ $hall->name }}</td>
                                <td>
                                    {{$hall->restaurant->name}}
                                </td>
                                <td>
                                    <a href="{{route('admin.restaurants.index', [
                                                    'hall' => $hall->id,
                                                    'restaurant' => $hall->restaurant->id
]                                               )}}" class="btn btn-sm btn-info">
                                        Masalar <i class="fas fa-external-link-square-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{route('admin.halls.edit', [
                                                'hall' => $hall->id,
                                                'has_reservation' => isset($hall->reservations[0]) ? 1:0
                                            ])}}
                                    ">Redaktə et <i class="far fa-edit"></i></a>
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
        toastr.options = {
            "preventDuplicates": true,
            "positionClass": "toast-top-center",
        }

        @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{$error}}');
            @endforeach
        @endif
    </script>
@endsection
