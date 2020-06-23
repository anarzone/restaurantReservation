@extends('admin.layouts.app')
@section('css')
    <!-- This page plugin CSS -->
    <link href="{{asset('back/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endsection
@section('content')
    <!-- basic table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered no-wrap">
                            <thead>
                            <tr>
                                <th>Ad</th>
                                <th>Email</th>
                                <th>Əməliyyat</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <a
                                            href="#"
                                            type="button"
                                            class="btn btn-rounded btn-outline-secondary btn-sm editUserInfo"
                                            data-toggle="modal"
                                            data-target="#edit-user-info"
                                            data-user-id="{{$user->id}}"
                                            data-user-name="{{$user->name}}"
                                            data-user-email="{{$user->email}}"
                                            data-role-id="{{ isset($user->roles[0]) ? $user->roles[0]->id : 0}}"
                                            data-group-id="{{ isset($user->groups[0]) ? $user->groups[0]->id : 0 }}"
                                        >
                                            Redaktə et
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Ad</th>
                                <th>Email</th>
                                <th>Əməliyyat</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- changeReservationStatus modal content -->
    <div id="edit-user-info" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h4 id="hall_name">Profil Redaktə</h4>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Ad</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" placeholder="istifadəçi adı">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" placeholder="aktiv email olmalıdır">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Rol</label>
                        <div class="col-sm-10">
                            <select class="custom-select mr-sm-2 form-control" id="roles" >
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Qrup</label>
                        <div class="col-sm-10">
                            <select class="custom-select mr-sm-2 form-control" id="groups">
                            </select>
                        </div>
                    </div>

                    <div class="btn-list text-center mt-4">
                        <button class="btn btn-rounded btn-outline-success save-user" type="button">Yadda saxla</button>
                        <button class="btn btn-rounded btn-outline-dark" data-dismiss="modal" type="button">Ləğv et</button>
                        <button class="btn btn-rounded btn-outline-danger delete-user" data-dismiss="modal" type="button">Sil</button>
                    </div>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- order table -->
@endsection

@section('js')
    <!--This page plugins -->
    <script src="{{asset('back/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('back/dist/js/pages/datatables/datatable-basic.init.js')}}"></script>
    <script>
        @if(session('message-delete'))
            displayMessage("{{session('message-delete')}}" ,'error')
        @elseif(session('message-success'))
            displayMessage("{{session('message-success')}}")
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        let user_id = null;
        let name = null;
        let role_id = null;
        let group_id = null;
        let email = null;

        $('.editUserInfo').on('click',function(event){
            $('#roles').empty();
            $('#groups').empty();

            user_id = $(this).data('user-id');
            name = $(this).data('user-name');
            email = $(this).data('user-email');
            role_id = $(this).data('role-id');
            group_id = $(this).data('group-id');

            if('{{auth()->user()->roles[0]->name}}' === 'super-admin' && parseInt('{{auth()->user()->id}}') === parseInt(user_id)){
                $('#roles').attr('disabled', true)
            }

            $('#name').attr('value', name);
            $('#email').attr('value', email);

            $.ajax({
                type: 'GET',
                url: "/manage/getRolesAndGroups",
                success: function (result) {
                    if($.trim(result.data)){
                        $.each(result.data.roles, function(key, val){
                            let val_with_selected = val.id === role_id ? 'value="'+ val.id + '" selected' : 'value="' + val.id + '"';
                            $('#roles').append('' +
                                '<option ' +
                                val_with_selected +
                                '>'+ val.name +
                                '</option>'
                            )
                        })

                        $('#groups').append('' +
                            '<option>-- Qrup seçin --</option>'
                        )

                        $.each(result.data.groups, function(key, val){
                            let val_with_selected = val.id === group_id ? 'value="'+ val.id + '" selected' : 'value="' + val.id + '"';
                            $('#groups').append('' +
                                '<option ' +
                                val_with_selected +
                                '>'+ val.group_name +
                                '</option>'
                            )
                        })
                    }
                }
            })

        })

        $('#name').on('change', function () {
            name = $(this).val();
        })
        $('#email').on('change', function () {
            email = $(this).val();
        })


        $('#roles').on('change', function () {
            role_id = $(this).val();
        })
        $('#groups').on('change', function () {
            group_id = $(this).val();
        })

        $('.save-user').on('click', function () {
            if(user_id && email){
                $.ajax({
                    type: 'POST',
                    url: '/users/update/',
                    data: {user_id, role_id, group_id, name, email},
                    success: function (result) {
                        if($.trim(result.data)){
                            location.reload();
                        }
                    },
                    error: function (data) {
                        let response = data.responseText
                        $.each(response.errors, function (key, val) {

                        })
                    }
                })
            }
        })

        $('.delete-user').on('click', function () {
            deleteEl(
                {},
                '/users/destroy/' + user_id,
                'Silmək istədiyinizdən əminsiniz?',
                '/manage/users/index'
            )
        })

    </script>
@endsection
