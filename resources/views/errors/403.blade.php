@extends('errors.illustrated-layout')
@php
    $exception = null;
@endphp
@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception == !null ? $exception->getMessage() : 'Forbidden'))
