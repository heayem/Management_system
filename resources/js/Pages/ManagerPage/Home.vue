<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/InputIcon';
import IconField from 'primevue/IconField';
import { route, Link, useForm } from '@inertiajs/inertia-vue3';
import Button from 'primevue/button';
import Image from 'primevue/image';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';

export default {
    props: {
        requestForms: { type: Object, default: () => [] },
        flash: { type: Object, default: null },
    },
    data() {
        return {
            data: this.requestForms,
            showDetail: false,
            requestDetail: null,
            toastPosition: { type: String, default: 'top-center' },
            columns: [
                { field: 'id', header: 'ID' },
                { field: 'user_name', header: 'Staff' },
                { field: 'role', header: 'Role' },
                { field: 'department_name', header: 'Department' },
                { field: 'title', header: 'Subject' },
                { field: 'start', header: 'Start date' },
                { field: 'end', header: 'End date' },
                { field: 'reason', header: 'Reason' },
                { field: 'status', header: 'Status' },
                { field: '', header: 'Action' },
            ],
            form: useForm({
                id: null,
                type: null,
                status: { type: String, default: 'pending' },
            }),
            searchQuery: '',
            filteredData: this.requestForms
        };
    },
    mounted() {
        this.toastPosition = 'top-center'
        this.$toast.add({ severity: 'info', summary: 'Info', detail: 'Message Content', life: 3050 });
        if (this.flash.success) {
            toastPosition = 'bottom-right'
            this.$toast.add({ severity: 'success', summary: 'Success Message', detail: this.flash.success, group: 'br', life: 3000 });
        } else if (this.flash.error) {
            toastPosition = 'bottom-right'
            this.$toast.add({ severity: 'error', summary: 'Error Message', detail: this.flash.error, group: 'br', life: 3000 });
        }
    },
    components: {
        AuthenticatedLayout,
        DataTable,
        Column,
        Button,
        InputIcon,
        IconField,
        InputText,
        Link,
        Image,
        Dialog,
        Toast
    },
    methods: {
        handleDetial(event) {
            this.requestDetail = event
            this.showDetail = !this.showDetail
        },
        handleStatus(data, status) {
            this.form.type = data.type
            this.form.id = data.id
            this.form.status = status
            this.form.post('request-manger-approve', {
                onFinish: () => this.form.reset(),
            });
        },
        search() {
            const query = this.searchQuery.toLowerCase();
            this.filteredData = this.requestForms.filter(requestForm =>
                (requestForm.id && typeof requestForm.id === 'string' && requestForm.id.toLowerCase().includes(query)) ||
                (requestForm.start && typeof requestForm.start === 'string' && requestForm.start.toLowerCase().includes(query)) ||
                (requestForm.end && typeof requestForm.end_date === 'string' && requestForm.end.toLowerCase().includes(query)) ||
                (requestForm.reason && typeof requestForm.reason === 'string' && requestForm.reason.toLowerCase().includes(query)) ||
                (requestForm.status && typeof requestForm.status === 'string' && requestForm.status.toLowerCase().includes(query))
            );
        },
    }
}
</script>


<template>
    <AuthenticatedLayout>
        <div class="w-full h-screen">
            <div class="p-5 card ">
                <DataTable :value="filteredData" paginator showGridlines :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                    selectionMode="single" dataKey="id"
                    :globalFilterFields="['subject', 'start', 'end', 'reason', 'status']" :metaKeySelection="false">
                    <template #header>
                        <div class="flex justify-between">
                            <IconField iconPosition="left">
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="searchQuery" placeholder="Keyword Search" @input="search" />
                            </IconField>
                            <div class="flex justifu-center space-x-2">
                                <Link :href="route('request-manger-create')">
                                <Button label="New request" icon="pi pi-book" raised />
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

                            <div v-if="col.header === 'Reason'" @click="handleDetial(data)">
                                <div v-html="data[col.field].substr(0, 20) + '...'"></div>
                            </div>
                            <div v-else-if="col.header === 'Status'" @click="handleDetial(data)">
                                <div v-if="data[col.field] === 'pending'"
                                    class="bg-yellow-400 w-full text-center p-2 text-yellow-50">
                                    {{ data[col.field] }}
                                </div>
                                <div v-if="data[col.field] === 'approved'"
                                    class="bg-green-400 w-full text-center p-2 text-green-50">
                                    {{ data[col.field] }}
                                </div>
                                <div v-if="data[col.field] === 'rejected'"
                                    class="bg-red-400 w-full text-center p-2 text-red-50">
                                    {{ data[col.field] }}
                                </div>
                            </div>
                            <div v-else @click="handleDetial(data)">
                                {{ data[col.field] }}
                            </div>
                            <div v-if="col.header == 'Action'" class="flex justify-center space-x-2">
                                <Button @click="handleStatus(data, 'approved')" class="w-24" label="Approve" severity="info"
                                    raised />
                                <Button @click="handleStatus(data, 'rejected')" class="w-24" label="Reject" severity="danger"
                                    raised />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <div class="card flex justify-content-center">
            <Dialog v-model:visible="showDetail" modal :header="'Request for ' + requestDetail?.type"
                :style="{ width: '25rem' }">
                <div class="grid grid-cols-2">
                    <div class="border text-center p-2">Name</div>
                    <div class="border text-center p-2">{{ requestDetail?.user_name }}</div>
                    <div class="border text-center p-2">Position</div>
                    <div class="border text-center p-2">{{ requestDetail?.department_name }}</div>
                    <div class="border text-center p-2">Role</div>
                    <div class="border text-center p-2">{{ requestDetail?.role }}</div>
                    <div class="border text-center p-2">Start date</div>
                    <div class="border text-center p-2">{{ requestDetail?.start }}</div>
                    <div class="border text-center p-2">End date</div>
                    <div class="border text-center p-2">{{ requestDetail?.end }}</div>
                    <div class="border text-center p-2">Status</div>
                    <div class="border text-center p-2">
                        <div v-if="requestDetail.status === 'pending'"
                            class="bg-yellow-400 w-full text-center p-2 text-yellow-50">
                            {{ requestDetail.status }}
                        </div>
                        <div v-if="requestDetail.status === 'approved'"
                            class="bg-green-400 w-full text-center p-2 text-green-50">
                            {{ requestDetail.status }}
                        </div>
                        <div v-if="requestDetail.status === 'rejected'"
                            class="bg-red-400 w-full text-center p-2 text-red-50">
                            {{ requestDetail.status }}
                        </div>
                    </div>
                    <div class="border text-center p-2 col-span-2">Reason</div>
                    <div class="border flex-wrap p-2 col-span-2">
                        <div v-html="requestDetail?.reason"></div>
                    </div>
                </div>
            </Dialog>
        </div>
    </AuthenticatedLayout>
    <Toast :position="toastPosition" group="br" />
</template>
