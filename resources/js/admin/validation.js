// import Toastify from 'toastify-js';

initialization()
document.addEventListener('turbo:load', initialization);

function formSubmitValidation(elem, parent, event) {
    elem.addEventListener(event, (e) => {
        const requiredInputs = parent.querySelectorAll('input[required], textarea[required], select[required]');
        requiredInputs.forEach((element) => {
            validateElement(element);

            if (element.tagName.toLowerCase() === 'select') {
                element.addEventListener('change', () => validateSelect(element));
            }
        });
        validateUploader(e);
        // showErrorToast();
    });
}

/*function showErrorToast() {
    const customTemplate = `
    <div class="custom-toast rounded shadow-sm bg-white mb-3 fade show toast-new">
            <div class="toast-body d-flex">
                <p class="mb-0 custom-toast-text"></p>
                <button type="button" class="btn-close close-toast ms-auto"></button>
            </div>
        </div>
    `;

    // Создайте узел DOM из шаблона
    const customNode = document.createElement('div');
    customNode.innerHTML = customTemplate;
    const customToastText = customNode.querySelector('.custom-toast-text');

    // Задайте текст уведомления
    customToastText.append('Пожалуйста, проверьте введенные данные, возможны указания на других языках. А то nonononono');

    let customToast = Toastify({
        node: customNode,
        duration: 3000,
        gravity: 'top',
        position: 'center',
        backgroundColor: 'linear-gradient(to right, #ff4040, #ff6666)',
        className: 'custom-toast-class',
        style: {
            zIndex: 99999999,
        },
    }).showToast()

    // Функция закрытия уведомления
    function closeCustomToast() {
        const toastEl = document.querySelector('.custom-toast');
        toastEl.classList.add('closed');
        customToast.hideToast();
    }

    const closeButton = customNode.querySelector('.close-toast');
    closeButton.addEventListener('click', closeCustomToast);
}*/

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
    setValidationClass(editorContainer, editor.getContent({format: 'text'}).trim() !== '', true);

    function validateTinyMCEAndSetInputValue() {
        input.value = editor.getContent();
        setValidationClass(editorContainer, editor.getContent({format: 'text'}).trim() !== '', true);
    }

    editor.on('TinyMCEKeyUpEvent', validateTinyMCEAndSetInputValue);
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
        if (dropzone) {
            setValidationClass(dropzoneWrapper, dropzone.files.length !== 0, true);

            dropzone.on('addedfile', function () {
                setValidationClass(dropzoneWrapper, dropzone.files.length !== 0, true);
                const uploaderDataInput = dropzoneWrapper.querySelector('.uploader-data');
                if (uploaderDataInput) {
                    const filePreviews = dropzoneWrapper.querySelectorAll('.dz-file-preview');
                    uploaderDataInput.value = filePreviews.length === 0 ? '' : filePreviews.length;
                }
            });

            dropzone.on('removedfile', function () {
                setValidationClass(dropzoneWrapper, dropzone.files.length !== 0, true);
                const uploaderDataInput = dropzoneWrapper.querySelector('.uploader-data');
                if (uploaderDataInput) {
                    const filePreviews = dropzoneWrapper.querySelectorAll('.dz-file-preview');
                    uploaderDataInput.value = filePreviews.length === 0 ? '' : filePreviews.length;
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
                const filePreviews = dropzoneWrapper.querySelectorAll('.dz-file-preview');
                uploaderDataInput.value = filePreviews.length === 0 ? '' : filePreviews.length;
            }

            dropzone.on('addedfile', function () {
                const uploaderDataInput = dropzoneWrapper.querySelector('.uploader-data');
                if (uploaderDataInput) {
                    const filePreviews = dropzoneWrapper.querySelectorAll('.dz-file-preview');
                    uploaderDataInput.value = filePreviews.length === 0 ? '' : filePreviews.length;
                }
            });

            dropzone.on('removedfile', function () {
                const uploaderDataInput = dropzoneWrapper.querySelector('.uploader-data');
                if (uploaderDataInput) {
                    const filePreviews = dropzoneWrapper.querySelectorAll('.dz-file-preview');
                    uploaderDataInput.value = filePreviews.length === 0 ? '' : filePreviews.length;
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
            formSubmitValidation(modalFormBtn, form, 'click')
        }
    })
    formSubmitValidation(form, form, 'submit');
    setTimeout(() => {
        dropZoneInitCheck();
    }, 300)
}
