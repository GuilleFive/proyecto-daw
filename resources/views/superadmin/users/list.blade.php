@extends('layouts.app')
@section('content')
    <div class="container pb-5">
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
            const table = $('.yajra-datatable').DataTable({
                scrollX: true,
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
                    element.addEventListener('click', () => openDeleteModal(element.dataset.user));
                });
            }

            function openDeleteModal(user) {
                const oUser = JSON.parse(user);
                Swal.fire({
                    title: '¿Desea eliminar este usuario?',
                    html: `ID: ${oUser.id}<br><br>Nombre: ${oUser.name}`,
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
                            url: '{{route('users.delete')}}',
                            type: 'DELETE',
                            data: {
                                "user": user,
                            }
                        }).done(
                            () => {
                                $('.yajra-datatable').DataTable().draw();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Usuario eliminado',
                                    showConfirmButton: false,
                                    timer: 1100,
                                    color: '#dee2e6',
                                    iconColor: '#85ff3e',
                                    background: '#24292d',
                                });
                            }).fail(
                            () => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de conexión',
                                    text: 'Inténtelo de nuevo',
                                    showConfirmButton: true,
                                    color: '#dee2e6',
                                    iconColor: '#d83131',
                                    background: '#24292d',
                                });
                            })
                    }
                })
            }
        </script>
    @endpush
@endsection
