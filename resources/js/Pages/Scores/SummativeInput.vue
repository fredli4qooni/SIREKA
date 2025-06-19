<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, watch, computed } from 'vue';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';

// --- PROPS ---
// Menerima data awal dari controller saat halaman dimuat
const props = defineProps({
    students: Array,
    subjects: Array,
    settings: Object,
});


// --- STATE MANAGEMENT ---

// State untuk pilihan dropdown
const selectedStudentId = ref(null);
const selectedSubjectId = ref(null);

// State untuk UI (loading & pesan sukses)
const isLoading = ref(false);
const successMessage = ref('');

// State untuk menampung data nilai yang akan di-binding ke form
const scores = reactive({
    material_scores: [],
    final_test_score: null,
    final_nontest_score: null,
});


// --- COMPUTED PROPERTIES ---

// Menghitung nilai rapor (rata-rata) secara real-time setiap kali nilai di input berubah
const reportScore = computed(() => {
    const allScores = [];

    // Kumpulkan semua nilai dari materi
    scores.material_scores.forEach(s => {
        // Hanya masukkan nilai yang valid (bukan null, undefined, atau string kosong)
        if (s.test_score !== null && s.test_score !== '') allScores.push(parseFloat(s.test_score));
        if (s.nontest_score !== null && s.nontest_score !== '') allScores.push(parseFloat(s.nontest_score));
    });

    // Kumpulkan nilai dari akhir semester
    if (scores.final_test_score !== null && scores.final_test_score !== '') allScores.push(parseFloat(scores.final_test_score));
    if (scores.final_nontest_score !== null && scores.final_nontest_score !== '') allScores.push(parseFloat(scores.final_nontest_score));

    // Hitung rata-rata jika ada nilai, jika tidak tampilkan 'N/A'
    if (allScores.length === 0) return 'N/A';
    
    const sum = allScores.reduce((a, b) => a + b, 0);
    return (sum / allScores.length).toFixed(2);
});


// --- METHODS ---

// Fungsi untuk mengambil data nilai dari API
const fetchScores = () => {
    // Jangan lakukan apa-apa jika salah satu dropdown belum dipilih
    if (!selectedStudentId.value || !selectedSubjectId.value) return;

    isLoading.value = true;
    successMessage.value = '';

    axios.get(route('api.scores.get'), {
        params: {
            student_id: selectedStudentId.value,
            subject_id: selectedSubjectId.value,
            academic_year: props.settings.academic_year,
            semester: props.settings.semester, // Mengirim semester sebagai angka (1 atau 2)
        }
    }).then(res => {
        // Mengisi state 'scores' dengan data dari backend
        Object.assign(scores, res.data.data);
    }).catch(err => {
        console.error("Gagal mengambil data nilai:", err);
        alert('Gagal mengambil data nilai. Silakan coba lagi.');
    }).finally(() => {
        isLoading.value = false;
    });
};

// Fungsi untuk menyimpan semua nilai ke API
const saveScores = () => {
    isLoading.value = true;
    successMessage.value = '';

    axios.post(route('api.scores.save'), {
        student_id: selectedStudentId.value,
        subject_id: selectedSubjectId.value,
        academic_year: props.settings.academic_year,
        semester: props.settings.semester, // Mengirim semester sebagai angka (1 atau 2)
        scores: scores.material_scores,
        final_test_score: scores.final_test_score,
        final_nontest_score: scores.final_nontest_score,
    }).then(res => {
        successMessage.value = res.data.message;
        // Ambil ulang data untuk memastikan nilai rapor dari backend juga terupdate
        fetchScores();
    }).catch(err => {
        console.error("Gagal menyimpan data:", err.response.data);
        alert('Gagal menyimpan data. Periksa kembali nilai yang diinput (0-100).');
    }).finally(() => {
        isLoading.value = false;
    });
};

// --- WATCHERS ---
// Secara otomatis memanggil fetchScores setiap kali pilihan siswa atau mata pelajaran berubah
watch([selectedStudentId, selectedSubjectId], fetchScores);

</script>

<template>
    <Head title="Input Nilai Sumatif" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Nilai Asesmen Sumatif</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Notifikasi Sukses -->
                <div v-if="successMessage" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ successMessage }}
                </div>

                <!-- Pilihan Siswa & Mata Pelajaran -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="student" class="block font-medium text-sm text-gray-700">Siswa</label>
                            <select id="student" v-model="selectedStudentId" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option :value="null" disabled>-- Pilih Siswa --</option>
                                <option v-for="student in props.students" :key="student.id" :value="student.id">{{ student.name }}</option>
                            </select>
                        </div>
                         <div>
                            <label for="subject" class="block font-medium text-sm text-gray-700">Mata Pelajaran</label>
                            <select id="subject" v-model="selectedSubjectId" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                 <option :value="null" disabled>-- Pilih Mata Pelajaran --</option>
                                <option v-for="subject in props.subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Input Nilai -->
                <div v-if="selectedStudentId && selectedSubjectId" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div v-if="isLoading" class="text-center py-10">
                        <p>Memuat data...</p>
                    </div>
                    <div v-else-if="!isLoading && scores.material_scores.length === 0" class="text-center py-10 text-gray-500">
                        Belum ada materi sumatif yang ditambahkan untuk mata pelajaran ini. <br>
                        Silakan tambahkan materi di menu "Manajemen Mata Pelajaran".
                    </div>
                    <form v-else @submit.prevent="saveScores">
                        <table class="min-w-full">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600">Nama Materi / Asesmen</th>
                                    <th class="px-4 py-2 text-center w-28 font-medium text-gray-600">Tes</th>
                                    <th class="px-4 py-2 text-center w-28 font-medium text-gray-600">Non Tes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Looping Nilai per Materi -->
                                <tr v-for="score in scores.material_scores" :key="score.material_id" class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium">{{ score.material_name }}</td>
                                    <td class="px-4 py-2">
                                        <input type="number" v-model.number="score.test_score" class="w-full text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="0" max="100" />
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" v-model.number="score.nontest_score" class="w-full text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="0" max="100" />
                                    </td>
                                </tr>
                                <!-- Input Nilai Akhir Semester -->
                                <tr class="border-b bg-gray-100">
                                    <td class="px-4 py-2 font-bold">Asesmen Sumatif Akhir Semester</td>
                                    <td class="px-4 py-2">
                                        <input type="number" v-model.number="scores.final_test_score" class="w-full text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="0" max="100" />
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" v-model.number="scores.final_nontest_score" class="w-full text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="0" max="100" />
                                    </td>
                                </tr>
                                 <!-- Tampilan Nilai Rapor -->
                                <tr class="bg-indigo-100">
                                    <td class="px-4 py-3 font-extrabold text-indigo-800 uppercase">Nilai Rapor (Rata-rata)</td>
                                    <td colspan="2" class="px-4 py-3 text-center font-extrabold text-3xl text-indigo-800">
                                       {{ reportScore }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-6 flex justify-end">
                            <PrimaryButton :disabled="isLoading">
                                {{ isLoading ? 'Menyimpan...' : 'Simpan Nilai' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>