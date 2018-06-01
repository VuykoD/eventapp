

<!-- Modal -->
	<div class="modal fade text-xs-left in" id="modalHostCommitment" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			@include('partials.spinner', ['spinner_location' =>  'confirm-host'])
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">×</span>
					</button>
					<label class="modal-title text-text-bold-600">Event Commitment Authorization</label>
				</div>
				{{-- <div class="alert alert-danger hidden js-alert-display">
				</div> --}}

				{{ Form::open(['route' => ['frontend.host.confirm', $uid], 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-form-host-invite']) }}

			  	  	<div class="modal-body">

			  	  		<div id="js-wrap-modal-conditions" class="hidden">
			  	  			<div class="form-group">
			  	  				<label>Your message</label>
				  	  			<textarea class="form-control" readonly></textarea>
			  	  			</div>
			  	  		</div>

			  	  		<div id="js-repo-hidden-conditions" class="hidden">
							<input type="checkbox" name="with_conditions" id="js-host-hidden-conditions"/>
							{{ Form::textarea('conditions_message', null, ['class' => 'form-control', 'placeholder' => 'Please state your request', 'id' => 'js-text-hidden-conditions']) }}
			  	  		</div>

						<p>Event Commitment Authorization Text</p>

                        <fieldset class="form-group" style="margin-top: 22px; margin-bottom: 0">
                            <label class="custom-control custom-checkbox">
                              <input type="checkbox" name="host_commitment" id="js-check-host-commitment" class="custom-control-input" required>
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description">I agree</span>
                            </label>
                        </fieldset>

					</div>

				    <div class="modal-footer">
				    	<div class="clearfix"></div>

						<div class="pull-left">
							<button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
						</div>

						<div class="pull-right">
						    {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs', 'id' => 'js-submit-host-commitment']) }}
						</div>

						<div class="clearfix"></div>
				  	</div>

				{{ Form::close() }}

			</div>
		</div>
	</div>

<script>


</script>