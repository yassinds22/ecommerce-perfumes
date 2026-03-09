@extends('admin.layout.master')

@section('title', 'إعدادات الموقع — لوكس بارفيوم')

@section('page_title', 'الإعدادات')
@section('page_subtitle', 'إدارة معلومات الموقع والتواصل')

@section('content')
    @include('admin.sections.settings', ['isActive' => true])
@endsection
