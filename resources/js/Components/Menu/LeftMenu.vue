<template>
    <div class="card h-screen flex justify-content-center">
        <Menu v-if="$page.props.auth.user.role === 'administrator'" :model="items" >
            <template #item="{ item, props }">
                <Link v-if="item.route" :href="item.route">
                <a v-bind="props.action" @click="navigate">
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
                </Link>
                <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
            </template>
        </Menu>
        <Menu v-if="$page.props.auth.user.role === 'department_administrator'" :model="adItems">
            <template #item="{ item, props }">
                <Link v-if="item.route" :href="item.route">
                <a v-bind="props.action" @click="navigate">
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
                </Link>
                <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
            </template>
        </Menu>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Menu from 'primevue/menu';
import { Link } from '@inertiajs/vue3';



const items = ref([
    {
        items: [
            {
                label: 'Dashboard',
                icon: 'pi pi-chart-pie',
                route: '/dashboard',
            },
            {
                label: 'Employee',
                icon: 'pi pi-users',
                route: '/employee',
            },
            {
                label: 'Employee Department',
                icon: 'pi pi-users',
                route: '/employee-department',
            },
            {
                label: 'Department',
                icon: 'pi pi-users',
                route: '/department',
            }
        ]
    },
]);

const adItems = ref([
    {
        items: [
            {
                label: 'Department request',
                icon: 'pi pi-chart-pie',
                route: '/request',
            },
            {
                label: 'Employee Department',
                icon: 'pi pi-users',
                route: '/employee-department',
            },
        ]
    },
]);
</script>
