@if (isset($persona))
    <div class="hidden" id="js-persona-edit">

        {{ Form::hidden('persona[class]', get_class($persona)) }}

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

        @if(get_class($persona) == config('event.vendor.class'))

            @include('partials.admin.vendor-user-category-edit')

        @endif

         @if(get_class($persona) == config('event.host.class'))

            @include('partials.admin.event-host-insurance')

        @endif

    </div>
@endif