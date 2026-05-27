@foreach(\App\Models\Variable::getFromCache() as $variable)
    @if($values)
        @php($home_variable = $values->where('variable_id', $variable->id)->first())
    @endif
    <div class="{{ $class }}">
        <div class="form-group">
            @if($withLabel)
                <label for="{{ $variable->id }}Variable" class="form-label">{{ $variable->title }}</label>
            @endif

            @if($variable->input_type === \App\Models\Variable::SELECT)
                <select name="variables[{{ $variable->id }}]" id="{{ $variable->id }}Variable" class="form-control">
                    <option value="">
                        {{ ($withLabel) ? $variable->place_holder: $variable->title }}
                    </option>
                    @foreach($variable->options as $option)
                        <option value="{{ $option->id }}" {{ isset($home_variable) && (data_get($home_variable, 'option_id') == $option->id) ? 'selected' : '' }}>{{ $option->name }}</option>
                    @endforeach
                </select>
            @endif
            @if($variable->input_type === \App\Models\Variable::CHECK_BOX)
                @foreach($variable->options as $option)
                <div class="form-group">
                    <input class="form-check-input" value="{{ $option->id }}" name="variables[{{ $variable->id }}]" id="{{ $option->id }}Option" type="radio" {{ isset($home_variable) && (data_get($home_variable, 'option_id') == $option->id) ? 'checked' : '' }}>
                    <label for="{{ $option->id }}Option" class="form-label">{{ $option->name }}</label>
                </div>
                @endforeach
            @endif
            @if($variable->input_type === \App\Models\Variable::TEXT)
                <input name="variables[{{ $variable->id }}]" value="{{ $home_variable->value ?? '' }}" id="{{ $variable->id }}Variable" placeholder="{{ ($withLabel) ? $variable->place_holder: $variable->title }}" type="text" class="form-control">
            @endif
        </div>
    </div>
@endforeach
