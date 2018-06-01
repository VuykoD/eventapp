

<!-- Modal -->

		<div class="hidden" id="js-repo-hidden-org">

				{{ Form::open(['class' => 'form', 'role' => 'form', 'id' => 'js-form-create-org', 'data-action' => route('frontend.event.organization.new'), 'data-form-title' => 'Create Organization']) }}

			  	  	<div class="modal-body">

			  	  		@php($build_fields = $organization_fields)
                        @include('partials.event.block-build-fields')

					</div>

				    <div class="modal-footer">
						<div class="pull-left">
							<button type="button" class="btn btn-danger btn-xs" id="js-close-create-org">Close</button>
						</div>

						<div class="pull-right">
						    {{ Form::submit('Submit', ['class' => 'btn btn-success btn-xs', 'id' => 'js-submit-create-org']) }}
						</div>

						<div class="clearfix"></div>
				  	</div>

				{{ Form::close() }}

		</div>
		