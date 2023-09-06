import $ from 'jquery';

const form = document.querySelector('.form')

let data = {
    errors: {
        name: {
            name: 'name',
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
                {
                    type: "min",
                    value: 4,
                    message: "Символов должно быть больше 2"
                },
                {
                    type: "max",
                    value: 30,
                    message: "Символов должно быть больше 2"
                },
                {
                    type: "email",
                    value: true,
                    message: "Тут должен быть электронный адрес"
                }
            ]
        },
        phone: {
            name: 'phone',
            msg: '123123asdasd',
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
                {
                    type: "min",
                    value: 4,
                    message: "Символов должно быть больше 2"
                },
                {
                    type: "max",
                    value: 30,
                    message: "Символов должно быть больше 2"
                }
            ]
        },
        msg: {
            name: 'msg',
            msg: 'asd12312dasfafdasd',
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
                {
                    type: "min",
                    value: 4,
                    message: "Символов должно быть больше 2"
                },
                {
                    type: "max",
                    value: 30,
                    message: "Символов должно быть больше 2"
                }
            ]
        }
    }
}

function showErr(err) {
    err.css('display', 'block')
}

function hideErrors(err) {
    err.each(function () {
        $(this).css('display', 'none')
    })
}

let inputs = $('.input')
let errs = data.errors
/*inputs.each( function () {
    const err = $(this).next()
    hideErrors(err)
    let inputName = $(this).attr('name')
    let rules
    /!*errs.forEach(item => {
        if(item.name === inputName) {
            rules = item.rules
        }
    })*!/
    if(rules.required) {
        $(this).attr('data-req', true)
    }
    if(rules.min) {
        $(this).attr('data-min', rules.min.value)
    }
    if(rules.max) {
        $(this).attr('data-max', rules.max.value)
    }
    if(rules.email) {
        $(this).attr('data-email', rules.email.value)
    }
})*/


function checkForRequired(val, errors, rule) {
    if(val === '') {
        errors.push(rule.message)
    }
}
function checkForMin(val, errors, rule) {
    if(val.length < rule.value) {
        errors.push(rule.message)
    }
}
function checkForMax(val, errors, rule) {
    if(val.length > rule.value) {
        errors.push(rule.message)
    }
}
function checkForEmail(val, errors, rule) {
    if(!reg.test(val)) {
        errors.push(rule.message)
    }
}
let reg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/
form.addEventListener('submit', (e) => {
    e.preventDefault()
    const formData = new FormData(e.target);
    const formProps = Object.fromEntries(formData);
    let errors = [];
    inputs.each(function () {
        let validationRules = data.errors[$(this).attr('name')].rules
        console.log(validationRules)
        let val = $(this).val()
        validationRules.forEach(rule => {
            switch (rule.type) {
                case 'required':
                    checkForRequired(val, errors, rule)
                    break;
                case 'min':
                    checkForMin(val, errors, rule)
                    break;
                case 'max':
                    checkForMax(val, errors, rule)
                    break;
                case 'email':
                    checkForEmail(val, errors, rule)
                    break;
            }
        })
        /*let val = $(this).val()
        let err = $(this).next()
        if ($(this).data('req') && val === '') {
            showErr(err)
            err.text('Поле не должно быть пустым')
            isValid = false
        } else if ($(this).data('min') && val.length < $(this).data('min')) {
            showErr(err)
            err.text(`В поле должно быть не меньше ${$(this).data('min')} символов`)
            isValid = false
        } else if ($(this).data('max') && val.length > $(this).data('max')) {
            showErr(err)
            err.text(`В поле должно быть не больше ${$(this).data('max')} символов`)
            isValid = false
        } else if ($(this).data('email') && !reg.test(val)) {
            showErr(err)
            err.text(`Это должен быть email`)
            isValid = false
        } else {
            hideErrors(err)
        }*/
    })
    console.log(errors);
    // console.log(formProps)
})
