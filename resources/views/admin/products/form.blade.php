@extends('layouts.app')
@section('content')

    <div class="container col-md-5 justify-content-center align-items-center">
        <form>
            <div class="form-outline mb-3">
                <label for="name" class="form-label">{{__('Nombre')}}</label>
                <input type="text" name="name" class="form-control" id="name">
            </div>
            <div class="form-outline mb-3">
                <label for="description" class="form-label">{{__('Descripción')}}</label>
                <textarea name="description" class="form-control" id="description"></textarea>
            </div>
            <div class="form-outline mb-3">
                <label for="stock" class="form-label">{{__('Stock')}}</label>
                <input type="number" name="stock" class="form-control" id="stock">
            </div>
            <div class="form-outline mb-3">
                <label for="category" class="form-label">{{__('Categoría')}}</label>
                <select name="category" class="form-select form" id="category">
                    @foreach($categories as $category)
                        <option id="{{$category->id}}" name="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-outline mb-3">
                <label for="price" class="form-label">{{__('Precio (€)')}}</label>
                <input type="number" step="0.01" name="price" class="form-control" id="price">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
