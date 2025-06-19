<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    students: Array,
});

// Gunakan useForm untuk mengelola semua data kehadiran
const form = useForm({
    // Kita akan mengisi 'attendances' dengan data dari props
    attendances: props.students.map(student => ({
        id: student.id,
        name: student.name, // untuk tampilan saja
        nis: student.nis,    // untuk tampilan saja
        attendance_sick: student.attendance_sick,
        attendance_permission: student.attendance_permission,
        attendance_alpha: student.attendance_alpha,
    })),
});

const successMessage = ref('');

const saveAttendances = () => {
    // Kita hanya perlu mengirim data yang dibutuhkan oleh backend
    const dataToSubmit = {
        attendances: form.attendances.map(att => ({
            id: att.id,
            attendance_sick: att.attendance_sick,
            attendance_permission: att.attendance_permission,
            attendance_alpha: att.attendance_alpha,
        }))
    };
    
    // Kita gunakan axios di sini karena kita ingin menampilkan pesan sukses custom
    // tanpa redirect (yang akan dilakukan oleh form.post)
    axios.post(route('api.attendances.update_all'), dataToSubmit)
        .then(res => {
            successMessage.value = res.data.message;
            // Sembunyikan pesan setelah 3 detik
            setTimeout(() => successMessage.value = '', 3000);
        })
        .catch(err => {
            console.error(err);
            // Jika ada error validasi, tampilkan
            if(err.response.status === 422) {
                alert('Data tidak valid. Pastikan semua input berisi angka.');
            } else {
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
};
</script>

<template>
    <Head title="Input Kehadiran" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Rekap Kehadiran Siswa</h2>
        </template>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                 <!-- Notifikasi Sukses -->
                <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ successMessage }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="saveAttendances">
                        <div class="p-6">
                            <table class="min-w-full">
                                <thead class="border-b">
                                    <tr>
                                        <th class="py-2 px-4 text-left">No</th>
                                        <th class="py-2 px-4 text-left">Nama Siswa</th>
                                        <th class="py-2 px-4 text-center w-20">Sakit (S)</th>
                                        <th class="py-2 px-4 text-center w-20">Izin (I)</th>
                                        <th class="py-2 px-4 text-center w-20">Alpha (A)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(student, index) in form.attendances" :key="student.id" class="border-b">
                                        <td class="py-2 px-4">{{ index + 1 }}</td>
                                        <td class="py-2 px-4 font-medium">{{ student.name }}</td>
                                        <td class="py-2 px-4">
                                            <input type="number" v-model="student.attendance_sick" class="w-full text-center border-gray-300 rounded-md shadow-sm" min="0">
                                        </td>
                                        <td class="py-2 px-4">
                                            <input type="number" v-model="student.attendance_permission" class="w-full text-center border-gray-300 rounded-md shadow-sm" min="0">
                                        </td>
                                        <td class="py-2 px-4">
                                            <input type="number" v-model="student.attendance_alpha" class="w-full text-center border-gray-300 rounded-md shadow-sm" min="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-end p-6 bg-gray-50">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Simpan Data Kehadiran
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>