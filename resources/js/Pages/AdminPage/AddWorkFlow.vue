<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from 'primevue/dropdown';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';


export default {
    props: {
        users: [Object, Array],
        departments: [Object, Array],
        flash: { type: Object, default: null },
    },
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        InputText,
        Calendar,
        ApplicationLogo,
        Dropdown,
        Link,
        Head,
        AuthenticatedLayout,
        Button
    },
    data() {
        return {
            approver: [{ 'name': 'Can approve mission', 'value': 1 }, { 'name': 'Can approve leave', 'value': 2 }, { 'name': 'Can approve mission and leave', 'value': 3 }],
            form: useForm({
                user_Id: null,
                department_Id: {'value':null},
                approver: 0,
            }),
        }
    },
    mounted() {
        if (this.flash.success) {
            this.$toast.add({ severity: 'success', summary: 'Success Message', detail: this.flash.success, group: 'br', life: 3000 });
        } else if (this.flash.error) {
            this.$toast.add({ severity: 'error', summary: 'Error Message', detail: this.flash.error, group: 'br', life: 3000 });
        }
    },
    methods: {
        submit() {
            this.form.department_Id.value = this.form.user_Id.department_Id
            this.form.post(route('employee-department-ad'), {
                onFinish: () => this.form.reset(''),
            });
        },
    }
}
</script>

<template>

    <AuthenticatedLayout>
        <div class="w-full h-auto flex flex-col justify-center items-center sm:pt-0 bg-gray-100">

            <Head title="Register" />

            <div class="w-full mt-3 md:w-[800px] felx flex-row justify-start items-start">
                <Link :href="route('employee-by-department')">
                <Button icon="pi pi-chevron-circle-left" label="Back" severity="danger" raised />
                </Link>
            </div>
            <form class="w-full md:w-[800px] mt-3 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
                @submit.prevent="submit">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="emplopyee" value="Emplopyee" />

                        <div class="card flex justify-content-center">
                            <Dropdown v-model="form.user_Id" :options="users" placeholder="Please select a user"
                                optionLabel="name" class="w-full">
                                <template #value="slotProps">
                                    <div v-if="slotProps.value" class="flex align-items-center space-x-3">
                                        <div>{{ slotProps.value.value }}</div>
                                        <div>{{ slotProps.value.name }}</div>
                                        <div>is </div>
                                        <div>{{ slotProps.value.role }}</div>
                                        <div>in </div>
                                        <div>{{ slotProps.value.department_name }}</div>
                                    </div>
                                    <span v-else>
                                        {{ slotProps.placeholder }}
                                    </span>
                                </template>
                                <template #option="slotProps">
                                    <div class="flex align-items-center space-x-3 justify-center">
                                        <div>{{ slotProps.option.value }}</div>
                                        <div>{{ slotProps.option.name }}</div>
                                        <div>is </div>
                                        <div>{{ slotProps.option.role }}</div>
                                        <div>in </div>
                                        <div>{{ slotProps.option.department_name }}</div>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <InputError class="mt-2" :message="form.errors.user_Id" />
                    </div>

                    <div>
                        <InputLabel for="approver" value="Can approver ?" />

                        <div class="card flex justify-content-center">
                            <Dropdown v-model="form.approver" :options="approver" optionLabel="name"
                                placeholder="Please select" :placeholder="form.approver" class="w-full" />
                        </div>

                        <InputError class="mt-2" :message="form.errors.department_Id" />
                    </div>

                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Add to department
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
    <Toast position="bottom-right" group="br" />
</template>
