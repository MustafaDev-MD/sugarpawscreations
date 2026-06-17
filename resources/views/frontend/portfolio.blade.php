@extends('frontend.layouts.app')

@section('title', $category->name)

@section('content')

<div class="container py-5">

    <h1 class="mb-4">
        {{ $category->name }}
    </h1>

    <div class="row">

        @foreach($category->portfolios as $portfolio)

            <div class="col-md-4 mb-4">

                <img src="{{ asset('storage/'.$portfolio->image) }}"
                     class="img-fluid">

            </div>

        @endforeach

    </div>

</div>

@endsection