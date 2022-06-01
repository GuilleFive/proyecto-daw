@extends('layouts.app')
@section('content')

    <div class="container ">
        <h2>{{__($form.' producto')}}</h2>
        <div class="d-flex justify-content-center">
            <form action="{{isset($product)?route('products.change'):route('products.post')}}" method="POST" class="col-md-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{__('Nombre')}}</label>
                    <input type="text" name="name" class="form-control @error('name')is-invalid @enderror"
                           @isset($product)
                           value="{{old('name')?old('name'):$product->name}}"
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
                    <textarea name="description" class="form-control @error('description')is-invalid @enderror" id="description">@isset($product){{old('description')?trim(old('description')):trim($product->description)}} @else{{trim(old('description'))}}@endisset</textarea>
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
                           @isset($product)
                           value="{{old('stock')?old('stock'):$product->stock}}"
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
                <div class="mb-3">
                    <label for="" class="form-label">{{__('Categoría')}}</label>
                    <select name="category" class="form-select" id="category">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}"
                                    @if($category->id == old('category') || (isset($product) && $category->id == "$product->category_id"))
                                    selected="selected"
                                    @endif
                                    name="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">{{__('Precio (€)')}}</label>
                    <input type="number" min="3" max="5000.99" step="0.01" name="price"
                           class="form-control @error('price')is-invalid @enderror"
                           @isset($product)
                           value="{{old('price')?old('price'):$product->price}}"
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
                @isset($product)
                <input type="hidden" name="id" value="{{$product->id}}">
                @endisset
                <button type="submit" class="btn btn-primary button-primary-dark">{{isset($product)?__('Editar'):__('Añadir')}}</button>
                <a href="{{url()->previous() !== route('products.create')?url()->previous():route('home')}}"
                class="btn btn-secondary">{{__('Volver')}}</a>
                <div class="mb-5">
                </div>
            </form>
        </div>
    </div>
@endsection
