@extends('admin.layouts.app')
@section('page-title', 'Planı dəyişdir')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <style>
        .hall-plan-image{
            max-height:100%;
            max-width:100%;
            object-fit: cover;
            opacity: 0.4;
        }
        .imagemaps-wrapper{
            width: 100%;
            text-align: left!important;
            background: black;
            overflow: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="imagemaps-wrapper">
                    <img class="hall-plan-image" src="{{asset('storage/back/images/'.$plan->img_name)}}" draggable="false" usemap="#hallmap">
                    <map class="imagemaps" name="hallmap">
                        @foreach($planData as $key => $data)
                            <area shape="rect" name="imagemaps-area" id="{{$data->id}}" class="imagemaps-area{{$key}}" coords="{{$data->coords}}" href="{{$data->table_id}}" target="_blank">
                        @endforeach
                    </map>
                </div>
                <div class="imagemaps-control">
                    <fieldset>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Masa</th>
                                <th scope="col">Sil</th>
                            </tr>
                            </thead>
                            <tbody class="imagemaps-output">
                            <tr class="item-###">
                                <th scope="row">###</th>
                                <td>
                                    <select class="form-control area-href">
                                        <option value="" selected disabled>-- Masa seç --</option>
                                        @foreach($hall_tables as $table)
                                            <option value="{{$table->id}}">{{$table->table_number}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-delete">Sil</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <div class="px-2">
                        <button type="button" class="btn btn-info btn-sm btn-add-map">+ Masa əlavə et</button>
                        <button type="button" class="btn btn-outline-success save-map float-right">Yadda saxla</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('back/dist/js/jquery.imagemaps.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        let initial_table_ids = {};
        let end_table_ids = {};

        $(document).ready(function(){
            $('.imagemaps-wrapper').imageMaps({
                addBtn: '.btn-add-map',
                output: '.imagemaps-output',
                stopCallBack: function(active, coords){
                    // console.log(active);
                    // console.log(coords);
                }
            });
        })

        // load initial table ids
        $.each($('map.imagemaps').children(), function (i, val) {
            initial_table_ids[i] = val.href.split('/')[6]
        })
        $('.save-map').on('click', function () {
            let plan_details = []

            // prepare ajax data
            $.each($('map.imagemaps').children(), function (i, val) {
                let table_id = val.href.split('/')[6]
                if(isNaN(table_id) || Object.values(end_table_ids).includes(table_id)) return
                end_table_ids[i] = table_id

                plan_details.push({'table_id': table_id,
                    'coords': val.coords,
                    'plan_table_id': val.id,
                    'plan_id': '{{$plan->id}}',
                    'hall_id': '{{$plan->hall_id}}'
                });
            })

            // check if initial table id deleted from current one
            for(let i in initial_table_ids){
                if(initial_table_ids.hasOwnProperty(i) && Object.values(end_table_ids).includes(initial_table_ids[i])){
                    delete initial_table_ids[i]
                }
            }

            console.log(initial_table_ids)
            console.log(end_table_ids)
            if($('[name=imagemaps-area]').length){
                $.ajax({
                    url: '{{route('admin.plans.update', $plan->id)}}',
                    type: 'PUT',
                    data: {plan_details, 'deletable_tables': initial_table_ids},
                    success: function (result) {
                        if ($.trim(result.message) === 'success'){
                            // location.href = '/admin/restaurants'
                            location.reload()
                        }
                    }
                })
            }
        })

    </script>
@endsection
