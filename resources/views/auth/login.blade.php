@extends('layouts.app')

@section('content')
<div class="container">
    <section id="LogIn" class="col-md-12 col-lg-6 center">
        <h1 style="text-align: center">¡Bienvenido Héroe!</h1>
        <br>
        <form method="POST" action="{{ route('login') }}">
            <div class="form-container">
                @csrf
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" id="inputEmail" placeholder="" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPass">Contraseña</label>
                    <input type="password"  id="inputPass" placeholder="" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-check" id="checkLogIn">
                    <input type="checkbox" class="form-check-input" id="sesionCheck" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="sesionCheck"><b> Recordarme</b></label>
                </div>
                <br>
                <button type="submit" class="btn btn-principal btn-block" id="btnIngreso">Ingresar</button>
        
        <br>
        <p class="text-center"> ¿No tenés cuenta? <a class="loginform-register-link" href="{{url('register')}}"><b>Registrarse</b></a></p>
        <p class="text-center"> ¿Olvidaste tu contraseña? ¡No te preocupes! <a href="{{ route('password.request') }}"><b>Te ayudamos a recuperarla</b></a></p>
            </div>
        </form>

        <img src="assets/separador.svg" alt="separador" id="separadorRegistroHeroe">
        <br>

        <a class="btn btn-gmail btn-block" id="btnLogInHeroeGmail"  href="{{ route('social.auth', 'google') }}">Ingresar con Gmail</a>
        <a class="btn btn-facebook btn-block" id="btnLogInHeroeFace" href="{{ route('social.auth', 'facebook') }}">Ingresar con Facebook</a>

    </section>
</div>

 @endsection
<!-- Fin Footer -->






