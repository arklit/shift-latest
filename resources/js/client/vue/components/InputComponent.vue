<template>
    <label>{{ label }}</label>
    <input v-mask="mask" :class="className" :type="type" :placeholder="placeholder" :name="name" :value="internalValue" @input="onInput">
    <span class="form-error" v-show="errors.$dirty">{{ errors.$errors[0]?.$message }}</span>
</template>

<script>
import VueMask from 'v-mask';
import { commonProps } from '../props.js';
export default {
    props: commonProps,
    emits: ['update:modelValue'],
    components: {
        VueMask
    },
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
