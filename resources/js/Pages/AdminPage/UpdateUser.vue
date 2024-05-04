<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SinlgeImage from '@/Components/File/SinlgeImage.vue';
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from 'primevue/dropdown';
import RadioButton from 'primevue/radiobutton';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';



export default {
    props: {
        users: Array,
    },
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        InputText,
        Calendar,
        SinlgeImage,
        ApplicationLogo,
        RadioButton,
        Dropdown,
        Link,
        Head,
        AuthenticatedLayout,
        Button
    },
    data() {
        return {
            form: useForm({
                id: this.users[0].id,
                fname: this.users[0].fname,
                lname: this.users[0].lname,
                email: this.users[0].email,
                phone: this.users[0].phone,
                dob: this.users[0].dob,
                role: this.users[0].role,
                place_of_birth: this.users[0].place_of_birth,
                gender: this.users[0].gender,
                profile: this.users[0].profile,
                current_address: this.users[0].current_address,
                password: '',
                password_confirmation: '',
            }),
            role: [
                { name: 'Administrator', value: 'administrator' },
                { name: 'Department administrator', value: 'department_administrator' },
                { name: 'HR manager', value: 'hr_manager' },
                { name: 'Team Leader', value: 'team_leader' },
                { name: 'CEO', value: 'cfo' },
                { name: 'Staff', value: 'staff' },
                { name: 'User', value: 'user' },
            ]
        }
    },
    methods: {
        handleImageUploaded(event) {
            const file = event
            this.form.profile = file
        },
        submit() {
            this.form.post(route('employee-edit'), {
                onFinish: () => this.form.reset(
                    'password',
                    'password_confirmation'),
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
                <Link :href="route('employee')">
                <Button label="Back" severity="danger" raised />
                </Link>
            </div>
            <form class="md:w-[800px] mt-3 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
                @submit.prevent="submit">

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                    <div>
                        <InputLabel for="fname" value="First name" />

                        <InputText id="fname" class="w-full" v-model="form.fname"  />

                        <InputError class="mt-2" :message="form.errors.fname" />
                    </div>

                    <div>
                        <InputLabel for="lname" value="Last name" />

                        <InputText id="lname" class="w-full" v-model="form.lname"  />

                        <InputError class="mt-2" :message="form.errors.lname" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />

                        <InputText id="email" class="w-full" type="email" v-model="form.email"  />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="phone" value="Phone" />

                        <InputText id="phone" type="tel" pattern=".{5,}"
                            title="Please enter at least 5 characters for the address" class="w-full"
                            v-model="form.phone"  />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>


                    <div>
                        <InputLabel for="place_of_birth" value="Place of birth" />

                        <InputText id="place_of_birth" class="w-full" v-model="form.place_of_birth"  />

                        <InputError class="mt-2" :message="form.errors.place_of_birth" />
                    </div>

                    <div>
                        <InputLabel for="current_address" value="Current address" />

                        <InputText id="current_address" class="w-full" v-model="form.current_address"  />

                        <InputError class="mt-2" :message="form.errors.place_of_birth" />
                    </div>

                    <div>
                        <InputLabel for="dob" value="Date of birth" />


                        <Calendar id="dob" class="w-full" dateFormat="dd/mm/yy" :manualInput="false" showIcon
                            v-model="form.dob"  />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>


                    <div>
                        <InputLabel for="password" value="Password" />

                        <Password id="password" class="w-full" v-model="form.password" toggleMask  />

                        <InputText id="password" class="w-full" type="password" v-model="form.password"  />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" value="Confirm Password" />

                        <InputText id="password_confirmation" class="w-full" type="password"
                            v-model="form.password_confirmation"  />

                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="hidden md:block w-full">
                        <InputLabel for="profile" value="Profile" />

                        <SinlgeImage v-if="users" :is-image="'/images/' + form.profile"
                            @image-uploaded="handleImageUploaded" />
                        <SinlgeImage v-else @image-uploaded="handleImageUploaded" />

                        <InputError class="mt-2" :message="form.errors.profile" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Role" />

                        <div class="card flex justify-content-center">
                            <Dropdown v-model="form.role" :options="role" optionLabel="name" :placeholder="form.role"
                                class="w-full" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <div class="block md:hidden w-full">
                        <InputLabel for="profile" value="Profile" />

                        <SinlgeImage v-if="users" :is-image="'/images/' + form.profile"
                            @image-uploaded="handleImageUploaded" />
                        <SinlgeImage v-else @image-uploaded="handleImageUploaded" />


                        <InputError class="mt-2" :message="form.errors.profile" />
                    </div>

                    <div>
                        <InputLabel for="gender" value="Gender" />

                        <div class="flex flex-wrap gap-3">
                            <div class="flex align-items-center">
                                <RadioButton v-model="form.gender" inputId="male"  name="male" value="0" />
                                <label for="male" class="ml-2">Male</label>
                            </div>
                            <div class="flex align-items-center">
                                <RadioButton v-model="form.gender" inputId="female"  name="female" value="1" />
                                <label for="ingredient2" class="ml-2">Female</label>
                            </div>
                        </div>

                        <InputError class="mt-2" :message="form.errors.gender" />
                    </div>

                </div>



                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Update
                    </PrimaryButton>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>
