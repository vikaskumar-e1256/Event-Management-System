@extends('layouts.site')
@section('title', 'Register')
@section('content')
    <div class="container mt-4">

        <h3 class="mt-4">Register</h3>
        <form id="registerForm">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name">
                <div id="nameError" class="text-danger"></div>
            </div>

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

            {{-- <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="0">Attendee</option>
                    <option value="1">Organizer</option>
                </select>
                <div id="roleError" class="text-danger"></div>
            </div> --}}

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <div id="message" class="mt-3 text-center"></div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                $('.text-danger').empty();

                var formData = new FormData(this);

                $.ajax({
                    url: '{{ route('register') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data, textStatus, xhr) {
                        if (xhr.status === 200) {
                            if (data.message) {
                                $('#registerForm')[0].reset();
                                $('#message').text(data.message).removeClass('text-danger')
                                    .addClass('text-success');
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
