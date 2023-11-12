@component($typeForm, get_defined_vars())
    <div data-controller="tinymce" class="mce-container" data-language="{{$language ?? 'en'}}">
        <input name="{{ $attributes["name"] }}" value="{!! $value !!}" required="{{ $attributes["required"] === true ? 'required' : '' }}" class="tinymce" id="tinymce-wrapper-{{$id}}" style="min-height: {{ $attributes['height'] }}" />
    </div>
@endcomponent
