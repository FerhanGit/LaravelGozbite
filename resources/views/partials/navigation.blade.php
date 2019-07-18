<div class="links col-12 d-flex align-bottom">
    <a class="link px-2" href="{{ route('home') }}">{{ __('Home') }}</a>

    <a class="link px-2" href="{{ route('recipe.list') }}">{{ __('Recipes') }}</a>

    <a class="link px-2" href="{{ route('login') }}">{{ __('Link 2') }}</a>

    <a class="link px-2" href="{{ route('login') }}">{{ __('Link 3') }}</a>

    {{ $slot }}
</div>
