@extends('layouts.app')
@section('content')
    <div class="container pb-5">
        <a href="{{route('products.create')}}" class="btn btn-outline-primary button-primary-outline-dark float-end"><i
                class="fa fa-plus-circle"> </i>{{__(' Añadir producto')}}</a>
        <h2 class="mb-4">{{__('Lista de productos')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Nombre')}}</th>
                <th>{{__('Descripción')}}</th>
                <th>{{__('Stock')}}</th>
                <th>{{__('Categoría')}}</th>
                <th>{{__('Precio')}}</th>
                <th>{{__('Acción')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script type="text/javascript">

            @if(session()->get('done'))
            Swal.fire({
                icon: 'success',
                title: "{{session()->get('done')}}",
                showConfirmButton: false,
                timer: 1100,
                color: '#dee2e6',
                iconColor: '#85ff3e',
                background: '#24292d',
            });
            @endif

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
                ajax: "{{ route('products.list') }}",
                fnDrawCallback: finishDrawing,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'stock', name: 'stock'},
                    {data: 'category', name: 'product_category_id'},
                    {data: 'price', name: 'price'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            function finishDrawing() {
                document.querySelectorAll('.products-delete-btn').forEach(element => {
                    element.addEventListener('click', () => openDeleteModal(element.dataset.product));
                });
                dataTable.button(0).enable(true);
                dataTable.button(1).enable(true);
            }

            function openViewModal(product) {
                const oProduct = JSON.parse(product);
                Swal.fire({
                    title: oProduct.name,
                    icon: 'info',
                    text: '',
                    confirmButtonColor: '#2891de',
                    confirmButtonText: 'Cerrar',
                    color: '#dee2e6',
                    iconColor: '#2891de',
                    background: '#24292d',
                })
            }



            function openDeleteModal(product) {
                const oProduct = JSON.parse(product);
                Swal.fire({
                    title: '¿Desea eliminar este producto?',
                    html: `ID: ${oProduct.id}<br><br>Nombre: ${oProduct.name}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2891de',
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
                            url: '{{route('products.delete')}}',
                            type: 'DELETE',
                            data: {
                                'product': product
                            },
                        }).success(
                            () => {
                                dataTable.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Producto eliminado',
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
                                })
                            })
                    }
                })
            }
        </script>

    @endpush
@endsection
