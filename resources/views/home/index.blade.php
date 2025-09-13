@extends('home.home_master')
@section('content')
    @include('home.home_layouts.hero_section')
    <!-- end hero -->

    @include('home.home_layouts.feature')
    <!-- end content -->

    @include('home.home_layouts.clarifies')
    <!-- end content -->

    @include('home.home_layouts.financial')
    <!-- end content -->

    @include('home.home_layouts.usability')
    <!-- end video -->

    @include('home.home_layouts.testimonial')
    <!-- end testimonial -->

    @include('home.home_layouts.faq')
    <!-- end faq -->

    @include('home.home_layouts.cta')
    <!-- end cta -->
@endsection
