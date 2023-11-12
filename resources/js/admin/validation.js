const form = document.getElementById('post-form');

setTimeout(() => {
    keyUp();
    document.addEventListener('turbo:load', keyUp);
    formSubmitValidation();
    document.addEventListener('turbo:load', formSubmitValidation);
}, 300);

function formSubmitValidation() {
    form.addEventListener('submit', (e) => {
        const requiredInputs = document.querySelectorAll('input[required], textarea[required], select[required]');

        requiredInputs.forEach((element) => {
            validateElement(element);

            if (element.tagName.toLowerCase() === 'select') {
                element.addEventListener('change', () => validateInput(element));
            }
        });

        validateUploader(e);
    });
}

function validateElement(element) {
    if (element.classList.contains('cropper-path')) {
        validateCropper(element);
        element.addEventListener('change', () => validateCropper(element));
    } else if (element.classList.contains('tinymce')) {
        validateTinyMce(element);
    } else {
        validateInput(element);
        element.addEventListener('input', () => validateInput(element));
    }
}

function setValidationClass(element, isValid, rounded = false) {
    const validationClassList = element.classList;
    const successClass = 'validation-success';
    const errorClass = 'validation-error';

    if (rounded) {
        validationClassList.add('rounded');
    } else {
        validationClassList.remove('rounded');
    }

    if (isValid) {
        validationClassList.remove(errorClass);
        validationClassList.add(successClass);
    } else {
        validationClassList.remove(successClass);
        validationClassList.add(errorClass);
    }
}

function validateInput(input) {
    setValidationClass(input, input.value.trim() !== '');
}

function validateSelect(select) {
    const tomSelect = select.tomselect;
    setValidationClass(select.nextElementSibling, tomSelect.isValid === false);

    tomSelect.on('change', function () {
        setValidationClass(select.nextElementSibling, tomSelect.isValid === false);
    });
}

function validateCropper(input) {
    const cropperParent = input.closest('.cropper-parent');
    const cropperContainer = cropperParent.querySelector('.cropper-actions');
    const img = cropperParent.querySelector('img');
    const imgSrc = img.getAttribute('src');

    if (imgSrc === '' || imgSrc === '#') {
        cropperContainer.classList.remove('border-dashed');
        setValidationClass(cropperContainer, false);
    } else {
        setValidationClass(cropperContainer, true);
    }

    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'src') {
                const imgSrc = img.getAttribute('src');
                if (imgSrc === '' || imgSrc === '#') {
                    setValidationClass(cropperContainer, false);
                } else {
                    setValidationClass(cropperContainer, true);
                }
            }
        }
    });

    observer.observe(img, { attributes: true });
}

function validateTinyMce(input) {
    const editor = tinymce.get(input.id);
    const editorContainer = input.closest('[data-controller="tinymce"]');
    setValidationClass(editorContainer, editor.getContent({ format: 'text' }).trim() !== '', true);

    function validateTinyMCEAndSetInputValue() {
        input.value = editor.getContent();
        setValidationClass(editorContainer, editor.getContent({ format: 'text' }).trim() !== '', true);
    }

    editor.on('keyup', validateTinyMCEAndSetInputValue);
    editor.on('change', validateTinyMCEAndSetInputValue);
}

function keyUp() {
    const editors = document.querySelectorAll('.tinymce[required="required"]');
    editors.forEach((input) => validateElement(input));
}

function validateUploader(e) {
    const dropzoneWrapper = document.querySelector('.dropzone-wrapper[data-required="required"]');
    if (dropzoneWrapper) {
        const dropzone = Dropzone.forElement(dropzoneWrapper);
        if (dropzone) {
            setValidationClass(dropzoneWrapper, dropzone.files.length !== 0, true);

            dropzone.on('addedfile', function () {
                setValidationClass(dropzoneWrapper, dropzone.files.length !== 0, true);
            });

            dropzone.on('removedfile', function () {
                setValidationClass(dropzoneWrapper, dropzone.files.length !== 0, true);
            });
        }
    }
}

function formStop(e) {
    e.preventDefault();

    setTimeout(() => {
        const button = e.submitter;
        button.classList.remove('cursor-wait');
        button.classList.remove('btn-loading');
        button.removeAttribute('disabled');
        button.querySelector('.spinner-loading ').remove();
    }, 1000);
}
