@extends('admin.layout.master')

@section('title', 'إدارة العملاء — لوكس بارفيوم')

@section('page_title', 'العملاء')
@section('page_subtitle', 'قاعدة بيانات العملاء المسجلين')

@section('content')
    @include('admin.sections.customers', ['isActive' => true])
@endsection
