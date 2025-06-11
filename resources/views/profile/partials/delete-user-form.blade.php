<!-- Button trigger modal -->
<!-- Button trigger modal (Bootstrap 4 syntax)-->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteAccountModal">
  {{ __('Delete Account') }}
</button>
<!-- Modal (Bootstrap 4 syntax)-->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAccountModalLabel">{{ __('Are you sure you want to delete your account?') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                @if ($errors->userDeletion->has('password'))
                    <small class="text-danger">{{ $errors->userDeletion->first('password') }}</small>
                @endif
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
