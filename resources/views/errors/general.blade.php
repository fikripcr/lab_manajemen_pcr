@extends('errors.error-layout')

@section('error-code', $status ?? '404')
@section('title', $title ?? 'Page Not Found')
@section('message', $message ?? 'Oops! The page you are looking for was not found.')
@section('description', $description ?? 'Page Not Found - The requested URL was not found on this server.')