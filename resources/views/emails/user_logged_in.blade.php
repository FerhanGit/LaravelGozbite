@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Email body</div>

                <div class="card-body">
                    <div class="d-flex col-8">
                           Hello <div class="d-flex col-7">
                                    {{ $user->name }} You are logged in!
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
