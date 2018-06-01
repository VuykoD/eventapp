

<!-- Modal -->
	<div class="modal fade text-xs-left in" id="modalCreateVenue" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			@include('partials.spinner')
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">×</span>
					</button>
					<label class="modal-title text-text-bold-600">Create Venue</label>
				</div>
				<div class="alert alert-danger hidden js-alert-display">
				</div>

				{{ Form::open([
					'class' => 'form', 
					'role' => 'form', 
					'id' => 'js-form-create-venue', 
					'data-action' => route('frontend.event.venue.new'),
					'data-coord-id' => $venue_fields['lat_lng']['attributes']['id'],
				]) }}

			  	  	<div class="modal-body">

					@php($build_fields = $venue_fields)
					@include('partials.event.block-build-fields')

					</div>

				    <div class="modal-footer">
						<div class="pull-left">
							<button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
						</div>

						<div class="pull-right">
						    {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs', 'id' => 'js-submit-create-venue']) }}
						</div>

						<div class="clearfix"></div>
				  	</div>

				{{ Form::close() }}

			</div>
		</div>
	</div>

<script>


</script>