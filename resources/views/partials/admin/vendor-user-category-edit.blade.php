{{-- Vendor users --}}

<fieldset class="js-event-criteria">

    @php($category_list = ['' => ''] + $category_list)
    @php($catg_values = $persona->categories_list)
    @if (empty($catg_values))
        @php($catg_values = ['' => ''])
    @endif                            

    @foreach ($catg_values as $catg => $slots)
        
        <div class="row js-category-block">
            <div class="col-lg-3">
                <div class="form-group">
                    {{ Form::label('category[id][]', 'Select Vendor Category', []) }}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">       
                    {{ Form::select('category[id][]', $category_list, $catg, ['class' => 'form-control use-select2', 'required' => '']) }}
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <button type="button" class="btn btn-block btn-outline-danger js-delete-catg-block" {{ $loop->first ? 'disabled' : '' }}>
                        <i class="icon-remove hidden-md-down"></i>
                        <span class="hidden-lg-up">Delete Category</span>
                    </button>
                </div>
            </div>
        </div>

    @endforeach

    <div class="row">
        <div class="col-lg-9 offset-lg-3">
            <div class="form-group">
                <button type="button" id="js-button-new-catg" class="btn btn-block btn-outline-primary">Add New Category</button>
            </div>
        </div>
    </div>

</fieldset>