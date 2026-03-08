@extends('admin.layout.master')

@section('title', 'لوحة التحكم — لوكس بارفيوم')

@section('content')
    @include('admin.sections.overview')
    @include('admin.sections.products')
    @include('admin.sections.orders')
    @include('admin.sections.customers')
    @include('admin.sections.reviews')
    @include('admin.sections.offers')

    @include('admin.sections.modals')
@endsection