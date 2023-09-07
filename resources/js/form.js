export default function FormsUsageWithValidation(data) {
    const form = document.querySelector('.form')
    const reg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/

    function checkValidation(fieldName, value) {
        let validationRules = data[fieldName].rules
        let error = null;
        let errorFound = false;
        validationRules.forEach(rule => {
            if (errorFound) {
                return;
            }
            switch (rule.type) {
                case 'required':
                    error = validateCheckMethods.checkForRequire(value, rule)
                    break
                case 'min':
                    error = validateCheckMethods.checkForMin(value, rule)
                    break
                case 'max':
                    error = validateCheckMethods.checkForMax(value, rule)
                    break
                case 'email':
                    error = validateCheckMethods.checkForEmail(value, rule)
                    break;
            }
            if (error !== null) {
                errorFound = true;
            }
        })
        console.log(error)
        return error;
    }

    function setErrorForField(input, error) {
        let next = input.nextElementSibling
        if (error) {
            next.textContent = error
            input.classList.add('error-input')
        } else {
            next.textContent = ''
            input.classList.remove('error-input')
        }
    }

    function checkValidationOnInput(input) {
        input.addEventListener('input', () => {
            let error = checkValidation(input.getAttribute('name'), input.value)
            setErrorForField(input, error)
        })
    }
    function checkValidationOnCheckbox(check) {
        check.addEventListener('change', () => {
            let error = checkValidation(check.getAttribute('name'), check.checked)
            setErrorForField(check, error)
        })
    }
    function checkValidForTextInputs(item) {
        let val = item.value
        let error = checkValidation(item.getAttribute('name'), val)
        setErrorForField(item, error)
        checkValidationOnInput(item)
    }
    function checkValidForCheckbox(item) {
        let val = item.checked
        let error = checkValidation(item.getAttribute('name'), val)
        setErrorForField(item, error)
        checkValidationOnCheckbox(item)
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault()
        // const formData = new FormData(e.target);
        // const formProps = Object.fromEntries(formData);
        let inputs = form.querySelectorAll('.input[type="text"]')
        inputs.forEach(item => {
            checkValidForTextInputs(item)
        })
        let checkbox = form.querySelectorAll('.input[type="checkbox"]')
        checkbox.forEach(item => {
            checkValidForCheckbox(item)
        })
    })
    const validateCheckMethods = {
        checkForRequire: function(val, rule) {
            if (val === '' || val === false) {
                return rule.message
            }
            return null;
        },
        checkForMin: function(val, rule) {
            if (val.length < rule.value) {
                return rule.message
            }
            return null;
        },
        checkForMax: function(val, rule) {
            if (val.length > rule.value) {
                return rule.message
            }
            return null;
        },
        checkForEmail: function(val, rule) {
            if (!reg.test(val)) {
                return rule.message
            }
            return null;
        },
    }
}
