<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    students: Array,
    subjects: Array,
    settings: Object,
});

const selectedStudentId = ref(null);
const selectedSubjectId = ref(null);
const isLoading = ref(false);
const successMessage = ref('');

// Gunakan useForm untuk mengelola deskripsi
const form = useForm({
    // Sertakan semua field di sini
    student_id: null,
    subject_id: null,
    academic_year: props.settings.academic_year,
    semester: props.settings.semester,
    description: '',
});

const reportScore = ref(null); // Untuk menampilkan nilai akhir

// Fungsi untuk mengambil data deskripsi
const fetchData = () => {
    if (!selectedStudentId.value || !selectedSubjectId.value) {
        form.description = '';
        reportScore.value = null;
        return;
    }

    isLoading.value = true;
    successMessage.value = '';
    form.reset();

    axios.get(route('api.competence.get'), {
        params: {
            student_id: selectedStudentId.value,
            subject_id: selectedSubjectId.value,
            academic_year: props.settings.academic_year,
            semester: props.settings.semester,
        }
    }).then(res => {
        form.description = res.data.description;
        reportScore.value = res.data.report_score;
    }).catch(err => {
        console.error(err);
    }).finally(() => {
        isLoading.value = false;
    });
};

// Tonton perubahan pada dropdown
watch([selectedStudentId, selectedSubjectId], fetchData);

// Fungsi untuk menyimpan deskripsi
const saveDescription = () => {
    if (!selectedStudentId.value || !selectedSubjectId.value) {
        alert('Pilih siswa dan mata pelajaran terlebih dahulu.');
        return;
    }

    // Isi ID yang relevan ke dalam form sebelum mengirim
    form.student_id = selectedStudentId.value;
    form.subject_id = selectedSubjectId.value;

    // Kirim seluruh isi 'form'
    form.post(route('api.competence.save'), {
        preserveScroll: true,
        onSuccess: () => {
            successMessage.value = 'Deskripsi berhasil disimpan!';
            setTimeout(() => successMessage.value = '', 3000);
        },
        onError: (errors) => {
            console.error("Gagal menyimpan:", errors);
            alert('Gagal menyimpan deskripsi. Pastikan deskripsi tidak kosong.');
        }
    });

    };

</script>

<template>
    <Head title="Input Capaian Kompetensi" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Capaian Kompetensi</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Notifikasi Sukses -->
                <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ successMessage }}</div>

                <!-- Pilihan Siswa & Mapel -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Siswa</label>
                            <select v-model="selectedStudentId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option :value="null" disabled>-- Pilih Siswa --</option>
                                <option v-for="student in props.students" :key="student.id" :value="student.id">{{ student.name }}</option>
                            </select>
                        </div>
                         <div>
                            <label class="block font-medium text-sm text-gray-700">Mata Pelajaran</label>
                            <select v-model="selectedSubjectId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                 <option :value="null" disabled>-- Pilih Mata Pelajaran --</option>
                                <option v-for="subject in props.subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Form Input Deskripsi -->
                <div v-if="selectedStudentId && selectedSubjectId" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div v-if="isLoading" class="text-center py-10">Memuat data...</div>
                    <form v-else @submit.prevent="saveDescription">
                        <div class="flex justify-between items-center mb-4">
                           <h3 class="text-lg font-medium text-gray-900">Deskripsi Capaian Kompetensi</h3>
                           <div v-if="reportScore !== null" class="font-bold text-lg">
                               Nilai Akhir: <span class="text-indigo-600">{{ parseFloat(reportScore).toFixed(2) }}</span>
                           </div>
                        </div>
                        
                        <textarea
                            v-model="form.description"
                            rows="6"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Tuliskan narasi capaian kompetensi siswa di sini. Contoh: Ananda menunjukkan penguasaan yang sangat baik dalam materi Bilangan Cacah, namun perlu meningkatkan pemahaman pada materi Pecahan."
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />

                        <div class="mt-6 flex justify-end">
                            <PrimaryButton :disabled="form.processing">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Deskripsi' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>