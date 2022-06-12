@extends('layouts.app')
@section('content')
    <div class="container pb-5">
        <h2 class="mb-4">{{__('Lista de usuarios')}}</h2>
        <div class="form-check form-switch mb-1">
            <input class="form-check-input" disabled type="checkbox" id="deleted-users">
            <label class="form-check-label" for="deleted-users">{{__('Mostrar usuarios eliminados')}}</label>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" disabled type="checkbox" id="admin-users">
            <label class="form-check-label" for="admin-users">{{__('Mostrar solo administradores')}}</label>
        </div>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Nombre')}}</th>
                <th>{{__('Usuario')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Teléfono')}}</th>
                <th>{{__('Rol')}}</th>
                <th>{{__('Acción')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script type="text/javascript">

            const table = $('.yajra-datatable');
            let columns = [];
            for (let i = 0; i < table[0].rows[0].cells.length - 1; i++)
                columns[i] = i;

            const dataTable = table.DataTable({
                dom: 'Bfrltip',
                buttons: [{
                    extend: 'pdfHtml5',
                    title: document.querySelector('.container>h2.mb-4').textContent,
                    text: '<i class="fa fa-file-pdf"></i>',
                    titleAttr: 'Exportar a pdf',
                    className: 'btn',
                    enabled: false,
                    exportOptions: {
                        columns: columns,
                        customize: function (doc) {
                            doc.content[1].margin = [100, 0, 100, 0]
                        },
                    }
                }, {
                    extend: 'excelHtml5',
                    title: document.querySelector('.container>h2.mb-4').textContent,
                    text: '<i class="fas fa-file-excel"></i>',
                    titleAttr: 'Exportar a excel',
                    className: 'btn',
                    enabled: false,
                    exportOptions: {
                        columns: columns,
                    }
                }],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'Todos'],
                ],
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.list') }}",
                    data: {
                        'deleted': () => document.querySelector('#deleted-users').checked,
                        'admins':() => document.querySelector('#admin-users').checked,
                    }
                },
                fnDrawCallback: finishDrawing,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'role', name: 'role'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            function finishDrawing() {
                document.querySelectorAll('.users-delete-btn').forEach(element => {
                    element.addEventListener('click', () => openDeleteModal(element.dataset.user));
                });
                document.querySelectorAll('.users-change-btn').forEach(element => {
                    element.addEventListener('click', () => openChangePowerModal(element.dataset.user));
                });

                document.querySelectorAll('.users-restore-btn').forEach(element => {
                    element.addEventListener('click', () => openRestoreModal(element.dataset.user));
                });
                document.querySelectorAll('.users-force-delete-btn').forEach(element => {
                    element.addEventListener('click', () => openForceDeleteModal(element.dataset.user));
                });

                document.querySelector('#deleted-users').disabled = false;
                document.querySelector('#admin-users').disabled = false;
                dataTable.button(0).enable(true);
                dataTable.button(1).enable(true);
            }

            document.querySelector('#deleted-users').addEventListener('change', () => {
                dataTable.ajax.reload()
            });
            document.querySelector('#admin-users').addEventListener('change', () => {
                dataTable.ajax.reload()
            });



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
                                dataTable.draw();
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

            function openRestoreModal(user) {
                const oUser = JSON.parse(user);
                Swal.fire({
                    title: '¿Desea restaurar este usuario?',
                    html: `ID: ${oUser.id}<br><br>Nombre: ${oUser.name}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Restaurar',
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
                            url: '{{route('users.restore')}}',
                            type: 'PATCH',
                            data: {
                                "user": user,
                            }
                        }).done(
                            () => {
                                dataTable.draw();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Usuario restaurado',
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

            function openChangePowerModal(user) {
                const oUser = JSON.parse(user);
                Swal.fire({
                    title: oUser.roles[0].id===3?'¿Desea hacer a este cliente administrador?':'¿Desea hacer a este administrador cliente?',
                    html: `ID: ${oUser.id}<br><br>Nombre: ${oUser.name}<br><br>Rol: ${oUser.roles[0].name}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Aceptar',
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
                            url: '{{route('users.upgrade')}}',
                            type: 'PATCH',
                            data: {
                                "user": user,
                                "change": oUser.roles[0].id===3?'promote':'demote',
                            }
                        }).success(
                            () => {
                                dataTable.draw();
                                Swal.fire({
                                        icon: 'success',
                                        title: 'El usuario ya es admin ',
                                        showConfirmButton: false,
                                        timer: 1100,
                                        color: '#dee2e6',
                                        iconColor: '#85ff3e',
                                        background: '#24292d',
                                    }
                                );
                            }
                        ).fail(
                            () => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de conexión',
                                    text: 'Inténtelo de nuevo',
                                    showConfirmButton: true,
                                    color: '#dee2e6',
                                    iconColor: '#d83131',
                                    background: '#24292d',
                                })
                            })
                    }
                })
            }

            function openForceDeleteModal(user){
                const oUser = JSON.parse(user);
                Swal.fire({
                    title: '¿Desea eliminar permanentemente este usuario?',
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
                            url: '{{route('users.force_delete')}}',
                            type: 'DELETE',
                            data: {
                                "user": user,
                            }
                        }).done(
                            () => {
                                dataTable.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Usuario eliminado permanentemente',
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
