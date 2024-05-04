<script>

export default {
    props: {
        isImage: {
            type: [Object, String],
            default: null,
        },
        hasMulti: { type: Boolean, default: false },
    },
    emits: ['imageUploaded'],
    data() {
        return {
            image: null,
        }
    },
    methods: {
        handleDrop(event) {
            event.preventDefault()
            const files = event.dataTransfer.files
            if (files.length > 0) {
                this.$emit('imageUploaded', files[0])
                this.image = URL.createObjectURL(files[0])
            }
        },
        handleFileInput(event) {
            const files = event.target.files
            if (files.length > 0) {
                this.$emit('imageUploaded', files[0])
                this.image = URL.createObjectURL(files[0])
            }
        },
    },
}
</script>

<template>
    <div class="border-dashed border-2 border-gray-400"
        :class="!image && !isImage ? 'flex justify-center items-center h-36' : ''" @dragover.prevent @dragenter.prevent
        @drop="handleDrop">
        <p class="w-fit text-center flex justify-center items-center gap-2 z-0 absolute h-10"
            :class="!image || !isImage ? 'fixed' : ''">
            <i class="pi pi-image" style="font-size: 2rem"></i>
        <p class="hidden md:block">change image</p>
        </p>
        <input type="file" class="w-full h-full opacity-0 z-10" @change="handleFileInput" />
        <div v-if="image" class="w-auto h-[auto] pt-4">
            <img :src="image" alt="Uploaded Image" />
        </div>
        <div v-if="!image && isImage" class="w-auto h-[auto] pt-4">
            <img :src="isImage" alt="Uploaded Image" />
        </div>
    </div>
</template>