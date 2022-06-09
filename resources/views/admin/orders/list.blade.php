@extends('layouts.app')
@section('content')
    <div class="container pb-5">
        <h2 class="mb-4">{{__('Lista de pedidos')}}</h2>

        <div class="filter-date hide float-start mb-3 ms-4 text-center ">
            <p class="h5">Filtrar por fecha de pedido</p>
            <label class="me-2">
                <input type="date" name="start-date" class="form-control form-control-sm start-date">
                Fecha de inicio
            </label>
            <label class="">
                <input type="date" name="end-date" class="form-control form-control-sm end-date">
                Fecha de fin
            </label>
        </div>

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
                dom: 'Bfrltips',
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
                },
                    {
                        extend: 'excelHtml5',
                        title: document.querySelector('.container>h2.mb-4').textContent,
                        text: '<i class="fas fa-file-excel"></i>',
                        titleAttr: 'Exportar a excel',
                        className: 'btn',
                        enabled: false,
                        exportOptions: {
                            columns: columns,
                        }
                    },
                    {
                        text: '<i class="fa fa-filter"></i>',
                        className: 'btn btn-outline-primary button-primary-outline-dark buttons-filter',
                        enabled: false,
                        action: () => {
                            document.querySelector('.filter-date').classList.toggle('hide');
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
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    url: "{{ route('orders.list') }}",
                    type: 'POST',
                    data: {
                        'startDate': () => document.querySelector('.start-date').value,
                        'endDate': () => document.querySelector('.end-date').value,
                    },
                },
                fnDrawCallback: finishDrawing,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user', name: 'user'},
                    {data: 'product', name: 'product'},
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
                ],
            });

            let startDate = document.querySelector('.end-date');
            let endDate = document.querySelector('.start-date');

            startDate.addEventListener('change', () => {
                dataTable.ajax.reload()
                endDate.setAttribute('max', startDate.value)
            })
            endDate.addEventListener('change', () => {
                dataTable.ajax.reload()
                startDate.setAttribute('min', endDate.value)

            })

            function finishDrawing() {
                document.querySelectorAll('.orders-view-btn').forEach(element => {
                    element.addEventListener('click', () => openViewModal(element.dataset.order));
                });
                dataTable.button(0).enable(true);
                dataTable.button(1).enable(true);
                dataTable.button(2).enable(true);
            }

            function openViewModal(order) {
                const oOrder = JSON.parse(order);
                let products = getProducts(oOrder);
                Swal.fire({
                    title: 'Productos',
                    icon: 'info',
                    html: products,
                    confirmButtonColor: '#2891de',
                    confirmButtonText: 'Cerrar',
                    color: '#dee2e6',
                    iconColor: '#2891de',
                    background: '#24292d',
                })
            }

            function getProducts(order) {
                let products = [];
                for (const product of order.product) {
                    products[product.name] ?
                        products[product.name]++ :
                        products[product.name] = 1;
                }

                let countProducts = '';
                for (const name of Object.keys(products)) {
                    countProducts +=`<p>${name} x ${products[name]}</p>`
                }

                return countProducts;
            }

        </script>
    @endpush
@endsection
