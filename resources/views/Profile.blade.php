{{-- resources/views/profile/index.blade.php --}}

@extends('layouts.app')

@section('title', 'My Profile')
@section('page_title', 'My Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="row">

    {{-- ── Left: Profile Card ──────────────────────────────── --}}
    <div class="col-12 col-md-4 col-lg-4">
        <div class="card card-primary card-outline text-center">
            <div class="card-body pt-4">

                {{-- Profile Image --}}
                <div class="mb-3">
                    @if($user->image)
                        <img src="{{ asset('uploads/avatars/' . $user->image) }}"
                             class="img-circle elevation-2"
                             style="width:110px;height:110px;object-fit:cover;"
                             alt="Profile Photo">
                    @else
                        <img src="{{ asset('dist/img/avatar.png') }}"
                             class="img-circle elevation-2"
                             style="width:110px;height:110px;object-fit:cover;"
                             alt="Profile Photo">
                    @endif
                </div>

                {{-- Name --}}
                <h4 class="mb-1 font-weight-bold">{{ $user->name }}</h4>

                {{-- Email --}}
                <p class="text-muted mb-1">
                    <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                </p>

                {{-- Contact --}}
                @if($user->contact)
                    <p class="text-muted mb-1">
                        <i class="fas fa-phone mr-1"></i> {{ $user->contact }}
                    </p>
                @endif

                <hr>

                {{-- Joined Date --}}
                <p class="text-muted small mb-0">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Joined: {{ $user->created_at->format('d M Y') }}
                </p>

            </div>
        </div>
    </div>
    {{-- ── End Left ─────────────────────────────────────────── --}}


    {{-- ── Right: Tabs ─────────────────────────────────────── --}}
    <div class="col-12 col-md-8 col-lg-8">
        <div class="card card-primary card-outline">

            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="edit-tab" data-toggle="tab" href="#edit" role="tab">
                            <i class="fas fa-user-edit mr-1"></i> Edit Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab">
                            <i class="fas fa-lock mr-1"></i> Change Password
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="profileTabsContent">

                    {{-- ── Tab 1: Edit Profile ─────────────────────────── --}}
                    <div class="tab-pane fade show active" id="edit" role="tabpanel">

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div class="form-group">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Enter full name">
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <label>Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="Enter email">
                                @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Contact ✅ --}}
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="contact"
                                       class="form-control @error('contact') is-invalid @enderror"
                                       value="{{ old('contact', $user->contact) }}"
                                       placeholder="Enter contact number">
                                @error('contact')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Profile Image ✅ --}}
                            <div class="form-group">
                                <label>Profile Photo</label>

                                {{-- Current image small preview --}}
                                @if($user->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('uploads/avatars/' . $user->image) }}"
                                             class="img-circle elevation-1"
                                             style="width:50px;height:50px;object-fit:cover;"
                                             alt="Current Photo">
                                        <small class="text-muted ml-2">Current photo</small>
                                    </div>
                                @endif

                                <div class="custom-file">
                                    <input type="file" name="image" id="imageInput"
                                           class="custom-file-input @error('image') is-invalid @enderror"
                                           accept="image/*">
                                    <label class="custom-file-label" for="imageInput">
                                        Choose new photo
                                    </label>
                                </div>
                                @error('image')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror

                                {{-- New image preview --}}
                                <img id="imagePreview" src="#" alt="Preview"
                                     class="img-thumbnail mt-2 d-none"
                                     style="width:80px;height:80px;object-fit:cover;">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>

                        </form>

                    </div>
                    {{-- ── End Tab 1 ───────────────────────────────────── --}}


                    {{-- ── Tab 2: Change Password ──────────────────────── --}}
                    <div class="tab-pane fade" id="password" role="tabpanel">

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Current Password --}}
                            <div class="form-group">
                                <label>Current Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           placeholder="Enter current password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password"
                                              data-target="current_password"
                                              style="cursor:pointer">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('current_password')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="form-group">
                                <label>New Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="new_password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Enter new password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password"
                                              data-target="new_password"
                                              style="cursor:pointer">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="form-group">
                                <label>Confirm New Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="confirm_password"
                                           class="form-control"
                                           placeholder="Re-enter new password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password"
                                              data-target="confirm_password"
                                              style="cursor:pointer">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key mr-1"></i> Update Password
                            </button>

                        </form>

                    </div>
                    {{-- ── End Tab 2 ───────────────────────────────────── --}}

                </div>
            </div>

        </div>
    </div>
    {{-- ── End Right ────────────────────────────────────────── --}}

</div>

@endsection

@push('scripts')
<script>
    // ── Image preview on file select ─────────────────────────
    document.getElementById('imageInput').addEventListener('change', function () {
        const file    = this.files[0];
        const preview = document.getElementById('imagePreview');

        if (file) {
            // Update custom file label
            this.nextElementSibling.textContent = file.name;

            // Show preview
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    });

    // ── Toggle password show/hide ─────────────────────────────
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            const icon   = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // ── Auto open password tab if password errors exist ───────
    @if($errors->has('current_password') || $errors->has('password'))
        document.getElementById('password-tab').click();
    @endif
</script>
@endpush