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
import axios from 'axios'


export default {
    props: {
        users: { type: Object, default: () => [] }
    },
    data() {
        return {
            data: this.users,
            columns: [
                { field: 'id', header: 'ID' },
                { field: 'fname', header: 'First Name' },
                { field: 'lname', header: 'Last Name' },
                { field: 'profile', header: 'Profile' },
                { field: 'phone', header: 'Phone' },
                { field: 'role', header: 'Role' },
                { field: 'id', header: 'Action' }
            ],
            form: useForm({
                id: null,
                fname: null,
                lname: null,
                phone: null,
                role: null,
                departments: null
            }),
            searchQuery: '',
            filteredData: this.users
        };
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
        Image
    },
    methods: {
       
        updateProfile(data) {
            this.form = data
            this.form.post(route('profile.update'));
        },
        search() {
            const query = this.searchQuery.toLowerCase();
            this.filteredData = this.users.filter(user =>
                (user.id && typeof user.id === 'string' && user.id.toLowerCase().includes(query)) ||
                (user.fname && typeof user.fname === 'string' && user.fname.toLowerCase().includes(query)) ||
                (user.lname && typeof user.lname === 'string' && user.lname.toLowerCase().includes(query)) ||
                (user.phone && typeof user.phone === 'string' && user.phone.toLowerCase().includes(query)) ||
                (user.role && typeof user.role === 'string' && user.role.toLowerCase().includes(query)) ||
                (user.departments && typeof user.departments === 'string' && user.departments.toLowerCase().includes(query))
            );
        },
        async handleDelete(id) {
            await axios.delete('employee-delete/' + id)
        }
    }
}
</script>


<template>
    <MainLayout>
        <div class="w-full h-screen">
            <div class="p-5 card ">
                <DataTable :value="filteredData"  paginator showGridlines :rows="5"
                    :rowsPerPageOptions="[5, 10, 20, 50]" selectionMode="single" dataKey="id"
                    :globalFilterFields="['fname', 'lname', 'phone', 'role', 'departments']" :metaKeySelection="false">
                    <template #header>
                        <div class="flex justify-between">
                            <IconField iconPosition="left">
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="searchQuery" placeholder="Search..." @input="search" />
                            </IconField>
                            <div class="flex justifu-center space-x-2">
                                <Link :href="route('register')">
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
                                {{ data[col.field] || '___ ___ "" ___ ___ ' }}
                            </div>
                            <div v-else class="flex justify-center space-x-2">
                                <Link :href="'employee-edit/' + data[col.field]">
                                <Button label="Edit" icon="pi pi-pen-to-square" severity="info" raised />
                                </Link>
                                <Button label="Delete" type="submit" @click="handleDelete(data[col.field])"
                                    icon="pi pi-trash" severity="danger" raised />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </MainLayout>
</template>