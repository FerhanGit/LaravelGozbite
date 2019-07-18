@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card  justify-content-center">
                <div class="card-header align-content-center">{{ __('Recipe Index') }}</div>

                <div class="card-body">

                    @if($categories)
                        @foreach($categories as $category)
                            <div class="col-md-4 mt-3 pl-0 btn button-blue">
                                <a class="nav-link btn btn-primary" href="{{ route('recipe.list', ['category' => $category->category]) }}">
                                    {{ $category->category }}
                                </a>
                            </div>
                        @endforeach
                    @endif

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
                                {{ $recipes->links() }}
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
