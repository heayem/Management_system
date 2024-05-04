<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Dropdown from 'primevue/dropdown';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';
import Editor from 'primevue/editor';


export default {
    props: {
        departments: [Object, Array],
    },
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        InputText,
        Calendar,
        Editor,
        Dropdown,
        Link,
        Head,
        AuthenticatedLayout,
        Button
    },
    data() {
        return {
            type: [
                { 'name': 'Mission', 'value': 'mission' },
                { 'name': 'Leave', 'value': 'leave' }
            ],
            form: useForm({
                title: '',
                start_date: '',
                end_date: '',
                department_Id: '',
                reason: '',
                type: '',
            }),

        }
    },
    methods: {
        submit() {
            this.form.department_Id = this.form.department_Id.value;
            this.form.type = this.form.type.value;
            console.log(this.form)
            this.form.post(route('request'), {
                onFinish: () => this.form.reset(),
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
                <Link :href="route('request-list')">
                <Button label="Back" severity="danger" raised />
                </Link>
            </div>
            <form class="md:w-[800px] mt-3 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
                @submit.prevent="submit">

                <div class="grid grid-cols-2   gap-4">

                    <div class="col-span-2">
                        <InputLabel for="title" value="Subject" />

                        <InputText id="title" class="w-full" v-model="form.title" required />

                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>


                    <div>
                        <InputLabel for="department_Id" value="Department" />

                        <div class="card flex justify-content-center">
                            <Dropdown v-model="form.department_Id" :options="departments" optionLabel="name"
                                placeholder="Select your department" class="w-full" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.department_Id" />
                    </div>

                    <div>
                        <InputLabel for="type" value="Leave/ Mission" />

                        <div class="card flex justify-content-center">
                            <Dropdown v-model="form.type" :options="type" optionLabel="name"
                                placeholder="Select Leave/ Mission" class="w-full" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.type" />
                    </div>


                    <div>
                        <InputLabel for="start_date" value="Start date" />


                        <Calendar id="start_date" class="w-full" dateFormat="yy/mm/dd" :manualInput="false" showIcon
                            v-model="form.start_date" required />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>
                    <div>
                        <InputLabel for="end_date" value="End date" />


                        <Calendar id="end_date" class="w-full" dateFormat="yy/mm/dd" :manualInput="false" showIcon
                            v-model="form.end_date" required />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>
                    <div class="md:col-span-2">
                        <Editor v-model="form.reason" header editorStyle="height: 220px" />
                    </div>
                </div>



                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Create
                    </PrimaryButton>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>
