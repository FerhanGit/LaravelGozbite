@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Recipe Show') }}</div>

                <div class="card-body">

                    <div class="col-md-3 mb-4">
                        <a class="nav-link  btn btn-secondary" href="{{ redirect()->back()->getTargetUrl() }}"> {{ __('<< Back') }}</a>
                    </div>

                    @if($recipe)
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-2">
                                    {{ $recipe->id  }}
                                </div>
                                <div class="col-md-2">
                                    {{ $recipe->title  }}
                                </div>
                                <div class="col-md-2">
                                    {{ $recipe->category  }}
                                </div>
                                <div class="col-md-2">
                                    {{ $recipe->user->name  }}
                                </div>
                                <div class="col-md-4">
                                    {{ $recipe->created_at  }}
                                </div>
                                <div class="col-md-4  mt-4">
                                    <a class="nav-link btn btn-primary btn-sm" href="{{ route('recipe.edit', ['recipe' => $recipe->id]) }}"> {{ __('Edit Recipe') }}</a>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <form method="POST" action="{{ route('recipe.destroy', ['recipe' => $recipe->id]) }}">
                                        @csrf
                                        @method('DELETE')

                                        <div class="form-group row mb-0">
                                            <button type="submit" class="btn btn-danger"  onclick="return confirm('Are you sure you wish to delete?')">
                                                {{ __('Delete Recipe') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-12  mt-4">
                                    @if($recipe->main_image)
                                        <img class="col-md-12" src='{{ asset('storage/images/recipe/'.$recipe->main_image->name) }}'>
                                    @endif
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                @foreach($recipe->recipeImages as $image)
                                    <div class="col-md-6  mt-4">
                                        <img src='{{ asset('storage/images/recipe/thumbnail/'.$image->name_thumb) }}'>
                                        <div class="col-md-6">
                                            <form method="POST" action="{{ route('recipe.delete.image', ['recipeImage' => $image->id]) }}">
                                                @csrf
                                                @method('DELETE')

                                                <div class="form-group row mb-0">
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you wish to delete?')">
                                                        {{ __('Delete Image') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    @else
                        <p> No users found..</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
