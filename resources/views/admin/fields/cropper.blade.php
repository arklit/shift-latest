@component($typeForm, get_defined_vars())
    <div data-controller="cropperrocont"
         data-cropperrocont-value="{{ $attributes['value'] }}"
         data-cropperrocont-storage="{{ $storage ?? config('platform.attachment.disk', 'public') }}"
         data-cropperrocont-width="{{ $width }}"
         data-cropperrocont-height="{{ $height }}"
         data-cropperrocont-min-width="{{ $minWidth }}"
         data-cropperrocont-min-height="{{ $minHeight }}"
         data-cropperrocont-max-width="{{ $maxWidth }}"
         data-cropperrocont-max-height="{{ $maxHeight }}"
         data-cropperrocont-target="{{ $target }}"
         data-cropperrocont-url="{{ $url }}"
         data-cropperrocont-accepted-files="{{ $acceptedFiles }}"
         data-cropperrocont-max-file-size="{{ $maxFileSize }}"
         data-cropperrocont-groups="{{ $attributes['groups'] }}"
         data-cropperrocont-path="{{ $attributes['path'] ?? '' }}"
    >
        <div class="border-dashed text-end p-3 cropper-actions">

            <div class="fields-cropper-container">
                <img src="#" class="cropper-preview img-fluid img-full mb-2 border" alt="">
            </div>

            <span class="mt-1 float-start">{{ __('Upload image from your computer:') }}</span>

            <div class="btn-group">
                <label class="btn btn-default m-0">
                    <x-orchid-icon path="cloud-upload" class="me-2"/>

                    {{ __('Browse') }}
                    <input type="file"
                           accept="image/*"
                           data-cropperrocont-target="upload"
                           data-action="change->cropperrocont#upload click->cropperrocont#openModal"
                           class="d-none">
                </label>

                <button type="button" class="btn btn-outline-danger cropper-remove"
                        data-action="cropperrocont#clear">{{ __('Remove') }}</button>
            </div>

            <input type="file"
                   accept="image/*"
                   class="d-none">
        </div>
        <img src="" alt="" id="test-image">

        <input class="cropper-path d-none"
               type="text"
               data-cropperrocont-target="source"
            {{ $attributes }}
        >

        <div class="modal" role="dialog" {{$staticBackdrop ? "data-bs-backdrop=static" : ''}}>
            <div class="modal-dialog modal-fullscreen-md-down modal-lg">
                <div class="modal-content-wrapper">
                    <div class="modal-content">
                        <div class="position-relative">
                            <img class="upload-panel">
                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-link"
                                    data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>

                            <button type="button"
                                    class="btn btn-default"
                                    data-action="cropperrocont#crop">
                                {{ __('Crop') }}
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endcomponent
