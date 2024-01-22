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
        SelectComponent
    },
    props: {
        name: String
    },
    data() {
        return {
            form: {},
            formInfo: {},
            formModel: this.generateFormModel(this.form),
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
                this.sendFormData();
            }
        },
        fillFormData() {
            this.formData = {};
            for (let key in this.formModel) {
                if (typeof this.formModel[key] === 'object' && this.formModel[key].value !== null) {
                    this.formData[key] = this.formModel[key].value;
                } else {
                    this.formData[key] = this.formModel[key];
                }
            }
        },
        async sendFormData() {
            try {
                const response = await axios.post('/ajax/forms/' + this.formInfo.key + '/send', this.formData);
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

                this.form = formConfig?.form || null;
                this.formInfo = formConfig?.info || null;
                const {messages} = this.extractValidationRulesAndMessages(formConfig, this.name);
                this.validations = messages;

                console.log(this.form, this.formInfo, this.validations);
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

        processField(field, path, validationRules, validationMessages) {
            if (field.rules) {
                validationRules[path] = {};
                validationMessages[path] = {};
                for (const ruleKey in field.rules) {
                    if (ruleKey === null || ruleKey === undefined || ruleKey === '') {
                        continue; // Не требуется валидация для пустых полей
                    }
                    const rule = field.rules[ruleKey];
                    const ruleFunction = this.createRule(ruleKey, rule);
                    validationRules[path][ruleKey] = rule;
                    validationMessages[path][ruleKey] = helpers.withMessage(field.messages[ruleKey], ruleFunction);
                }
            } else if (typeof field === 'object') {
                for (const key in field) {
                    this.processField(field[key], `${key}`, validationRules, validationMessages);
                }
            }
        },

        extractValidationRulesAndMessages(config, formName) {
            const validationRules = {};
            const validationMessages = {};

            const form = config[formName];
            if (form) {
                this.processField(form, formName, validationRules, validationMessages);
            } else {
                console.error(`Form with name '${formName}' not found in the config.`);
            }

            return {rules: validationRules, messages: validationMessages};
        },

        generateFormModel(formConfig) {
            const model = {};
            for (const key in formConfig) {
                if (key !== 'info' && key !== 'form' && typeof formConfig[key] === 'object') {
                    if (key.includes('fields_')) {
                        Object.assign(model, formConfig[key]);
                    } else {
                        model[key] = formConfig[key].value;
                    }
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

