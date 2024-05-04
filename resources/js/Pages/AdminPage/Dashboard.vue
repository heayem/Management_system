<script>
import MainLayout from '@/Pages/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    props:{
        totalUsers: { type: Object, default: () => [] },
        totalDepartments: { type: Object, default: () => [] },

    },
    components: {
        MainLayout,
        Head,
        Link,
    }
}
</script>
<template>
<MainLayout>
    <div class="w-full flex justify-center pt-5  space-x-10">
        <div class="w-fit h-5 border border-gray-800 rounded-md space-x-3  py-5 px-10 flex items-center "> 
            <i class="pi pi-users"></i> <p>Total  employees </p> <p>{{ totalUsers }}</p>
        </div>
        <div class="w-fit h-5  border border-gray-800 rounded-md space-x-3 py-5 px-10 flex items-center "> 
            <i class="pi pi-users"></i> <p>Total  departments </p> <p>{{ totalDepartments }}</p>
        </div>
    </div>
    
</MainLayout>

</template>