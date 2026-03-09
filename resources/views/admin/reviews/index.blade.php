@extends('admin.layout.master')

@section('title', 'إدارة التقييمات — لوكس بارفيوم')

@section('page_title', 'التقييمات')
@section('page_subtitle', 'إدارة تقييمات وآراء العملاء')

@section('content')
    @include('admin.sections.reviews', ['isActive' => true])
@endsection
