<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LeftMenu from '@/Components/Menu/LeftMenu.vue'

export default {
    components: {
        AuthenticatedLayout,
        LeftMenu
    }
}
</script>
<template>
    <authenticated-layout>
        <div class="flex flex-row">
            <LeftMenu />
                <slot />
        </div>
    </authenticated-layout>

</template>