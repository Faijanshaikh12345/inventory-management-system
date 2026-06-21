{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Register</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">

    <div class="register-box">

        {{-- Logo --}}
        <div class="register-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" height="45"
                    style="margin-right: 8px;">
                <b>Admin</b> Panel
            </a>
        </div>

        {{-- Register Card --}}
        <div class="card">
            <div class="card-body register-card-body">

                <p class="login-box-msg">Register a new membership</p>

                {{-- Error Alert --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                {{-- ✅ enctype for file upload --}}
                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Full Name --}}
                    <div class="input-group mb-3">
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Full Name"
                               value="{{ old('name') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Email"
                               value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Contact ✅ new --}}
                    <div class="input-group mb-3">
                        <input type="text" name="contact"
                               class="form-control @error('contact') is-invalid @enderror"
                               placeholder="Contact Number (optional)"
                               value="{{ old('contact') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                        @error('contact')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Profile Image ✅ new --}}
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" name="image" id="imageInput"
                                   class="custom-file-input @error('image') is-invalid @enderror"
                                   accept="image/*">
                            <label class="custom-file-label" for="imageInput">
                                Profile Photo (optional)
                            </label>
                        </div>
                        @error('image')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Image Preview ✅ --}}
                    <div class="text-center mb-3 d-none" id="previewWrapper">
                        <img id="imagePreview" src="#" alt="Preview"
                             class="img-circle elevation-1"
                             style="width: 70px; height: 70px; object-fit: cover;">
                    </div>

                    {{-- Terms & Register Button --}}
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="agree" value="agree" required>
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                Register
                            </button>
                        </div>
                    </div>

                </form>

                {{-- Login Link --}}
                <p class="mt-3 mb-0">
                    <a href="{{ route('login') }}" class="text-center">
                        I already have an account
                    </a>
                </p>

            </div>
        </div>

    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <script>
        // Show file name in custom file input label
        document.getElementById('imageInput').addEventListener('change', function () {
            const file    = this.files[0];
            const preview = document.getElementById('imagePreview');
            const wrapper = document.getElementById('previewWrapper');

            if (file) {
                // Update label
                this.nextElementSibling.textContent = file.name;

                // Show preview
                preview.src = URL.createObjectURL(file);
                wrapper.classList.remove('d-none');
            }
        });
    </script>

</body>
</html>