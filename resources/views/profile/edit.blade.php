@extends('layouts.smartbiz')
@section('title', 'Meu perfil | SmartBiz')
@section('page_title', 'Meu perfil')
@section('page_subtitle', 'Conta, segurança e aparência')
@section('content')
<div class="sb-profile-page">
    <div class="sb-profile-hero">
        <div class="sb-avatar sb-profile-avatar-large">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
        <div><h1 class="sb-page-title mb-1">{{ $user->name }}</h1><p class="sb-page-subtitle m-0">{{ $user->email }}</p></div>
    </div>
    <div class="row g-3">
        <div class="col-xl-7"><div class="sb-card sb-profile-card">@include('profile.partials.update-profile-information-form')</div></div>
        <div class="col-xl-5"><div class="sb-card sb-profile-card">@include('profile.partials.appearance-form')</div></div>
        <div class="col-xl-7"><div class="sb-card sb-profile-card">@include('profile.partials.update-password-form')</div></div>
        <div class="col-xl-5"><div class="sb-card sb-profile-card sb-danger-card">@include('profile.partials.delete-user-form')</div></div>
    </div>
</div>
@endsection
