@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Verifikasi OTP</h4>
                </div>
                <div class="card-body">
                    <p class="text-center">
                        Kami telah mengirimkan kode OTP 6 digit ke:<br>
                        <strong>{{ session('otp_email') }}</strong>
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->has('otp'))
                        <div class="alert alert-danger">{{ $errors->first('otp') }}</div>
                    @endif

                    <form method="POST" action="{{ route('verify.otp') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Masukkan Kode OTP</label>
                            <input type="text" name="otp" class="form-control text-center fs-3" maxlength="6" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
                    </form>

                    <div class="text-center mt-3">
                        <form method="POST" action="{{ route('resend.otp') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none">
                                Kirim ulang OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection