@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card justify-content-center">
                <div class="card-header align-content-center">{{ __('Recipe Index') }}</div>

                <div class="card-body">

                    <div class="btn-group mb-4">
                        @if($categories)
                            @foreach($categories as $category)

                                @foreach($users as $user)
                                    <label class="btn btn-secondary btn-light  {{  null != request('category', null) &&  null != request('user', null) && request('category') == $category->category && request('user') == $user->id ? 'active': '' }}">
                                        <a class="nav-link" href="{{ route('recipe.category.user.list', ['category' => $category->category, 'user' => $user->id]) }}"> {{ $category->category }} by {{ $user->name }}</a>
                                    </label>
                                @endforeach

                                <label class="btn btn-secondary btn-light  {{ null != request('category', null) && null == request('user', null) && request('category') == $category->category ? 'active': '' }}">
                                    <a class="nav-link " href="{{ route('recipe.list', ['category' => $category->category]) }}"> all {{$category->category}}</a>
                                </label>

                            @endforeach
                        @endif

                        @if($users)
                            @foreach($users as $user)
                                <label class="btn btn-secondary btn-light  {{ null != request('user', null) && null == request('category', null)  && request('user') == $user->id ? 'active': '' }}">
                                    <a class="nav-link" href="{{ route('recipe.user.list', ['user' => $user->id]) }}"> all by {{ $user->name }}</a>
                                </label>
                            @endforeach
                        @endif
                </div>
                    @if($recipes)
                        <table class="table-striped table-bordered w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Recipe title</th>
                                <th>Recipe category</th>
                                <th>Recipe content</th>
                                <th>Recipe User Id</th>
                                <th>Recipe User name</th>
                                <th>Recipes of that User</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($recipes as $recipe)
                                    <tr>
                                        <td>{{ $recipe->id }} </td>
                                        <td><a href="{{ route('recipe.show', ['recipe' => $recipe->id]) }}"> {{ $recipe->title }} </a></td>
                                        <td>{{ $recipe->category }} </td>
                                        <td>{{ $recipe->content }} </td>
                                        <td> <a href="{{ route('home') }}"> {{ $recipe->user->id }} </a> </td>
                                        <td><a href="{{ route('home') }}"> {{ $recipe->user->name }} </a> </td>
                                        <td>{{ \App\User::find($recipe->user->id)->recipes->count() }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($recipes->links())
                            <div class="col-md-4 mt-3 pl-0">
                                {{ $recipes->onEachSide(5)->links() }}
                            </div>
                        @endif
                        
                    @else
                        <p> No users found..</p>
                    @endif

                    <div class="col-md-4 mt-3 pl-0">
                        <a class="nav-link btn btn-primary" href="{{ route('recipe.create') }}"> {{ __('Create new Recipe') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
