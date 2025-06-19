<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <!-- Kita tidak lagi menggunakan GuestLayout, kita buat layout custom di sini -->
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <Head title="Log in" />
        
        <div class="w-full max-w-4xl mx-auto bg-white shadow-2xl rounded-2xl flex overflow-hidden">
            
            <!-- === KOLOM KIRI: FORM LOGIN === -->
            <div class="w-full md:w-1/2 p-8 sm:p-12">
                <div class="w-full">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang!</h1>
                    <p class="text-gray-600 mb-8">Silakan masuk untuk mengelola rapor.</p>

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="block mt-4 flex justify-between items-center">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            
                             <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Lupa password?
                            </Link>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <PrimaryButton class="w-full text-center justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Masuk
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>

            <!-- === KOLOM KANAN: GAMBAR === -->
            <div class="hidden md:block md:w-1/2 bg-cover bg-center" style="background-image: url('/images/logo.png');">
                 <!-- Div ini hanya untuk menampilkan gambar background -->
            </div>
        </div>
    </div>
</template>