@extends('layouts.app')
@section('content')
    <div class="container pb-5">
        <h2 class="mb-4">{{__('Lista de pedidos')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Usuario')}}</th>
                <th>{{__('Nº de Productos')}}</th>
                <th>{{__('Dirección')}}</th>
                <th>{{__('Coste Total')}}</th>
                <th>{{__('Fecha pedido')}}</th>
                <th>{{__('Fecha entrega')}}</th>
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
                ajax: "{{ route('orders.list') }}",
                fnDrawCallback: () => {dataTable.button(0).enable(true); dataTable.button(1).enable(true);},
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user', name: 'user_id'},
                    {data: 'product', name: 'product_id'},
                    {data: 'address', name: 'address_id'},
                    {data: 'cost', name: 'cost'},
                    {data: 'order_date', name: 'order_date'},
                    {data: 'delivery_date', name: 'delivery_date'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        </script>
    @endpush
@endsection
