			
	@foreach($build_fields as $field_name => $field)
  	  	@switch($field['type'])
  	  	    @case('hidden')
  	  	        {{ Form::hidden($field_name, null, $field['attributes']) }}
  	  	    @break

  	  	    @case('textarea')
  	  	    @break

  	  	    @default
  	  	        <div class="form-group">
                    {{ Form::label($field_name, $field['placeholder'], []) }}
                    {{ Form::input($field['type'], $field_name, null, 
                    	array_merge(['class' => 'form-control', 'placeholder' => $field['placeholder']], $field['attributes'])) }}
                </div>
  	  	    @break
  	  	@endswitch
  	@endforeach