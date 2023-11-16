initialization()
document.addEventListener('turbo:load', initialization);

function formSubmitValidation(elem, parent, event) {
    elem.addEventListener(event, (e) => {
        const requiredInputs = parent.querySelectorAll('input[required], textarea[required], select[required]');
        requiredInputs.forEach((element) => {
            validateElement(element);
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
    } else if (element.tagName.toLowerCase() === 'select') {
        validateSelect(element)
        element.addEventListener('change', () => validateSelect(element));
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
    setValidationClass(select.nextElementSibling, tomSelect.isValid === true);

    tomSelect.on('change', function () {
        setValidationClass(select.nextElementSibling, tomSelect.isValid === true);
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
        cropperContainer.classList.remove('border-dashed');
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

    observer.observe(img, {attributes: true});
}

function validateTinyMce(input) {
    const editor = tinymce.get(input.id);
    const editorContainer = input.closest('[data-controller="tinymce"]');
    const toxContainer = editorContainer.querySelector('.tox-tinymce');
    setValidationClass(toxContainer, editor.getContent({format: 'text'}).trim() !== '', true);

    function validateTinyMCEAndSetInputValue() {
        input.value = editor.getContent();
        setValidationClass(toxContainer, editor.getContent({format: 'text'}).trim() !== '', true);
    }

    editor.on('keyup', validateTinyMCEAndSetInputValue);
    editor.on('change', validateTinyMCEAndSetInputValue);
}

function TinyMCEKeyUpEvent() {
    const editors = document.querySelectorAll('.tinymce[required="required"]');
    editors.forEach((input) => validateElement(input));
}

function validateUploader(e) {
    const dropzoneWrappers = document.querySelectorAll('.dropzone-wrapper[data-required="required"]');
    dropzoneWrappers.forEach(function (dropzoneWrapper) {
        const dropzone = Dropzone.forElement(dropzoneWrapper);
        const uploadField = dropzoneWrapper.querySelector('.uploader-field');
        if (dropzone) {
            setValidationClass(uploadField, dropzone.files.length !== 0, true);
            const uploaderDataInput = dropzoneWrapper.querySelector('.uploader-data');
            uploaderDataInput.value = dropzone.files.length === 0 ? '' : dropzone.files.length;

            dropzone.on('addedfile', function () {
                setValidationClass(uploadField, dropzone.files.length !== 0, true);
                if (uploaderDataInput) {
                    uploaderDataInput.value = dropzone.files.length === 0 ? '' : dropzone.files.length;
                }
            });

            dropzone.on('removedfile', function () {
                setValidationClass(uploadField, dropzone.files.length !== 0, true);
                if (uploaderDataInput) {
                    uploaderDataInput.value = dropzone.files.length === 0 ? '' : dropzone.files.length;
                }
            });
        }
    });
}

function dropZoneInitCheck() {
    const dropzoneWrappers = document.querySelectorAll('.dropzone-wrapper[data-required="required"]');
    dropzoneWrappers.forEach(function (dropzoneWrapper) {
        const dropzone = Dropzone.forElement(dropzoneWrapper);
        if (dropzone) {
            const uploaderDataInput = dropzoneWrapper.querySelector('.uploader-data');
            if (uploaderDataInput) {
                uploaderDataInput.value = dropzone.files.length === 0 ? '' : dropzone.files.length;
            }

            dropzone.on('addedfile', function () {
                if (uploaderDataInput) {
                    uploaderDataInput.value = dropzone.files.length === 0 ? '' : dropzone.files.length;
                }
            });

            dropzone.on('removedfile', function () {
                if (uploaderDataInput) {
                    uploaderDataInput.value = dropzone.files.length === 0 ? '' : dropzone.files.length;
                }
            });
        }
    });
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

function initialization() {
    let form = document.getElementById('post-form');
    let modalForm = document.querySelectorAll('.modal-content');

    TinyMCEKeyUpEvent();
    modalForm.forEach((form) => {
        let modalFormBtn = form.querySelector('button[type="submit"]');
        if (modalFormBtn) {
            // formSubmitValidation(modalFormBtn, form, 'click')
            modalFormBtn.addEventListener('click', function (event) {
                event.preventDefault();

                const errorBlock = document.querySelector('.error-block');

                if (errorBlock) {
                    errorBlock.remove();
                }

                sendForm(form);
            });
        }
    })
    formSubmitValidation(form, form, 'submit');
    setTimeout(() => {
        dropZoneInitCheck();
    }, 300)


    function sendForm(form) {
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        xhr.open('POST', '/ajax/send-modal');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        // Обработка успешного ответа
                    } else {
                        const errors = response.errors;
                        const errorBlock = document.createElement('div');
                        errorBlock.classList.add('error-block');

                        errors.forEach(function (error) {
                            const errorElement = document.createElement('p');
                            errorElement.textContent = error;
                            errorBlock.appendChild(errorElement);
                        });

                        const modalHeader = document.querySelector('.modal-header');
                        modalHeader.parentNode.insertBefore(errorBlock, modalHeader.nextSibling);
                    }
                } else {
                    // Обработка ошибки
                }
            }
        };
        xhr.send(formData);
    }

}
