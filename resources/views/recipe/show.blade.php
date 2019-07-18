@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Recipe Show') }}</div>

                <div class="card-body">
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
                                <div class="col-md-2">
                                    {{ $recipe->created_at  }}
                                </div>
                                <div class="col-md-2">
                                    <a class="nav-link" href="{{ route('recipe.edit', ['recipe' => $recipe->id]) }}"> {{ __('Edit Recipe') }}</a>
                                </div>

                                <div class="col-md-2">
                                    <form method="POST" action="{{ route('recipe.destroy', ['recipe' => $recipe->id]) }}">
                                        @csrf
                                        @method('DELETE')

                                        <div class="form-group row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Delete Recipe') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
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
