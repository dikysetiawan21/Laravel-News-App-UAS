<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')
    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Name') }}</label>
        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @if ($errors->has('name'))
            <small class="text-danger">{{ $errors->first('name') }}</small>
        @endif
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @if ($errors->has('email'))
            <small class="text-danger">{{ $errors->first('email') }}</small>
        @endif
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning mt-2 p-2">
                {{ __('Your email address is unverified.') }}
                <button form="send-verification" class="btn btn-link p-0 align-baseline">{{ __('Click here to re-send the verification email.') }}</button>
            </div>
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mt-2">
                    {{ __('A new verification link has been sent to your email address.') }}
                </div>
            @endif
        @endif
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    @if (session('status') === 'profile-updated')
        <span class="text-success ms-2">{{ __('Saved.') }}</span>
    @endif
</form>
