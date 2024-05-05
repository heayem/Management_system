<script>
import MainLayout from '@/Pages/MainLayout.vue';
import { useForm } from '@inertiajs/vue3'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/InputIcon';
import IconField from 'primevue/IconField';
import { route, Link } from '@inertiajs/inertia-vue3';
import Button from 'primevue/button';
import Image from 'primevue/image';
import axios from 'axios';
import Toast from 'primevue/toast';


export default {
    props: {
        departments: { type: Object, default: () => [] },
        flash: { type: Object, default: null },
    },
    data() {
        return {
            data: this.departments,
            columns: [
                { field: 'id', header: 'ID' },
                { field: 'name', header: 'Name' },
                { field: 'id', header: 'Action' }
            ],
            form: useForm({
                id: null,
                name: null,
            }),
            searchQuery: '',
            filteredData: this.departments
        };
    },
    mounted() {
        if (this.flash.success) {
            this.$toast.add({ severity: 'success', summary: 'Success Message', detail: this.flash.success, group: 'br', life: 3000 });
        } else if (this.flash.error) {
            this.$toast.add({ severity: 'error', summary: 'Error Message', detail: this.flash.error, group: 'br', life: 3000 });
        }
    },
    components: {
        MainLayout,
        DataTable,
        Column,
        Button,
        InputIcon,
        IconField,
        InputText,
        Link,
        Image,
        Toast
    },
    methods: {
        onRowSelect(event) {
            alert(event)
            console.log(event)
        },
        updateProfile(data) {
            this.form = data
            this.form.post(route('profile.update'));
        },
        search() {
            const query = this.searchQuery.toLowerCase();
            this.filteredData = this.departments.filter(department =>
                (department.id && typeof department.id === 'string' && department.id.toLowerCase().includes(query)) ||
                (department.name && typeof department.name === 'string' && department.name.toLowerCase().includes(query))
            );
        },
        async handleDelete(id) {
            await axios.delete('department/' + id)
        }
    }
}
</script>


<template>
    <MainLayout>
        <div class="w-full h-screen overflow-x-auto">
            <div class="p-5 card ">
                <DataTable :value="filteredData" paginator showGridlines :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                    selectionMode="single" dataKey="id" :globalFilterFields="['name']" :metaKeySelection="false">
                    <template #header>
                        <div class="flex justify-between">
                            <IconField iconPosition="left">
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="searchQuery" placeholder="Search..." @input="search" />
                            </IconField>
                            <div class="flex justifu-center space-x-2">
                                <Link :href="route('department-create')">
                                <Button label="New" icon="pi pi-users" raised />
                                </Link>
                            </div>
                        </div>
                    </template>
                    <template #empty>
                        <div class="w-full h-32 font-semibold text-xl flex space-x-2 items-center justify-center">
                            <p>Oop! No data found.</p> <i class="pi pi-database" style="font-size: 2rem"></i>
                        </div>
                    </template>
                    <Column v-for="col in columns" :field="col.field" :header="col.header">
                        <template #body="{ data }">
                            <div v-if="col.header === 'Profile'" class="flex justify-center">
                                <Image :src="'/images/' + data[col.field]" alt="Image" width="50" preview />
                            </div>
                            <div v-else-if="col.header !== 'Action'">
                                {{ data[col.field] }}
                            </div>
                            <div v-else class="flex justify-center space-x-2">
                                <Link :href="'department-edit/' + data[col.field]">
                                <Button class="w-24" label="Edit" icon="pi pi-pen-to-square" severity="info" raised />
                                </Link>
                                <Button class="w-24" label="Delete" type="submit" @click="handleDelete(data[col.field])"
                                    icon="pi pi-trash" severity="danger" raised />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </MainLayout>
    <Toast position="bottom-right" group="br" />
</template>
