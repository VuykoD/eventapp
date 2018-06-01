
@include('partials.spinner', ['spinner_location' =>  'avatar'])

<div class="row">
    <div class="col-4">
        <div class="form-group">
            <img src="{{ $avatar_url }}" class="user-profile-image">
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group">
        <div class="col-md-2">
            <button type="button" id="js-button-upload" class="btn btn-primary">Upload New Logo</button>
        </div>
    </div>
</div>

