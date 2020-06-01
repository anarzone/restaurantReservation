@extends('admin.layouts.app')
@section('page-title', 'Şəkil yüklə')
@section('css')
    <link rel="stylesheet" href="{{asset('back/dist/css/dropzone/dropzone.min.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <select class="custom-select mr-sm-2" id="halls">
                                <option disabled selected value>-- Zal seç --</option>
                                @foreach($halls as $hall)
                                    <option value="{{$hall->id}}">{{$hall->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5 text-center">
                            <div class="dropzone" style="display: none"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-9">
                            <button id="next-page" class="btn btn-block btn-primary" disabled>Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('back/dist/js/dropzone/dropzone.min.js')}}"></script>
    <script>
        let hall_id = null;
        let current_hall_id = null;

        $('#halls').on('change', function () {
            hall_id = $(this).val()
            $('.dropzone').show()
        })

        Dropzone.autoDiscover = false;
        let myDropzone = new Dropzone(".dropzone", {
            url: '{{route('admin.plan.images.upload')}}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            // previewTemplate: document.getElementById('template-preview').innerHTML,
            dictDefaultMessage: "Şəkil yükləmək üçün klikləyin və ya şəkli bura daşıyın",
            maxFiles:1,
            init: function() {
                this.hiddenFileInput.removeAttribute('multiple');
                this.on("maxfilesexceeded", function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
                this.on("sending", function(file, xhr, formData) {
                    formData.append("hall_id", hall_id);
                });
            },
            acceptedFiles: ".jpg, .jpeg, .png",
            addRemoveLinks: false,
            paramName: 'plan_image',
            success: function (file, response) {
                current_hall_id = response.data.hall_id
                console.log(current_hall_id)
                $('#next-page').removeAttr('disabled')
            },
            error: function (file, response) {
                console.log(response)
            }
        })

        $('#next-page').on('click', function () {
            if(current_hall_id){
                location.href = '/admin/halls/'+current_hall_id+'/plan/create'
            }
        })
    </script>
@endsection
