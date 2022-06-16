<footer class="footer__distributed">

    <div class="footer__right">
        <a href="https://www.linkedin.com/in/guillermoromeroaguilar/" target="__blank"><i class="fab fa-linkedin"></i></a>
        <a href="https://github.com/GuilleFive" target="__blank"><i class="fab fa-github"></i></a>
    </div>

    <div class="footer__left">

        <p class="footer__links">
            <a class="link-1" href="{{route('home')}}">{{__('Home')}}</a>

            <a href="{{route('users.profile')}}">{{__('Perfil')}}</a>

            <a href="{{route('orders.mine')}}">{{__('Pedidos')}}</a>

            <a href="{{route('cart')}}">{{__('Carrito')}}</a>
        </p>

        <p>Retkon Hardware &copy; {{now()->year}}</p>
    </div>

</footer>
