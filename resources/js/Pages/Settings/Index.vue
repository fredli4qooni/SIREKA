<!-- resources/js/Pages/Settings/Index.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const form = reactive({
    // Data Sekolah
    school_name: '',
    npsn: '',
    nss: '',
    address: '',
    postal_code: '',
    village: '',
    sub_district: '',
    district: '',
    province: '',
    email: '',
    // Data Kepsek
    headmaster_name: '',
    headmaster_nip: '',
    // Data Rapor
    class_level: '4',
    phase: 'B',
    semester: '1 (Satu)',
    academic_year: '',
    report_place: '',
    report_date: '',
    // Data Guru (dengan prefix beda agar tidak konflik)
    name: '',
    nip: '',
    user_email: '',
    password: '',
    password_confirmation: ''
});

const isLoading = ref(true);
const successMessage = ref('');

// Ambil data awal saat halaman dimuat
onMounted(() => {
    axios.get(route('api.settings.get')).then(res => {
        const { school, user } = res.data.data;
        // Map data sekolah ke form
        Object.keys(school).forEach(key => {
            if (form.hasOwnProperty(key)) {
                form[key] = school[key];
            }
        });
        // Map data guru
        form.name = user.name;
        form.nip = user.nip;
        form.user_email = user.email;
    }).finally(() => isLoading.value = false);
});

const updateSettings = () => {
    isLoading.value = true;
    successMessage.value = '';
    axios.post(route('api.settings.update'), form)
        .then(res => {
            successMessage.value = res.data.message;
            // Kosongkan field password setelah berhasil
            form.password = '';
            form.password_confirmation = '';
            // Sembunyikan pesan setelah 3 detik
            setTimeout(() => successMessage.value = '', 3000);
        })
        .catch(error => {
            // Sederhanakan penanganan error untuk saat ini
            alert('Terjadi kesalahan. Pastikan semua field terisi dengan benar.');
            console.error(error.response.data);
        })
        .finally(() => isLoading.value = false);
};
</script>

<template>

    <Head title="Pengaturan Rapor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Rapor</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Notifikasi Sukses -->
                <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ successMessage }}
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="updateSettings" class="space-y-8">
                        <!-- === INFORMASI SEKOLAH === -->
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Informasi Sekolah</h2>
                                <p class="mt-1 text-sm text-gray-600">Informasi ini akan ditampilkan di kop rapor.</p>
                            </header>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel value="Nama Sekolah" />
                                    <TextInput v-model="form.school_name" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="NPSN" />
                                    <TextInput v-model="form.npsn" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="NSS" />
                                    <TextInput v-model="form.nss" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="E-mail Sekolah" />
                                    <TextInput v-model="form.email" type="email" class="mt-1 block w-full" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel value="Alamat Sekolah" />
                                    <textarea v-model="form.address"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                                <div>
                                    <InputLabel value="Desa / Kelurahan" />
                                    <TextInput v-model="form.village" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Kecamatan" />
                                    <TextInput v-model="form.sub_district" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Kabupaten / Kota" />
                                    <TextInput v-model="form.district" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Provinsi" />
                                    <TextInput v-model="form.province" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Kode Pos" />
                                    <TextInput v-model="form.postal_code" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </section>

                        <!-- === INFORMASI KEPALA SEKOLAH & GURU === -->
                        <section class="pt-8 border-t">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Informasi Pimpinan & Guru</h2>
                                <p class="mt-1 text-sm text-gray-600">Informasi ini digunakan untuk tanda tangan dan
                                    identitas
                                    di rapor.</p>
                            </header>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel value="Nama Kepala Sekolah" />
                                    <TextInput v-model="form.headmaster_name" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="NIP Kepala Sekolah" />
                                    <TextInput v-model="form.headmaster_nip" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Nama Guru Kelas" />
                                    <TextInput v-model="form.name" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="NIP Guru Kelas" />
                                    <TextInput v-model="form.nip" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </section>

                        <!-- === PENGATURAN RAPOR === -->
                        <section class="pt-8 border-t">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Pengaturan Rapor</h2>
                                <p class="mt-1 text-sm text-gray-600">Pengaturan khusus untuk format dan periode rapor.
                                </p>
                            </header>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <InputLabel value="Kelas" />
                                    <TextInput v-model="form.class_level" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Fase" />
                                    <TextInput v-model="form.phase" class="mt-1 block w-full" />
                                </div>
                                <!-- Settings/Index.vue -->
                                <div>
                                    <InputLabel value="Semester" />
                                    <select v-model="form.semester"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="1">1 (Satu)</option> <!-- value adalah angka -->
                                        <option value="2">2 (Dua)</option> <!-- value adalah angka -->
                                    </select>
                                </div>
                                <div>
                                    <InputLabel value="Tahun Pelajaran" />
                                    <TextInput v-model="form.academic_year" placeholder="2023/2024"
                                        class="mt-1 block w-full" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel value="Tempat Diterbitkan Rapor" />
                                    <TextInput v-model="form.report_place" class="mt-1 block w-full" />
                                </div>
                                <div class="md:col-span-2">
                                    <InputLabel value="Tanggal Diterbitkan Rapor" />
                                    <TextInput v-model="form.report_date" type="date" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </section>

                        <!-- === PENGATURAN AKUN === -->
                        <section class="pt-8 border-t">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Pengaturan Akun Login</h2>
                                <p class="mt-1 text-sm text-gray-600">Informasi ini digunakan untuk login aplikasi.</p>
                            </header>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel value="Email Login" />
                                    <TextInput v-model="form.user_email" type="email" class="mt-1 block w-full" />
                                </div>
                                <div></div>
                                <div>
                                    <InputLabel value="Password Baru (Opsional)" />
                                    <TextInput v-model="form.password" type="password" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel value="Konfirmasi Password Baru" />
                                    <TextInput v-model="form.password_confirmation" type="password"
                                        class="mt-1 block w-full" />
                                </div>
                            </div>
                        </section>

                        <div class="flex items-center justify-end pt-8 border-t">
                            <PrimaryButton :disabled="isLoading">
                                {{ isLoading ? 'Menyimpan...' : 'Simpan Semua Pengaturan' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>