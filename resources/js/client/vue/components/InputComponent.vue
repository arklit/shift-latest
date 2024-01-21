<template>
    <label>{{ label }}</label>
    <input :class="className" :type="type" :placeholder="placeholder" :name="name" :value="internalValue" @input="onInput">
    <span class="form-error" v-show="errors.$dirty">{{ errors.$errors[0]?.$message }}</span>
</template>

<script>
export default {
    props: {
        modelValue: String,
        label: String,
        type: String,
        placeholder: String,
        name: String,
        className: String,
        value: String,
        errors: Object
    },
    emits: ['update:modelValue'],

    computed: {
        internalValue: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },
    methods: {
        onInput(event) {
            this.internalValue = event.target.value;
        }
    }
}
</script>
