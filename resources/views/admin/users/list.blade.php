@extends('layouts.app')
@section('content')
    <div class="container pb-5">
        <h2 class="mb-4">{{__('Lista de clientes')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Nombre')}}</th>
                <th>{{__('Usuario')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Teléfono')}}</th>
                <th>{{__('Dirección')}}</th>
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
                ajax: "{{ route('users.list') }}",
                fnDrawCallback: finishDrawing,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'address', name: 'address'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            function finishDrawing() {
                document.querySelectorAll('.users-delete-btn').forEach(element => {
                    element.addEventListener('click', () => openDeleteModal(element.dataset.user));
                });

                dataTable.button(0).enable(true);
                dataTable.button(1).enable(true);
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
                        }).success(
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


        </script>
    @endpush
@endsection
