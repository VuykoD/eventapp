

{{ Form::open(['data-action' => $action_url, 'class' => 'form', 'role' => 'form', 'method' => 'post', 'id' => 'js-upload-avatar']) }}
    <input type="hidden" name="owner" value="{{ $owner_type }}">
    <input type="hidden" name="owner_id" value="{{ $owner_id }}">
    <input type="file" id="js-input-avatar" {{-- name="avatar" --}} accept="image/*" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">

{{ Form::close() }}

<!-- Modal -->
    <div class="modal fade text-xs-left in" id="modalCropAvatar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                @include('partials.spinner', ['spinner_location' =>  'cropper'])
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                    <label class="modal-title text-text-bold-600">Crop Your Logo</label>
                </div>


                <div class="modal-body" style="min-height:500px;" >
                    <div style="width: 100%; height: 100%; min-height:500px;">
                        <img src="{{ $avatar_url }}" style="width:100%; max-width:100%; min-height:400px;" id="js-img-cropper">
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="pull-left">
                        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
                    </div>


                    <div class="pull-right">
                        <button type="button" id="js-button-apply" class="btn btn-success btn-xs">Apply</button>
                    </div>

                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>


