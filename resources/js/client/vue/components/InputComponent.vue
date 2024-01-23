<template>
    <label :for="id" v-html="label"></label>
    <input :id="id" v-mask="mask" :class="className" :type="type" :placeholder="placeholder" :name="name" :value="internalValue" @input="onInput">
    <span v-if="errors" class="form-error" v-show="errors.$dirty">{{ errors.$errors[0]?.$message }}</span>
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
