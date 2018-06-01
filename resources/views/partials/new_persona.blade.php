@if (isset($persona_list))

        <div class="hidden" id="js-persona-select">
        
            <div class="form-group">
                {{ Form::label('persona[select]', 'Select Type', ['class' => 'col-lg-3 control-label']) }}

                <div class="col-lg-9">
                    @php ($list = ['' => ''])
                    @foreach ($persona_list as $key => $persona)
                        @php ($list[$key] = $persona['name'])
                    @endforeach
                    {{ Form::select('persona[select]', $list, null, ['class' => 'form-control', 'required' => '']) }}
                </div>
            </div>

            <div class="clearfix hidden-md-down row-spacer"></div>

        </div>

        @foreach ($persona_list as $meta_id => $meta)

            <div class="hidden" id="js-persona-new-{{ $meta_id }}" data-persona="{{ $meta_id }}" data-role-id="{{ $meta['role_id'] }}">

                @php ($persona = new $meta['class'])

                {{ Form::hidden('persona[class]', $meta['class'], ['id' => 'persona-' . $meta_id]) }}

                @foreach($persona->getFields() as $key => $field)

                    @php ($field_name = 'persona[' . $key . ']')

                    <div class="form-group">
                        {{ Form::label($field_name, $field['placeholder'], ['class' => 'col-lg-3 control-label']) }}

                        <div class="col-lg-9">
                            @if ($field['type'] === 'textarea')
                                {{ Form::textarea($field_name, $persona->{$key}, ['class' => 'form-control', 'placeholder' => $field['placeholder']]) }}
                            @endif
                            @if ($field['type'] !== 'textarea')
                                {{ Form::input($field['type'], $field_name, $persona->{$key}, ['class' => 'form-control', 'placeholder' => $field['placeholder']]) }}
                            @endif  
                        </div>
                    </div>

                    <div class="clearfix hidden-md-down row-spacer"></div>

                @endforeach


                @if($meta_id == config('event.vendor.meta_id'))

                    @include('partials.admin.vendor-user-category-create')

                @endif

                @if($meta_id == config('event.host.meta_id'))

                    @include('partials.admin.event-host-insurance')

                @endif

            </div>

        @endforeach
@endif