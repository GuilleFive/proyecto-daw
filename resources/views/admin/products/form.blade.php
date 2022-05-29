@extends('layouts.app')
@section('content')

    <div class="container ">
        <h2>{{__('Añadir nuevo producto')}}</h2>
        <div class="d-flex justify-content-center">
            <form action="{{route('products.post')}}" method="POST" class="col-md-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{__('Nombre')}}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{old('name')}}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{__('Descripción')}}</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{old('description')}}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">{{__('Stock')}}</label>
                    <input type="number" min="0" max="255" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{old('stock')}}" id="stock">
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
                            <option value="{{$category->id}}" name="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">{{__('Precio (€)')}}</label>
                    <input type="number" min="3" max="5000.99" step="0.01" name="price" class="form-control @error('price')is-invalid @enderror" value="{{old('price')}}" id="price">
                    @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{__('Añadir')}}</button>
                <a href="{{url()->previous()}}" class="btn btn-secondary">{{__('Volver')}}</a>
            </form>
        </div>
    </div>
@endsection
