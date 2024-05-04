<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LeftMenu from '@/Components/Menu/LeftMenu.vue'
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link,
        LeftMenu
    }
}
</script>
<template>
<authenticated-layout>
    <LeftMenu/>
    
    
</authenticated-layout>

</template>