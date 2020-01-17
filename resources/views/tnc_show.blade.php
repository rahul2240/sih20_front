@extends('layouts.dashboard')
@section('content')
<div class="card">
    <div class="card-body">
        <p class="h1 text-center">{{ $title }}</p>
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="{{ config('etherpad.url') }}/p/{{ $padID }}" allowfullscreen></iframe>
        </div>
    </div>
</div>

@endsection