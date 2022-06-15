@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header h3">{{ 'Perfil' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.edit_profile') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" title="No puedes cambiar tu nombre"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input type="text" id="name" title="No puedes cambiar tu nombre" disabled
                                           class="form-control"
                                           value="{{old('name')?old('name') :$user->name }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="username"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>

                                <div class="col-md-6">
                                    <input id="username" type="text"
                                           class="form-control @error('username') is-invalid @enderror" name="username"
                                           value="{{old('username')?old('username') :$user->username }}" readonly
                                           required autocomplete="username"
                                           autofocus>

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{old('email')?old('email') :$user->email }}" readonly required
                                           autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Phone number') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" type="tel"
                                           class="form-control @error('phone') is-invalid @enderror" name="phone"
                                           value="{{old('phone')?old('phone') :$user->phone }}" readonly required
                                           autocomplete="phone" autofocus>

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="passwords-container hide">
                                <div class="row mb-3 mb-5">
                                    <label for="new_password"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Nueva contraseña') }}</label>

                                    <div class="col-md-6">
                                        <input id="new_password" type="password"
                                               class="form-control  @error('new_password') is-invalid @enderror"
                                               name="new_password" autocomplete="new_password">

                                        @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="current_password"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Contraseña actual') }}</label>

                                    <div class="col-md-6">
                                        <input id="current_password" type="password"
                                               class="form-control  @error('current_password') is-invalid @enderror"
                                               name="current_password"
                                               required autocomplete="current_password">

                                        @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-around">
                                <div class="buttons-container">
                                    <button type="button" class="btn button-primary-dark edit">
                                        {{ __('Editar') }}
                                    </button>
                                    <button type="button" id="delete-account"
                                            class="btn btn-danger">{{__('Eliminar cuenta')}}</button>
                                </div>
                                <div class="buttons-container d-none">
                                    <button type="submit" class="btn button-primary-dark">
                                        {{ __('Guardar') }}
                                    </button>
                                    <button type="button" class="btn btn-secondary toggle-edit cancel">
                                        {{ __('Cancelar') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer>

            @error('current_password') shared()
            @enderror
            @error('new_password') shared()
            @enderror

            document.querySelector('.edit').addEventListener('click', () => {
                shared();
            })


            document.querySelector('.cancel').addEventListener('click', () => {
                shared();

                document.querySelector('#username').value = "{{$user->username}}";
                document.querySelector('#email').value = "{{$user->email}}";
                document.querySelector('#phone').value = "{{$user->phone}}";
            })

            function shared() {
                document.querySelectorAll('.buttons-container').forEach(element => {
                    element.classList.toggle('d-none');
                })

                document.querySelectorAll('.form-control:not(input[disabled], #new_password, #current_password)').forEach(element => {
                    element.toggleAttribute('readonly')
                })

                document.querySelector('.passwords-container').classList.toggle('hide');

                document.querySelector('.card-header').textContent = document.querySelector('.passwords-container').classList.contains('hide') ? 'Perfil' : 'Editar Perfil'
            }

            @if(session()->get('success'))
            Swal.fire({
                icon: 'success',
                title: "{{session()->get('success')}}",
                showConfirmButton: false,
                timer: 1100,
                color: '#dee2e6',
                iconColor: '#85ff3e',
                background: '#24292d',
            });
            localStorage.clear();
            @endif

            document.querySelector('#delete-account').addEventListener('click', () =>openDeleteAccountModal());

            function openDeleteAccountModal() {
                Swal.fire({
                    title: '¿Desea borrar su cuenta?',
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
                            url: '{{route('users.delete_account')}}',
                            type: 'DELETE',

                        }).success(location.href = '{{route('home')}}')

                            .fail(
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
