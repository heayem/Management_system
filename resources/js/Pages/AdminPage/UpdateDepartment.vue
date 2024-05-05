<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputText from 'primevue/inputtext';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';
import Toast from 'primevue/toast';



export default {
    props: {
        department: [Array,Object],
        flash: { type: Object, default: null },

    },
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        InputText,
        Link,
        Head,
        AuthenticatedLayout,
        Button,
        Toast
    },
    data() {
        return {
            form: useForm({
                id: this.department.id,
                name: this.department.name,
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
            this.form.put(route('department'), {
                onFinish: () => this.form.reset(),
            });
        },
    }
}
</script>

<template>

    <AuthenticatedLayout>
        <div class="w-full h-auto flex flex-col justify-start items-center sm:pt-0 bg-gray-100">

            <Head title="Register" />
            <div class="mt-3">
                <Link  :href="route('department')">
                <Button label="Back" severity="danger" raised />
                </Link>
            <form class="md:w-[400px] mt-3 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
                @submit.prevent="submit">

                <div class="flex">

                    <div>
                        <InputLabel for="name" value="Name" />

                        <InputText id="name" class="w-full" v-model="form.name" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                </div>
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Update
                    </PrimaryButton>
                </div>

            </form>
        </div>

        </div>
    </AuthenticatedLayout>
    <Toast position="bottom-right" group="br" />
</template>
