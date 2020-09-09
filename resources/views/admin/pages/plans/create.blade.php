@extends('admin.layouts.app')
@section('page-title', 'Plan yarat')
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
            background: black;
            overflow: hidden;
        }

        .imagemaps-wrapper{
            width: 1000px;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="imagemaps-wrapper">
                    <img class="hall-plan-image" src="{{asset('storage/back/images/'.$hall->plan->img_name)}}" draggable="false" usemap="hallmap">
                    <map class="imagemaps" name=hallmap">
                    </map>
                </div>
                <div class="imagemaps-control">
                    <fieldset>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Masa</th>
{{--                                <th scope="col">Target</th>--}}
                                <th scope="col">Sil</th>
                            </tr>
                            </thead>
                            <tbody class="imagemaps-output">
                            <tr class="item-###">
                                <th scope="row">###</th>
                                <td>
                                    <select class="form-control area-href">
                                        <option value="" selected disabled>-- Masa seç --</option>
                                        @foreach($hall->tables as $table)
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
                        <button type="button" class="btn btn-info btn-sm btn-add-map">Əlavə et</button>
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
        $(document).ready(function(){
            $('.imagemaps-wrapper').imageMaps({
                addBtn: '.btn-add-map',
                output: '.imagemaps-output',
                stopCallBack: function(active, coords){
                    console.log(active);
                    console.log(coords);
                }
            });
        })


        $('.btn-get-map').on('click', function(){
            let oParent = $(this).parent().parent().parent();
            let result  = oParent.find('.imagemaps-wrapper').clone();
            result.children('div').remove();
            alert(result.html());
        });

        $('.save-map').on('click', function () {
            let plan_details = []

            $.each($('map.imagemaps').children(), function (i, val) {
                table_id = val.href.split('/')[7]
                plan_details.push({'table_id': table_id, 'coords': val.coords})
            })

            if($('[name=imagemaps-area]').length){
                console.log(plan_details)
                $.ajax({
                    url: '{{route('manage.plans.store')}}',
                    type: 'POST',
                    data: {plan_details, plan_id: '{{$hall->plan->id}}', hall_id: '{{$hall->id}}' },
                    success: function (result) {
                        if ($.trim(result.message) === 'success'){
                            location.href = '/manage'
                        }
                    }
                })
            }
        })

    </script>
@endsection
