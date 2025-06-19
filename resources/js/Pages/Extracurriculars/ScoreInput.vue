<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    students: Array,
    extracurriculars: Array,
    settings: Object,
});

const selectedStudentId = ref(null);
const studentEskulData = ref([]); // Untuk menampung daftar nilai ekskul siswa
const isLoading = ref(false);
const successMessage = ref('');

// Predikat options
const predicates = [
    'Mulai Berkembang',
    'Cukup Berkembang',
    'Berkembang',
    'Sangat Berkembang'
];

// Form untuk menambah nilai baru
const form = useForm({
    student_id: null,
    extracurricular_id: null,
    academic_year: props.settings.academic_year,
    semester: props.settings.semester,
    predicate: 'Berkembang',
    description: '',
});

// Fungsi untuk mengambil data nilai ekskul siswa dari API
const fetchStudentEskul = async () => {
    if (!selectedStudentId.value) {
        studentEskulData.value = [];
        return;
    }
    isLoading.value = true;
    try {
        const response = await axios.get(route('api.students.extracurriculars', selectedStudentId.value), {
            params: {
                academic_year: props.settings.academic_year,
                semester: props.settings.semester
            }
        });
        studentEskulData.value = response.data;
    } catch (error) {
        console.error("Gagal mengambil data ekskul siswa:", error);
        alert("Gagal mengambil data ekskul siswa.");
    } finally {
        isLoading.value = false;
    }
};

// Tonton perubahan pada dropdown siswa
watch(selectedStudentId, fetchStudentEskul);

// Fungsi untuk submit form tambah nilai
const submitForm = () => {
    if (!form.extracurricular_id) {
        alert('Silakan pilih ekstrakurikuler terlebih dahulu.');
        return;
    }
    form.student_id = selectedStudentId.value;
    form.post(route('api.student_extracurriculars.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('extracurricular_id', 'description'); // Reset form setelah sukses
            fetchStudentEskul(); // Ambil ulang daftar nilai
            successMessage.value = 'Nilai ekstrakurikuler berhasil disimpan.';
            setTimeout(() => successMessage.value = '', 3000);
        },
        onError: (errors) => {
            if (errors.extracurricular_id) {
                 alert('Gagal: Siswa ini sudah memiliki nilai untuk ekstrakurikuler tersebut di semester ini.');
            }
            console.error(errors);
        }
    });
};

// Fungsi untuk menghapus nilai ekskul
const deleteScore = (scoreId) => {
    if (confirm('Anda yakin ingin menghapus penilaian ekstrakurikuler ini?')) {
        const deleteForm = useForm({});
        deleteForm.delete(route('api.student_extracurriculars.destroy', scoreId), {
            preserveScroll: true,
            onSuccess: () => {
                fetchStudentEskul(); // Refresh daftar
                successMessage.value = 'Penilaian berhasil dihapus.';
                setTimeout(() => successMessage.value = '', 3000);
            }
        });
    }
};
</script>

<template>
    <Head title="Input Nilai Ekstrakurikuler" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Nilai Ekstrakurikuler</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Notifikasi Sukses -->
                <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ successMessage }}</div>

                <!-- Pilih Siswa -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <label class="block font-medium text-sm text-gray-700">Pilih Siswa</label>
                    <select v-model="selectedStudentId" class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm">
                        <option :value="null" disabled>-- Pilih seorang siswa --</option>
                        <option v-for="student in props.students" :key="student.id" :value="student.id">{{ student.name }}</option>
                    </select>
                </div>

                <!-- Area Input (tampil setelah siswa dipilih) -->
                <div v-if="selectedStudentId">
                    <!-- Daftar Nilai yang Sudah Ada -->
                    <div v-if="studentEskulData.length > 0" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Ekskul yang Diikuti</h3>
                        <div class="mt-4 space-y-4">
                            <div v-for="score in studentEskulData" :key="score.id" class="p-4 border rounded-md flex justify-between items-start">
                                <div>
                                    <p class="font-bold">{{ score.extracurricular.name }}</p>
                                    <p class="text-sm font-semibold text-indigo-600">{{ score.predicate }}</p>
                                    <p class="mt-2 text-sm text-gray-600">{{ score.description }}</p>
                                </div>
                                <DangerButton @click="deleteScore(score.id)">Hapus</DangerButton>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Nilai Baru -->
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                         <h3 class="text-lg font-medium text-gray-900">Tambah Penilaian Ekskul Baru</h3>
                         <form @submit.prevent="submitForm" class="mt-4 space-y-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Pilih Ekstrakurikuler</label>
                                <select v-model="form.extracurricular_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option :value="null" disabled>-- Pilih --</option>
                                    <option v-for="eskul in props.extracurriculars" :key="eskul.id" :value="eskul.id">{{ eskul.name }}</option>
                                </select>
                                <InputError :message="form.errors.extracurricular_id" />
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Predikat</label>
                                <select v-model="form.predicate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option v-for="pred in predicates" :key="pred" :value="pred">{{ pred }}</option>
                                </select>
                                 <InputError :message="form.errors.predicate" />
                            </div>
                            <div>
                                 <label class="block font-medium text-sm text-gray-700">Deskripsi (Opsional)</label>
                                 <textarea v-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                 <InputError :message="form.errors.description" />
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton :disabled="form.processing">
                                    {{ form.processing ? 'Menyimpan...' : 'Tambah & Simpan Penilaian' }}
                                </PrimaryButton>
                            </div>
                         </form>
                    </div>
                </div>
                 <div v-else class="text-center text-gray-500 py-6">
                    Pilih seorang siswa untuk mulai menginput nilai ekstrakurikuler.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>