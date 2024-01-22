<template>
    <div>
        <label>{{ label }}</label>
        <input :class="className" type="file" :name="name" :multiple="multiple" @change="onFileChange">
        <span class="form-error" v-show="errors.$dirty">{{ errors.$errors[0]?.$message }}</span>

        <div v-if="multiple" v-for="(file, index) in downloadedFiles" :key="index">
            <span>{{ file.name }}</span>
            <button @click.prevent="deleteFile(index)">Удалить</button>
        </div>
    </div>
</template>

<script>
import { commonProps } from '../props.js';

export default {
    props: commonProps,
    emits: ['update:modelValue'],
    computed: {
        downloadedFiles() {
            return this.modelValue || [];
        }
    },
    methods: {
        onFileChange(event) {
            const files = event.target.files;
            if (files.length === 0) {
                this.$emit('update:modelValue', null); // Очищаем поле, если количество файлов становится нулевым
            } else {
                if (this.multiple) {
                    this.$emit('update:modelValue', [...this.downloadedFiles, ...files]);
                } else {
                    this.$emit('update:modelValue', files[0]);
                }
            }
        },
        deleteFile(index) {
            const updatedFiles = this.downloadedFiles.slice();
            updatedFiles.splice(index, 1);
            this.$emit('update:modelValue', updatedFiles);
        }
    }
}
</script>
