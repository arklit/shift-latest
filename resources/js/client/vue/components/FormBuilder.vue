<template>
    <form :class="formInfo.form_class" v-if="form">
        {{ formInfo.title }} <br>
        {{ formInfo.description }}
        <div v-for="(field, key) in form" :class="field.container_class ?? 'fields_group'" :key="key">
            <div v-if="key.includes('fields_')" :class="field.container_class" v-for="(field, subKey) in field"
                 :key="subKey">
                <component
                    :is="field.component"
                    :name="subKey"
                    :label="field.label"
                    :value="field.value"
                    :modelValue="formModel[subKey]"
                    :type="field.type"
                    :placeholder="field.placeholder"
                    :class-name="field.input_class"
                    :errors="v$.formModel[subKey]"
                    :mask="field.mask ?? ''"
                    :multiple="field.multiple ?? false"
                    @update:modelValue="updateField(subKey, $event);"
                />
            </div>
            <component
                v-else
                :is="field.component"
                :name="key"
                :label="field.label"
                :value="field.value"
                :modelValue="formModel[key]"
                :type="field.type"
                :placeholder="field.placeholder"
                :class-name="field.input_class"
                :errors="v$.formModel[key]"
                :mask="field.mask ?? ''"
                :multiple="field.multiple ?? false"
                @update:modelValue="updateField(key, $event);"
            />
        </div>
        <button @click.prevent="onSubmit" :class="formInfo.btn_class">{{ formInfo.btn_text }}</button>
    </form>
</template>

<script>
import axios from "axios";
import {isInteger, toNumber} from "lodash/lang";
import InputComponent from './InputComponent.vue'
import SelectComponent from "./SelectComponent.vue";
import DatePickerComponent from "./DatePickerComponent.vue";
import FileComponent from "./FileComponent.vue";
import {useVuelidate} from '@vuelidate/core';
import {
    required,
    minLength,
    maxLength,
    helpers,
    email,
    numeric,
    alpha,
    alphaNum,
    integer,
    decimal,
    requiredIf,
    requiredUnless,
    between, sameAs
} from '@vuelidate/validators';

export default {
    name: 'FormBuilder',
    components: {
        InputComponent,
        SelectComponent,
        DatePickerComponent,
        FileComponent
    },
    props: {
        name: String
    },
    data() {
        return {
            form: {},
            formInfo: {},
            formModel: {},
            validationForm: {},
            v$: useVuelidate(),
            validations: {},
            formData: {},
        };
    },
    mounted() {
        this.getFormConfig();
    },
    methods: {
        toNumber,
        isInteger,
        onSubmit() {
            this.v$.$validate();
            if (this.v$.$error) {
                console.log('error');
            } else {
                console.log('success');
                this.fillFormData();
                console.log(this.formData)
                this.sendFormData();
            }
        },
        fillFormData() {
            this.formData = new FormData();
            for (let key in this.formModel) {
                if (this.formModel[key] instanceof File) {
                    this.formData.append(key, this.formModel[key]);
                } else if (typeof this.formModel[key] === 'object' && this.formModel[key].value !== null) {
                    this.formData.append(key, this.formModel[key].value);
                } else {
                    this.formData.append(key, this.formModel[key]);
                }
            }
        },
        async sendFormData() {
            try {
                const response = await axios.post('/ajax/forms/' + this.name + '/send', this.formData);
                console.log('Form sent successfully', response);
            } catch (error) {
                console.error('Error sending form', error);
            }
        },
        updateField(fieldName, value) {
            this.formModel[fieldName] = value;
        },
        async getFormConfig() {
            try {
                const response = await axios.post('/ajax/get-form-config/' + this.name);
                const formConfig = response.data;
                this.validationForm = formConfig;

                this.form = formConfig.form || null;
                this.formInfo = formConfig?.info || null;
                const {messages} = this.extractValidationRulesAndMessages(this.form);
                this.validations = messages;
                this.formModel = this.generateFormModel(this.form);
            } catch (error) {
                console.log(error);
            }
        },

        createRule(ruleName, ruleValue) {
            if (ruleName === 'required') {
                return required;
            } else if (ruleName === 'requiredIf') {
                return requiredIf(ruleValue);
            } else if (ruleName === 'requiredUnless') {
                return requiredUnless(ruleValue);
            } else if (ruleName === 'minLength') {
                return minLength(ruleValue);
            } else if (ruleName === 'maxLength') {
                return maxLength(ruleValue);
            } else if (ruleName === 'email') {
                return email;
            } else if (ruleName === 'numeric') {
                return numeric;
            } else if (ruleName === 'alpha') {
                return alpha;
            } else if (ruleName === 'alphaNum') {
                return alphaNum;
            } else if (ruleName === 'integer') {
                return integer;
            } else if (ruleName === 'decimal') {
                return decimal;
            } else if (ruleName === 'between') {
                const [min, max] = ruleValue;
                return between(min, max);
            } else if (ruleName === 'sameAs') {
                return sameAs(ruleValue);
            } else {
                return () => ''; // Правило по умолчанию, если название не распознано
            }
        },

        processFields(fields, parentPath, validationRules, validationMessages) {
            for (const key in fields) {
                const field = fields[key];
                const path = parentPath ? `${key}` : key;
                if (field.rules) {
                    validationRules[path] = {};
                    validationMessages[path] = {};
                    for (const ruleKey in field.rules) {
                        if (ruleKey && field.rules[ruleKey]) {
                            const rule = field.rules[ruleKey];
                            const ruleFunction = this.createRule(ruleKey, rule);
                            validationRules[path][ruleKey] = rule;
                            validationMessages[path][ruleKey] = helpers.withMessage(field.messages[ruleKey], ruleFunction);
                        }
                    }
                } else if (typeof field === 'object') {
                    this.processFields(field, path, validationRules, validationMessages);
                }
            }
        },

        extractValidationRulesAndMessages(fields) {
            const validationRules = {};
            const validationMessages = {};

            this.processFields(fields, '', validationRules, validationMessages);

            return {rules: validationRules, messages: validationMessages};
        },

        generateFormModel(form) {
            const model = {};
            for (const key in form) {
                if (key.includes('fields_')) {
                    Object.assign(model, form[key].value);
                } else {
                    model[key] = form[key].value;
                }
            }
            return model;
        },
    },
    validations() {
        if (this.validations) {
            return {
                formModel: {
                    ...this.validations
                }
            }
        }
    }
}
</script>

