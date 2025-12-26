@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approve.css') }}">
@endsection

@section('content')

<form action="{{ route('admin.correction.approve', $correction->id) }}"
      method="POST">
    @csrf
    @method('PUT')
    <button>承認</button>
</form>


@endsection