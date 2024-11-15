@extends('layouts.site')

@section('title', 'Login')

@section('content')
    <div class="container mt-4">

        <h3 class="mt-4">Login</h3>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
                <div id="emailError" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <div id="passwordError" class="text-danger"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div id="message" class="mt-3 text-center"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $('.text-danger').empty();

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('login') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data, textStatus, xhr) {
                        if (xhr.status === 200) {
                            if (data.success && data.redirect) {
                                window.location.href = data.redirect;
                            } else if (data.message) {
                                $('#message').text(data.message).removeClass('text-success')
                                    .addClass('text-danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, errorMessages) {
                                $('#' + field + 'Error').html(errorMessages.join(
                                    '<br>'));
                            });
                        } else {
                            console.error('Unexpected error:', error);
                        }
                    }
                });
            });
        });
    </script>
@endpush
