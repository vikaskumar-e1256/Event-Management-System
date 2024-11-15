@extends('layouts.site')

@section('title', 'Payment Result')

@section('content')
    <div class="container mt-4">
        @if ($status === 'success')
            <div class="alert alert-success">
                Your payment was successful! Thank you for your purchase.
            </div>
        @else
            <div class="alert alert-danger">
                Payment failed.
                @if ($errors->any())
                    <p>{{ $errors->first('error') }}</p>
                @else
                    Please try again.
                @endif
            </div>
        @endif
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Events</a>
    </div>
@endsection
