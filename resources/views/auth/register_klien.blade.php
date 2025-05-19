@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <h3 class="text-center mb-4">Registrasi Klien</h3>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('register.klien') }}">
                        {!! csrf_field() !!}

                        <!-- Nama Klien -->
                        <div class="form-group">
                            <label class="control-label" for="nama_klien">Nama Lengkap</label>
                            <div>
                                <input type="text" class="form-control{{ $errors->has('nama_klien') ? ' is-invalid' : '' }}"
                                       name="nama_klien" id="nama_klien" value="{{ old('nama_klien') }}" required>

                                @if ($errors->has('nama_klien'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nama_klien') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="control-label" for="email">Email</label>
                            <div>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" id="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label class="control-label" for="password">Password</label>
                            <div>
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" id="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group">
                            <label class="control-label" for="password_confirmation">Konfirmasi Password</label>
                            <div>
                                <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                       name="password_confirmation" id="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    Daftar Sebagai Klien
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Link Login -->
            <div class="text-center mt-3">
                Sudah punya akun? <a href="{{ route('backpack.auth.login') }}">Login disini</a>
            </div>
        </div>
    </div>
@endsection
