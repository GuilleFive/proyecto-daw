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
                <th>{{__('Nº de Productos')}}</th>
                <th>{{__('Destinatario')}}</th>
                <th>{{__('Dirección')}}</th>
                <th>{{__('Código Postal')}}</th>
                <th>{{__('Método Pago')}}</th>
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
                order: [6, 'desc'],
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    url: "{{ route('orders.list.client') }}",
                    type: 'POST',
                    data: {
                        'startDate': () => document.querySelector('.start-date').value,
                        'endDate': () => document.querySelector('.end-date').value,
                    },
                },
                fnDrawCallback: settings => {
                    finishDrawing();
                    if (settings._iDisplayLength > settings.fnRecordsDisplay() || isNaN(settings.fnRecordsDisplay())) {
                        $(settings.nTableWrapper).find('.dataTables_paginate').hide();
                    } else {
                        $(settings.nTableWrapper).find('.dataTables_paginate').show();
                    }
                },
                columns: [
                    {data: 'product', name: 'product'},
                    {data: 'receiver', name: 'receiver'},
                    {data: 'address', name: 'address'},
                    {data: 'postal_code', name: 'postal_code'},
                    {data: 'payment_method', name: 'payment_method'},
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
                document.querySelectorAll('.orders-deliver-btn').forEach(element => {
                    element.addEventListener('click', () => openDeliverModal(element.dataset.order));
                });

                document.querySelectorAll('.orders-deliver-btn').forEach(element => {
                    element.addEventListener('click', () => openDeliverModal(element.dataset.order));
                });

                document.querySelectorAll('.orders-cancel-btn').forEach(element => {
                    element.addEventListener('click', () => openCancelModal(element.dataset.order));
                });

                dataTable.button(0).enable(true);
                dataTable.button(1).enable(true);
                dataTable.button(2).enable(true);
            }

            function openCancelModal(order) {
                const oOrder = JSON.parse(order);
                Swal.fire({
                    title: '¿Desea cancelar este pedido?',
                    html: `ID: ${oOrder.id}<br><br>Destinatario: ${oOrder.address.receiver_name}<br><br>Fecha del pedido: ${oOrder.order_date}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2891de',
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
                            url: '{{route('orders.cancel')}}',
                            type: 'DELETE',
                            data: {
                                'order': oOrder.id
                            },
                        }).success(
                            () => {
                                dataTable.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pedido cancelado',
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
