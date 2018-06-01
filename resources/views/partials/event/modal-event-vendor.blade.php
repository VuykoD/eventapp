

<!-- Modal -->
	<div class="modal fade text-xs-left in" id="modalCreateVendor" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			@include('partials.spinner', ['spinner_location' =>  'modal-vendor'])
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">×</span>
					</button>
					<label class="modal-title text-text-bold-600">Create Vendor</label>
				</div>
				<div class="alert alert-danger hidden js-alert-display-modal">
				</div>

				{{ Form::open(['class' => 'form', 'role' => 'form', 'id' => 'js-form-create-vendor', 'data-action' => route('frontend.event.vendor.new'), 'data-form-title' => 'Create Event Vendor']) }}

			  	  	<div class="modal-body">

			  	  		<div class="form-group">
                            {{ Form::label('persona[first_name]', 'First Name', []) }}
                            {{ Form::text('persona[first_name]', null, ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => '']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('persona[last_name]', 'Last Name', []) }}
                            {{ Form::text('persona[last_name]', null, ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => '']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('persona[title]', 'Title', []) }}
                            {{ Form::text('persona[title]', null, ['class' => 'form-control', 'placeholder' => 'Title']) }}
                        </div>

			  	  		<div class="form-group">
                            {{ Form::label('email', 'Email', []) }}
                            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => '']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('persona[phone]', 'Phone', []) }}
                            {{ Form::text('persona[phone]', null, ['class' => 'form-control', 'placeholder' => 'Phone']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('persona[alt_phone]', 'Alt Phone', []) }}
                            {{ Form::text('persona[alt_phone]', null, ['class' => 'form-control', 'placeholder' => 'Alt Phone']) }}
                        </div>

                        {{-- Organization --}}

                        @php ($organization_list = ['' => ''] + $organization_list)

                        <div class="form-group">
                            {{ Form::label('organization[id]', 'Select Organization', []) }}
                            {{ Form::select('organization[id]', $organization_list, null, ['class' => 'form-control use-select2-modal', 'id' => 'js-select-orgs', 'required' => '']) }}
                        </div>

                        @include('partials.admin.vendor-user-category-create')


					</div>

				    <div class="modal-footer">
						<div class="pull-left">
							<button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
						</div>

						<div class="pull-right">
						    {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs', 'id' => 'js-submit-create-vendor']) }}
						</div>

						<div class="clearfix"></div>
				  	</div>

				{{ Form::close() }}

			</div>
		</div>
	</div>

<script>


</script>