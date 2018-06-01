@extends('auth')

@section('title', 'Vendor Registration')

@section('content')

  @section('sub_title', 'Vendor Registration')
    @include('partials.spinner')

    <form class="form-horizontal vendor-registration" method="POST" action="{{ route('frontend.auth.vendor.signup.register') }}" accept-charset="UTF-8">
      <div id="screen-1" class="form-screen">
        <h5>Is your organization already part of the Events Portal?</h5>
        <fieldset class="form-group has-feedback is-org">
          <input type="radio" name="is_org" value="yes" id="org-yes">
          <label for="org-yes">Yes</label>
          <input type="radio" name="is_org" value="no" id="org-no">
          <label for="org-no">No</label>
        </fieldset>
        <div class="code-input" style="display: none">
          <p>Please enter the 5 digit code provided by your Organization Administrator</p>
          <fieldset class="form-group has-feedback has-icon-left">
            <input type="text" name="code" id="code" class="form-control form-control-lg input-lg" placeholder="5 digit code" autocomplete="off">
            <div class="form-control-position">
                <i class="icon-paper-clip"></i>
            </div>
          </fieldset>
        </div>
        <button type="button" data-step="2" class="btn btn-lg btn-primary btn-block disabled forward" data-url="{{ route('frontend.auth.vendor.signup.checkcode') }}">Next <i class="icon-fast-forward2"></i></button>
        <div class="alert alert-danger" id="org-error" style="display: none"></div>
      </div>
      <div id="screen-2" class="form-screen" style="display: none">
        <h5>Create an Organization</h5>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <fieldset class="form-group has-feedback">
              <label for="org-name">Organization Name</label>
              <input type="text" id="org-name" name="org_name" class="form-control form-control-lg input-lg" data-mandatory="Please enter your Organization Name">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="org-addr1">Address 1</label>
              <input type="text" id="org-addr1" name="org_addr1" class="form-control form-control-lg input-lg" data-mandatory="Please enter Address">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="org-addr2">Address 2</label>
              <input type="text" id="org-addr2" name="org_addr2" class="form-control form-control-lg input-lg">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="org-city">City</label>
              <input type="text" id="org-city" name="org_city" class="form-control form-control-lg input-lg" data-mandatory="Please enter City">
            </fieldset>
          </div>
          <div class="col-xs-12 col-sm-6">
            <fieldset class="form-group has-feedback">
              <label for="org-state">State</label>
              <input type="text" id="org-state" name="org_state" class="form-control form-control-lg input-lg" data-mandatory="Please enter State">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="org-zip">Zip</label>
              <input type="text" id="org-zip" name="org_zip" class="form-control form-control-lg input-lg" data-mandatory="Please enter Zip code">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="org-phone">Phone</label>
              <input type="text" id="org-phone" name="org_phone" class="form-control form-control-lg input-lg" data-mandatory="Please enter Phone">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="org-website">Website</label>
              <input type="text" id="org-website" name="org_website" class="form-control form-control-lg input-lg" data-mandatory="Please enter Website">
            </fieldset>
          </div>
        </div>
        <div class="row">
          <button type="button" data-step="1" class="btn btn-lg btn-primary backward pull-left" style="margin-left: 15px;"><i class="icon-rewind"></i> Back</button>
          <button type="button" data-step="3" class="btn btn-lg btn-primary forward pull-right" style="margin-right: 15px">Next <i class="icon-fast-forward2"></i></button>
          <div class="cleafix"></div>
        </div>
      </div>
      <div id="screen-3" class="form-screen" style="display: none">
        <h5>Register</h5>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <div class="row">
              <div class="col-xs-6">
                <fieldset class="form-group has-feedback">
                  <label for="user-name">Name</label>
                  <input type="text" id="user-name" name="user_name" class="form-control form-control-lg input-lg" data-mandatory="Please enter Name">
                </fieldset>
              </div>
              <div class="col-xs-6">
                <fieldset class="form-group has-feedback">
                  <label for="user-lastname">Last Name</label>
                  <input type="text" id="user-lastname" name="user_lastname" class="form-control form-control-lg input-lg" data-mandatory="Please enter Last Name">
                </fieldset>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6">
                <fieldset class="form-group has-feedback">
                  <label for="user-phone">Phone</label>
                  <input type="text" id="user-phone" name="user_phone" class="form-control form-control-lg input-lg" data-mandatory="Please enter Phone">
                </fieldset>
              </div>
              <div class="col-xs-6">
                <fieldset class="form-group has-feedback">
                  <label for="user-altphone">Alt Phone</label>
                  <input type="text" id="user-altphone" name="user_altphone" class="form-control form-control-lg input-lg">
                </fieldset>
              </div>
            </div>
            <fieldset class="form-group has-feedback">
              <label for="user-email">Email</label>
              <input type="text" id="user-email" name="user_email" class="form-control form-control-lg input-lg" data-mandatory="Please enter Email">
            </fieldset>
            <div class="row">
              <div class="col-xs-6">
                <fieldset class="form-group has-feedback">
                  <label for="user-password">Password</label>
                  <input type="password" id="user-password" name="password" class="form-control form-control-lg input-lg" data-mandatory="Please enter Password">
                </fieldset>
              </div>
              <div class="col-xs-6">
                <fieldset class="form-group has-feedback">
                  <label for="user-passconfirm">Confirm Password</label>
                  <input type="password" id="user-passconfirm" name="password_confirmation" class="form-control form-control-lg input-lg" data-mandatory="Please enter Password">
                </fieldset>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6">
            <fieldset class="form-group has-feedback">
              <label for="user-title">Title</label>
              <input type="text" id="user-title" name="user_title" class="form-control form-control-lg input-lg" data-mandatory="Please enter Title">
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="user-aboutus">About Us</label>
              <textarea id="user-aboutus" name="user_aboutus" class="form-control form-control-lg" data-mandatory="Please enter Description"></textarea>
            </fieldset>
            <fieldset class="form-group has-feedback">
              <label for="user-cats">Select Category</label>
              <div id="user-cats">
                <div class="row">
                  <div class="col-xs-12 col-sm-9">
                    <select id="cats-list">
                      @foreach ($cats as $cat)
                      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-xs-12 col-sm-3">
                    <button type="button" class="btn btn-lg btn-primary" id="add-category">Add</button>
                  </div>
                </div>
              </div>
              <div id="selected-cats">
                <div id="empty-cats-error" style="display: none">Please select at least one Category</div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="row" style="text-align: center">
          <button type="button" data-step="2" class="btn btn-lg btn-primary backward pull-left" style="margin-left: 15px;"><i class="icon-rewind"></i> Back</button>
          <div class="alert alert-danger" id="error" style="display: none"></div>
          <button type="submit" class="btn btnc-login btn-lg pull-right" style="margin-right: 15px"><i class="icon-circle-plus"></i> Register</button>
          <div class="cleafix"></div>
        </div>
      </div>
      <div id="screen-4" class="form-screen" style="display: none;">
        <h5 class="text-center">Successful Registration</h5>
        <div class="row">
          <div class="col-xs-12">
            <p></p>
          </div>
        </div>
        <div class="row" style="text-align: center">
          <a href="{{ url('/') }}" class="btn btnc-login btn-lg">To the main page</a>
          <div class="cleafix"></div>
        </div>
      </div>
    </form>

@endsection

@section('vendor_login')
  <a href="{{ route('frontend.auth.login') }}" class="card-link vendor-back-login-link"><i class="icon-esc remove-cat"></i> Login</a>
@endsection

@section('javascript')
<script async defer 
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQaVZgBql0Qw7hBJJMrlybFenyb5RfcE8">
</script>
@endsection