<template>
    <div class="files-list">
        <div class="mb-2 fw-bolder">
            {{ title }}
        </div>
        <div class="files-buttons" v-if="multiple">
            <label for="">
                <input type="file" ref="files" name="filesUpload" class="" multiple @change="handleFileUpload()" >
                <button class="btn btn-primary" @click.prevent="addFiles()">Добавить файлы</button>
            </label>
        </div>
        <div class="files-buttons" v-else>
            <label for="">
                <input type="file" ref="files" name="filesUpload" class=""  @change="handleFileUpload()">
                <button class="btn btn-primary" @click.prevent="addFiles()">Добавить файл</button>
            </label>
        </div>
        <div class="files-list-section" v-if="files.length > 0">
            <div class="file-item" v-for="(file, index) in files" :key="index">
                <span class="file-name">{{file.name }}</span> <span class="file-remove ms-4 text-danger" @click.prevent="removeFile(index)" title="Удалить"><i class="fas fa-trash-alt"></i></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        files: []
    }),
    props: {
        title: String,
        multiple: Boolean
    },
    emits: [
        'data-files'
    ],
    methods: {
        addFiles() {
            this.$refs.files.click();
        },
        handleFileUpload() {
            let filesUpload = this.$refs.files.files;

            if (filesUpload.length > 0) {
                if (this.multiple) {
                    for (let i = 0; i < filesUpload.length; i++) {
                        this.files.push(filesUpload[i]);
                    }
                } else {
                    this.files = [filesUpload[0]]
                }

                this.$emit('data-files', this.files);
            }
        },
        removeFile(key) {
            this.files.splice(key, 1);
            this.$emit('data-files', this.files);
        }
    }
}
</script>

<style>
    .files-list input[type="file"]{
        position: absolute;
        visibility: hidden;
        top: -1000px;
    }
    .files-list .file-remove {
        cursor: pointer;
    }
    .files-list .file-item {
        background-color: #f1f1f1;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .files-list .file-item:not(:last-child) {
        margin-bottom: 0.5rem;
    }
    .files-list-section {
        display: inline-block;
    }
</style>
