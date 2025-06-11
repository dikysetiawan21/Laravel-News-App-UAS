<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')
    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
        @if ($errors->updatePassword->has('current_password'))
            <small class="text-danger">{{ $errors->updatePassword->first('current_password') }}</small>
        @endif
    </div>
    <div class="mb-3">
        <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
        <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
        @if ($errors->updatePassword->has('password'))
            <small class="text-danger">{{ $errors->updatePassword->first('password') }}</small>
        @endif
    </div>
    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
        @if ($errors->updatePassword->has('password_confirmation'))
            <small class="text-danger">{{ $errors->updatePassword->first('password_confirmation') }}</small>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    @if (session('status') === 'password-updated')
        <span class="text-success ms-2">{{ __('Saved.') }}</span>
    @endif
</form>
