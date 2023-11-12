@component($typeForm, get_defined_vars())
    <div data-controller="tinymce" data-language="{{$language ?? 'en'}}">
        <textarea name="{{ $attributes["name"] }}" required="{{ $attributes["required"] === true ? 'required' : '' }}" class="tinymce" id="tinymce-wrapper-{{$id}}" style="min-height: {{ $attributes['height'] }}">
            {!! $value !!}
        </textarea>
    </div>
@endcomponent
