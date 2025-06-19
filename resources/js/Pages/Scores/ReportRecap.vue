<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    subjects: Array,
    recapData: Array,
    settings: Object,
});

// State untuk melacak siswa mana yang sedang 'diperluas'
// Hanya bisa satu siswa yang diperluas pada satu waktu
const expandedStudentId = ref(null);

// Fungsi untuk membuka/menutup detail siswa
const toggleDetails = (studentId) => {
    if (expandedStudentId.value === studentId) {
        // Jika siswa yang sama diklik lagi, tutup detailnya
        expandedStudentId.value = null;
    } else {
        // Jika siswa lain diklik, buka detailnya
        expandedStudentId.value = studentId;
    }
};

// Fungsi untuk memformat nilai
const formatScore = (score) => {
    if (score === null || score === undefined) return '-';
    return score % 1 === 0 ? score : parseFloat(score).toFixed(2);
};
</script>

<template>
    <Head title="Rekap Nilai Rapor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rekap Nilai Rapor Akhir
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Tahun Ajaran: {{ settings.academic_year }} - Semester: {{ settings.semester === 1 ? '1 (Ganjil)' : '2 (Genap)' }}
            </p>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- Header Tabel Ringkasan -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Detail</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta Didik</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Nilai</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Capaian Kelas</th>
                                </tr>
                            </thead>
                            <!-- Kita gunakan <template> untuk mengelompokkan baris utama dan baris detail -->
                            <template v-for="student in props.recapData" :key="student.id">
                                <!-- Baris Utama (Ringkasan) -->
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button @click="toggleDetails(student.id)" class="p-1 rounded-full hover:bg-gray-200 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 transition-transform duration-300" :class="{'transform rotate-90': expandedStudentId === student.id}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                                            <div class="text-sm text-gray-500">NIS: {{ student.nis }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-bold text-gray-900">{{ formatScore(student.total_score) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                  :class="{
                                                      'bg-yellow-100 text-yellow-800': student.rank === 1,
                                                      'bg-green-100 text-green-800': student.rank > 1 && student.rank <= 3,
                                                      'bg-gray-100 text-gray-800': student.rank > 3
                                                  }">
                                                Peringkat {{ student.rank }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- Baris Detail (Hanya Tampil Jika Diperluas) -->
                                <tbody v-if="expandedStudentId === student.id" class="bg-gray-50">
                                    <tr>
                                        <td :colspan="4" class="px-6 py-4">
                                            <h4 class="font-semibold text-gray-800 mb-2">Rincian Nilai:</h4>
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead>
                                                    <tr class="bg-gray-200">
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase w-3/5">Mata Pelajaran</th>
                                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-600 uppercase w-2/5">Nilai Akhir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="subject in props.subjects" :key="subject.id">
                                                        <td class="px-4 py-2 text-sm text-gray-700">{{ subject.name }}</td>
                                                        <td class="px-4 py-2 text-center text-sm font-medium text-gray-900">
                                                            {{ formatScore(student.scores[subject.id]) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </AuthenticatedLayout>
    </template>