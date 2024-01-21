import './bootstrap';
import './ui';
import './vue/initialization'
import FormsUsageWithValidation from "./form";

let data = {
        name: {
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
                {
                    type: "min",
                    value: 4,
                    message: "Символов должно быть больше 4"
                },
                {
                    type: "max",
                    value: 30,
                    message: "Символов должно быть не больше 30"
                },
                {
                    type: "email",
                    value: true,
                    message: "Тут должен быть электронный адрес"
                }
            ]
        },
        phone: {
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
                {
                    type: "min",
                    value: 4,
                    message: "Символов должно быть больше 4"
                },
                {
                    type: "max",
                    value: 30,
                    message: "Символов должно быть не больше 30"
                },
            ]
        },
        msg: {
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
                {
                    type: "min",
                    value: 4,
                    message: "Символов должно быть больше 4"
                },
                {
                    type: "max",
                    value: 30,
                    message: "Символов должно быть не больше 30"
                },
            ]
        },
        checkbox: {
            rules: [
                {
                    type: "required",
                    value: true,
                    message: "Поле обязательно для заполнения"
                },
            ]
        },
        select: {
            rules: [
                {
                    type: "required",
                    value: true,
                    message: 'Поле обязательно для заполнения'
                }
            ]
        }
}
FormsUsageWithValidation(data)
