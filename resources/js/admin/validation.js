const form = document.getElementById('post-form');

setTimeout(() => {
    keyUp()
})

form.addEventListener('submit', (e) => {

    const requiredInputs = document.querySelectorAll('input[required], textarea[required], select[required]');

    requiredInputs.forEach((element) => {
        if (element.tagName.toLowerCase() === 'input' || element.tagName.toLowerCase() === 'textarea') {
            if (element.classList.contains('cropper-path')) {
                validateCropper(element);
                element.addEventListener('change', () => {
                    validateCropper(element)
                });
            }
            if (element.classList.contains('tinymce')) {
                validateTinyMce(element);
            } else {
                validateInput(element);
                element.addEventListener('input', () => {
                    validateInput(element)
                });
            }
        } else if (element.tagName.toLowerCase() === 'select') {
            validateSelect(element);
            element.addEventListener('change', () => {
                validateInput(element)
            });
        }
    });
});

function validateInput(input) {
    if (input.value.trim() === '') {
        input.classList.add('validation-error');
        input.classList.remove('validation-success');
    } else {
        input.classList.remove('validation-error');
        input.classList.add('validation-success');
    }
}

function validateSelect(select) {
    const tomSelect = select.tomselect;
    if (tomSelect.isValid === false) {
        select.nextElementSibling.classList.add('validation-error');
        select.nextElementSibling.classList.remove('validation-success');
    } else {
        select.nextElementSibling.classList.remove('validation-error');
        select.nextElementSibling.classList.add('validation-success');
    }

    tomSelect.on('change', function () {
        if (tomSelect.isValid === false) {
            select.nextElementSibling.classList.add('validation-error');
            select.nextElementSibling.classList.remove('validation-success');
        } else {
            select.nextElementSibling.classList.remove('validation-error');
            select.nextElementSibling.classList.add('validation-success');
        }
    });
}

function validateCropper(input) {
    const cropperParent = input.closest('.cropper-parent');
    const cropperContainer = cropperParent.querySelector('.cropper-actions');
    const img = cropperParent.querySelector('img');
    const imgSrc = img.getAttribute('src');

    if (imgSrc === '' || imgSrc === '#') {
        cropperContainer.classList.remove('border-dashed');
        cropperContainer.classList.remove('validation-success');
        cropperContainer.classList.add('validation-error');
    }
    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'src') {
                const imgSrc = img.getAttribute('src');
                if (imgSrc === '' || imgSrc === '#') {
                    cropperContainer.classList.remove('border-dashed');
                    cropperContainer.classList.remove('validation-success');
                    cropperContainer.classList.add('validation-error');
                } else {
                    cropperContainer.classList.remove('validation-error');
                    cropperContainer.classList.add('validation-success');
                }
            }
        }
    });

    observer.observe(img, {attributes: true});
}

function validateTinyMce(input) {

    const editor = tinymce.get(input.id);
    const editorContainer = input.closest('[data-controller="tinymce"]');

    if (editor.getContent({format: 'text'}).trim() === '') {
        editorContainer.classList = 'validation-error rounded';
    } else {
        editorContainer.classList = 'validation-success rounded';
    }
}

function keyUp() {
    const editors = document.querySelectorAll('.tinymce')
    editors.forEach( function (input) {
        let editor = tinymce.get(input.id);
        let editorContainer = input.closest('[data-controller="tinymce"]');
        editor.on('keyup', function () {
            input.innerHTML = editor.getContent();
            if (editor.getContent({format: 'text'}).trim() === '') {
                editorContainer.classList = 'validation-error rounded';
                form.stopImmediatePropagation()
            } else {
                editorContainer.classList = 'validation-success rounded';
            }
        });
    })
}
