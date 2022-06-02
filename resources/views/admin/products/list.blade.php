@extends('layouts.app')
@section('content')
    <div class="container-fluid px-5 pb-5">
        <a href="{{route('products.create')}}" class="btn btn-outline-primary button-primary-outline-dark float-end"><i
                class="fa fa-plus-circle"> </i>{{__(' Añadir producto')}}</a>
        <h2 class="mb-4">{{__('Lista de productos')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">

            const table = $('.yajra-datatable').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.list') }}",
                fnDrawCallback: assignEventListener,
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'stock', name: 'stock'},
                    {data: 'category', name: 'category'},
                    {data: 'price', name: 'price'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            })

            function assignEventListener() {
                document.querySelectorAll('.products-delete-btn').forEach(element => {
                    element.addEventListener('click', () => openDeleteAlert(element.id));
                });
            }

            function openDeleteAlert(id) {
                Swal.fire({
                    title: '¿Desea eliminar este producto?',
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
                            url: 'products/delete/' + id,
                            type: 'DELETE',
                        }).then(
                            $('.yajra-datatable').DataTable().draw().then(
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Producto eliminado',
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
