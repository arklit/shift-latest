// props.js
export const commonProps = {
    modelValue: String|Object,
    label: String,
    type: String,
    placeholder: String,
    name: String,
    className: String,
    value: String,
    errors: Object,
    mask: String,
    accept: String, // Добавляем новый пропс accept для указания допустимых типов файлов
    multiple: {
        type: Boolean,
        default: false
    } // Добавляем новый пропс multiple для возможности выбора нескольких файлов
};
