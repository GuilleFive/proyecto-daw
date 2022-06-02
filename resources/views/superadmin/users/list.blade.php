@extends('layouts.app')
@section('content')
    <div class="container-fluid px-5 pb-5">
        <h2 class="mb-4">{{__('Lista de usuarios')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('Nombre')}}</th>
                <th>{{__('Usuario')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Teléfono')}}</th>
                <th>{{__('Dirección')}}</th>
                <th>{{__('Rol')}}</th>
                <th>{{__('Acción')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
                var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.list') }}",
                    fnDrawCallback: assignEventListener,
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'username', name: 'username'},
                        {data: 'email', name: 'email'},
                        {data: 'phone', name: 'phone'},
                        {data: 'address', name: 'address'},
                        {data: 'role', name: 'role'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        },
                    ]
                });

                function assignEventListener() {
                    document.querySelectorAll('.users-delete-btn').forEach(element => {
                        element.addEventListener('click', () => openDeleteAlert(element.id));
                    });
                }

                function openDeleteAlert(id) {
                    Swal.fire({
                        title: '¿Desea eliminar este usuario?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Eliminar',
                        color: '#dee2e6',
                        iconColor: '#ff852d',
                        background: '#24292d',
                    }).then((result) => {

                        if (result.isConfirmed) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                                }
                            });

                            $.ajax({
                                url: 'users/delete/' + id,
                                type: 'DELETE',
                            }).then(
                                $('.yajra-datatable').DataTable(

                                ).draw().then(
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Usuario eliminado',
                                        showConfirmButton: false,
                                        timer: 1100,
                                        color: '#dee2e6',
                                        iconColor: '#85ff3e',
                                        background: '#24292d',
                                    })
                                )
                            )
                        }
                    })
                }
        </script>
    @endpush
@endsection
