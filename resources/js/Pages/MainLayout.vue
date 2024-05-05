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
            <LeftMenu v-if="$page.props.auth.user.role ==='administrator' || $page.props.auth.user.role ==='department_administrator'" />

            <slot />
        </div>
    </authenticated-layout>

</template>