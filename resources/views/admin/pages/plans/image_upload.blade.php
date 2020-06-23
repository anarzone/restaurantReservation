@extends('admin.layouts.app')
@section('page-title', 'Şəkil yüklə')
@section('css')
    <link rel="stylesheet" href="{{asset('back/dist/css/dropzone/dropzone.min.css')}}">
    <style>
        .dropzone{
            height: 200px;
            border: dashed 1px #2746e0;
            background-color: mintcream;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <select class="custom-select mr-sm-2" id="restaurants">
                                <option disabled selected value>-- Restoran seç --</option>
                                @foreach($restaurants as $rest)
                                    <option value="{{$rest->id}}">{{$rest->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <select class="custom-select mr-sm-2" id="halls">
                            </select>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-8 col-md-8 text-center">
                            <div class="text-center dropzone-wrapper">
                                <div class="dropzone" style="display: none" id="myDropZone"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-8 col-md-8">
                            <button id="next-page" class="btn btn-block btn-primary" disabled>Növbəti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('back/dist/js/dropzone/dropzone.min.js')}}"></script>
    <script src="{{asset('back/dist/js/dry_functions.js')}}"></script>
    <script>
        let hall_id         = null;
        let current_hall_id = null;
        let plan_id         = null
        let hall_plans     = []

        @foreach($halls as $hall)
            hall_plans['{{$hall->id}}'] = '{{$hall->plan ? $hall->plan->id : null}}'
        @endforeach

        Dropzone.autoDiscover = false;

        $('#restaurants').on('change', function () {
            $('#next-page').attr('disabled', true);
            let restaurant_id = $(this).val()

            getHalls(restaurant_id)
        })

        $('#halls').on('change', function () {
            hall_id = $(this).val()
            $('.dropzone').detach()
            $('.dropzone-wrapper').append(`<div class="dropzone" style="display: none" id="myDropZone"></div>`)
            $('.dropzone').show()

            let myDropzone = new Dropzone(".dropzone", {
                url: '{{route('manage.plan.images.upload')}}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dictDefaultMessage: "Şəkil yükləmək üçün klikləyin və ya şəkli bura daşıyın",
                dictRemoveFile: 'Şəkli silin',
                maxFiles:1,
                init: function() {
                    thisDropzone = this;
                    this.hiddenFileInput.removeAttribute('multiple');
                    this.on("maxfilesexceeded", function(file)
                    {
                        this.removeAllFiles(file);
                        this.addFile(file);
                    });
                    this.on("sending", function(file, xhr, formData) {
                        formData.append("hall_id", hall_id);
                    });

                    $.get('/manage/plans/getByHallId/' + hall_id, function (response) {
                        if ($.trim(response.plan)){
                            let plan = response.plan
                            plan_id = plan.id
                            $('#next-page').removeAttr('disabled')
                            let mockFile = {name: plan.img_name, size: plan.img_size, accepted: true}

                            thisDropzone.files.push(mockFile);

                            thisDropzone.emit("addedfile", mockFile);
                            thisDropzone.emit("thumbnail", mockFile, '{{url('storage/back/images')}}/'+plan.img_name);
                            thisDropzone.emit("complete", mockFile);
                        }
                    })
                },
                acceptedFiles: ".jpg, .jpeg, .png",
                addRemoveLinks: true,
                paramName: 'plan_image',
                success: function (file, response) {
                    current_hall_id = response.data.hall_id
                    $('#next-page').removeAttr('disabled')
                },
                error: function (file, response) {
                    console.log(response)
                }
            })

            $('.dropzone')[0].dropzone.files.forEach(function(file) {
                file.previewElement.remove();
            });

            $('.dropzone').removeClass('dz-started');
            $('.dz-preview').remove();

        })

        $('#next-page').on('click', function () {
            if(hall_plans[hall_id]){
                location.href = '/manage/plans/' + plan_id + '/edit';
            }else if (current_hall_id) {
                location.href = '/manage/halls/' + current_hall_id + '/plan/create';
            }
        })

    </script>
@endsection
