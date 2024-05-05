<script>
import MainLayout from '@/Pages/MainLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/InputIcon';
import IconField from 'primevue/IconField';
import { route, Link } from '@inertiajs/inertia-vue3';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';


export default {
    props: {
        requestForms: { type: Object, default: () => [] },
        flash: { type: Object, default: null },
    },
    data() {
        return {
            showDetail: false,
            requestDetail: null,
            data: this.requestForms,
            columns: [
                { field: 'id', header: 'ID' },
                { field: 'title', header: 'Subject' },
                { field: 'start_date', header: 'Start date' },
                { field: 'end_date', header: 'End date' },
                { field: 'reason', header: 'Reason' },
                { field: 'status', header: 'Status' },
            ],
            searchQuery: '',
            filteredData: this.requestForms
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
        Dialog,
        Toast
    },
    methods: {
        handleDetial(event) {
            this.requestDetail = event
            this.showDetail = !this.showDetail
        },
        search() {
            const query = this.searchQuery.toLowerCase();
            this.filteredData = this.requestForms.filter(requestForm =>
                (requestForm.id && typeof requestForm.id === 'string' && requestForm.id.toLowerCase().includes(query)) ||
                (requestForm.start_date && typeof requestForm.start_date === 'string' && requestForm.start_date.toLowerCase().includes(query)) ||
                (requestForm.end_date && typeof requestForm.end_date === 'string' && requestForm.end_date.toLowerCase().includes(query)) ||
                (requestForm.reason && typeof requestForm.reason === 'string' && requestForm.reason.toLowerCase().includes(query)) ||
                (requestForm.status && typeof requestForm.status === 'string' && requestForm.status.toLowerCase().includes(query))
            );
        },
    }
}
</script>


<template>
    <MainLayout>
        <div class="w-full h-screen">
            <div class="p-5 card ">
                <DataTable :value="filteredData" paginator showGridlines :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                    selectionMode="single" dataKey="id"
                    :globalFilterFields="['title', 'start_date', 'end_date', 'reason', 'status']"
                    :metaKeySelection="false">
                    <template #header>
                        <div class="flex justify-between">
                            <IconField iconPosition="left">
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText class="w-32 md:w-96" v-model="searchQuery" placeholder="Search..."
                                    @input="search" />
                            </IconField>
                            <div class="flex justifu-center space-x-2">
                                <Link :href="route('request-staff-create')">
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
                            <div v-if="col.header == 'Status'" @click="handleDetial(data)">
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
                            <div v-else-if="col.header !== 'Reason'" @click="handleDetial(data)">
                                {{ data[col.field] }}
                            </div>
                            <div v-else @click="handleDetial(data)">
                                <div v-html="data[col.field].substr(0, 20) + '...'"></div>
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
                    <div class="border text-center p-2">Subject</div>
                    <div class="border text-center p-2">{{ requestDetail?.title }}</div>
                    <div class="border text-center p-2">Department</div>
                    <div class="border text-center p-2">{{ requestDetail?.department_name }}</div>
                    <div class="border text-center p-2">Start date</div>
                    <div class="border text-center p-2">{{ requestDetail?.start_date }}</div>
                    <div class="border text-center p-2">End date</div>
                    <div class="border text-center p-2">{{ requestDetail?.end_date }}</div>
                    <div class="border text-center p-2">Status</div>
                    <div class="border text-center p-2">
                        <div v-if="requestDetail?.status === 'pending'"
                            class="bg-yellow-400 w-full text-center p-2 text-yellow-50">
                            {{ requestDetail?.status }}
                        </div>
                        <div v-if="requestDetail?.status === 'approved'"
                            class="bg-green-400 w-full text-center p-2 text-green-50">
                            {{ requestDetail?.status }}
                        </div>
                        <div v-if="requestDetail?.status === 'rejected'"
                            class="bg-red-400 w-full text-center p-2 text-red-50">
                            {{ requestDetail?.status }}
                        </div>
                    </div>
                    <div class="border text-center p-2 col-span-2">Reason</div>
                    <div class="border flex-wrap p-2 col-span-2">
                        <div v-html="requestDetail?.reason"></div>
                    </div>
                </div>
            </Dialog>
        </div>
    </MainLayout>
    <Toast position="bottom-right" group="br" />
</template>
