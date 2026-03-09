@extends('admin.layout.master')

@section('title', 'إدارة الطلبات — لوكس بارفيوم')

@section('page_title', 'الطلبات')
@section('page_subtitle', 'تتبع وإدارة طلبات العملاء')

@section('content')
    @include('admin.sections.orders', ['isActive' => true])
@endsection
