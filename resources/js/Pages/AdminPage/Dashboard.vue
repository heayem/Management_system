<script>
import MainLayout from '@/Pages/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Toast from 'primevue/toast';

export default {
    props: {
        totalUsers: { type: Object, default: () => [] },
        totalDepartments: { type: Object, default: () => [] },
        flash: { type: Object, default: null },
    },
    mounted() {
        this.$toast.add({ severity: 'info', summary: 'Info', detail: 'Message Content', life: 3050 });
        if (this.flash.success) {
            this.$toast.add({ severity: 'success', summary: 'Success Message', detail: this.flash.success, group: 'br', life: 3000 });
        } else if (this.flash.error) {
            this.$toast.add({ severity: 'error', summary: 'Error Message', detail: this.flash.error, group: 'br', life: 3000 });
        }
    },
    components: {
        MainLayout,
        Head,
        Link,
        Toast
    }
}
</script>
<template>
    <MainLayout>
        <div class="w-full flex justify-center pt-5  space-x-10">
            <div class="w-fit h-5 border border-gray-800 rounded-md space-x-3  py-5 px-10 flex items-center ">
                <i class="pi pi-users"></i>
                <p>Total employees </p>
                <p>{{ totalUsers }}</p>
            </div>
            <div class="w-fit h-5  border border-gray-800 rounded-md space-x-3 py-5 px-10 flex items-center ">
                <i class="pi pi-users"></i>
                <p>Total departments </p>
                <p>{{ totalDepartments }}</p>
            </div>
        </div>

    </MainLayout>
    <Toast position="bottom-right" group="br" />
</template>