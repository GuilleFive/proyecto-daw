@extends('layouts.app')
@section('content')

    <div class="container ">
        <h2>{{__($form.' producto')}}</h2>
        <div class="d-flex justify-content-center">
            <form action="{{isset($productItem)?route('products.change'):route('products.post')}}" method="POST"
                  class="col-md-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{__('Nombre')}}</label>
                    <input type="text" name="name" class="form-control @error('name')is-invalid @enderror"
                           @isset($productItem)
                           value="{{old('name')?old('name'):$productItem->name}}"
                           @else
                           value="{{old('name')}}"
                           @endisset
                           id="name">

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{__('Descripción')}}</label>
                    <textarea name="description" class="form-control @error('description')is-invalid @enderror"
                              id="description">@isset($productItem){{old('description')?trim(old('description')):trim($productItem->description)}} @else{{trim(old('description'))}}@endisset</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">{{__('Stock')}}</label>
                    <input type="number" min="0" max="255" name="stock"
                           class="form-control @error('stock')is-invalid @enderror"
                           @isset($productItem)
                           value="{{old('stock')?old('stock'):$productItem->stock}}"
                           @else
                           value="{{old('stock')}}"
                           @endisset
                           id="stock">
                    @error('stock')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <label for="category" class="form-label">{{__('Categoría')}}</label>

                <div class="mb-3 d-flex justify-content-between">
                    <select name="category" class="form-select" id="category">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}"
                                    @if($category->id === old('category') || (isset($productItem) && $category->id === $productItem->product_category_id))
                                    selected="selected"
                                    @endif
                                    name="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    @can('create_products')
                        <button id="add-category" type="button"
                                class="btn btn-outline-primary button-primary-outline-dark float-end">
                            <i class="fa fa-plus-circle"></i>
                        </button>

                        <button id="remove-category" type="button"
                                class="btn btn-outline-danger float-end">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                    @endcan
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">{{__('Precio (€)')}}</label>
                    <input type="number" min="3" max="5000.99" step="0.01" name="price"
                           class="form-control @error('price')is-invalid @enderror"
                           @isset($productItem)
                           value="{{old('price')?old('price'):$productItem->price}}"
                           @else
                           value="{{old('price')}}"
                           @endisset

                           id="price">
                    @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @isset($productItem)
                    <input type="hidden" name="id" value="{{$productItem->id}}">
                @endisset
                <div class="mb-5">

                    <button type="submit"
                            class="btn btn-primary button-primary-dark">{{isset($productItem)?__('Editar'):__('Añadir')}}</button>
                    <a href="{{url()->previous() !== route('products.create')?url()->previous():route('home')}}"
                       class="btn btn-secondary">{{__('Volver')}}</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @can('create_products')
            <script>
                document.querySelector('#add-category').addEventListener('click', openModalNewCategory);
                document.querySelector('#remove-category').addEventListener('click', () => openModalRemoveCategory(document.querySelector('#category').value));

                function openModalNewCategory() {
                    Swal.fire({
                        title: 'Nueva categoría',
                        html: `<div class="mb-2">
                            <label for="category-name" class="form-label">{{__('Nombre')}}</label>
                            <input type="text" name="category-name" class="form-control" id="category-name">
                            </div>

                            <div class="mb-2">
                            <label for="category-description" class="form-label">{{__('Descripción')}}</label>
                            <textarea name="category-description" class="form-control" id="category-description"></textarea>
                            </div>`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#2891de',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Añadir',
                        color: '#dee2e6',
                        iconColor: '#2891de',
                        background: '#24292d',
                    }).then((result) => {

                        if (result.isConfirmed) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                                }
                            });

                            $.ajax({
                                url: '{{route('product_categories.post')}}',
                                type: 'POST',
                                data: {
                                    'name': document.querySelector('#category-name').value.trim(),
                                    'description': document.querySelector('#category-description').value.trim(),
                                },
                            }).success(
                                data => {
                                    document.querySelector('#category').innerHTML += data;
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Categoría creada',
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
                                        title: 'Error',
                                        text: 'Compruebe que el nombre y la descripción están rellenos',
                                        showConfirmButton: true,
                                        color: '#dee2e6',
                                        iconColor: '#d83131',
                                        background: '#24292d',
                                    })
                                })
                        }
                    })
                }

                function openModalRemoveCategory(category) {

                    const categoryOption = document.querySelector('#category').options[document.querySelector('#category').selectedIndex];

                    Swal.fire({
                        title: '¿Desea eliminar esta categoría?',
                        html: categoryOption.textContent,
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
                                url: '{{route('product_categories.delete')}}',
                                type: 'DELETE',
                                data: {
                                    'category': category
                                },
                            }).success(
                                () => {

                                    categoryOption.remove();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Categoría eliminada',
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
                    });

                }
            </script>
        @endcan
    @endpush
@endsection
