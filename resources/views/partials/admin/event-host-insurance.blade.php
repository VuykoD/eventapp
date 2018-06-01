    {{-- Event Host Insurance / Employer --}}
<fieldset class="js-host-insurance-block">

    @php($is_employer = isset($persona->health_insurer))

    <div class="row">
        <div class="col-lg-3">
            <div class="form-group"> 
                <label for="persona[host_has_insurance]">Employer</label>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="form-group">
                <input type="checkbox" name="persona[host_has_insurance]" id="js-host-employer" class="switchery" {{ $is_employer ? 'checked' : '' }}/>
            </div>
        </div>
    </div>

    @php($insurer_name = $persona->health_insurer ?? null)

    <div class="row js-host-insurance hidden">
        <div class="col-lg-3">
            <div class="form-group">
                {{ Form::label('persona[health_insurer]', 'Health Insurer Name', []) }}
            </div>
        </div>
        <div class="col-lg-9">
            <div class="form-group"> 
                {{ Form::text('persona[health_insurer]', $insurer_name, ['class' => 'form-control', 'placeholder' => 'Health insurer']) }}
            </div>
        </div>
    </div>

    @php($plans_list = isset($persona->health_plans) ? $persona->plans_array : null)

    <div class="row js-host-insurance hidden">
        <div class="col-lg-3">
            <div class="form-group"> 
                {{ Form::label('persona[health_plans][]', 'Plans Offered', []) }}
            </div>
        </div>
        <div class="col-lg-9">
            <div class="form-group">
                {{ Form::select('persona[health_plans][]', config('event.insurance.plans'), $plans_list, ['class' => 'form-control use-select2', 'multiple' => '']) }}
            </div> 
        </div>
    </div>

</fieldset>