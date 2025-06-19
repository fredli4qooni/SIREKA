<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    students: Array,
});

// Gunakan useForm untuk mengelola semua data kehadiran
const form = useForm({
    attendances: props.students.map(student => ({
        id: student.id,
        name: student.name,
        nis: student.nis,
        attendance_sick: student.attendance_sick || 0,
        attendance_permission: student.attendance_permission || 0,
        attendance_alpha: student.attendance_alpha || 0,
    })),
});

const successMessage = ref('');
const errorMessage = ref('');
const isLoading = ref(false);

// Computed untuk menghitung total kehadiran per siswa
const totalAbsences = computed(() => {
    return form.attendances.map(student => ({
        ...student,
        total: parseInt(student.attendance_sick || 0) + 
               parseInt(student.attendance_permission || 0) + 
               parseInt(student.attendance_alpha || 0)
    }));
});

// Computed untuk statistik keseluruhan
const overallStats = computed(() => {
    const totals = form.attendances.reduce((acc, student) => {
        acc.sick += parseInt(student.attendance_sick || 0);
        acc.permission += parseInt(student.attendance_permission || 0);
        acc.alpha += parseInt(student.attendance_alpha || 0);
        return acc;
    }, { sick: 0, permission: 0, alpha: 0 });
    
    return {
        ...totals,
        total: totals.sick + totals.permission + totals.alpha
    };
});

// Validasi input
const validateInput = (value, studentIndex, field) => {
    const numValue = parseInt(value);
    if (isNaN(numValue) || numValue < 0) {
        form.attendances[studentIndex][field] = 0;
    } else if (numValue > 999) {
        form.attendances[studentIndex][field] = 999;
    }
};

// Watch untuk validasi real-time
watch(() => form.attendances, (newAttendances) => {
    newAttendances.forEach((student, index) => {
        ['attendance_sick', 'attendance_permission', 'attendance_alpha'].forEach(field => {
            validateInput(student[field], index, field);
        });
    });
}, { deep: true });

const clearMessages = () => {
    successMessage.value = '';
    errorMessage.value = '';
};

const saveAttendances = () => {
    clearMessages();
    isLoading.value = true;
    
    // Validasi data sebelum mengirim
    const hasInvalidData = form.attendances.some(att => 
        isNaN(parseInt(att.attendance_sick)) || parseInt(att.attendance_sick) < 0 ||
        isNaN(parseInt(att.attendance_permission)) || parseInt(att.attendance_permission) < 0 ||
        isNaN(parseInt(att.attendance_alpha)) || parseInt(att.attendance_alpha) < 0
    );
    
    if (hasInvalidData) {
        errorMessage.value = 'Pastikan semua data berisi angka yang valid (minimal 0)';
        isLoading.value = false;
        return;
    }
    
    const dataToSubmit = {
        attendances: form.attendances.map(att => ({
            id: att.id,
            attendance_sick: parseInt(att.attendance_sick) || 0,
            attendance_permission: parseInt(att.attendance_permission) || 0,
            attendance_alpha: parseInt(att.attendance_alpha) || 0,
        }))
    };
    
    axios.post(route('api.attendances.update_all'), dataToSubmit)
        .then(res => {
            successMessage.value = res.data.message || 'Data kehadiran berhasil disimpan!';
            setTimeout(() => successMessage.value = '', 5000);
        })
        .catch(err => {
            console.error('Error saving attendances:', err);
            if (err.response?.status === 422) {
                errorMessage.value = 'Data tidak valid. Pastikan semua input berisi angka yang benar.';
            } else if (err.response?.status === 500) {
                errorMessage.value = 'Terjadi kesalahan server. Silakan coba lagi.';
            } else {
                errorMessage.value = 'Terjadi kesalahan saat menyimpan data. Periksa koneksi internet Anda.';
            }
        })
        .finally(() => {
            isLoading.value = false;
        });
};

// Fungsi untuk reset semua data
const resetAllData = () => {
    if (confirm('Apakah Anda yakin ingin mereset semua data kehadiran menjadi 0?')) {
        form.attendances.forEach(student => {
            student.attendance_sick = 0;
            student.attendance_permission = 0;
            student.attendance_alpha = 0;
        });
        clearMessages();
    }
};
</script>

<template>
    <Head title="Input Kehadiran" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Input Rekap Kehadiran Siswa
                </h2>
                <div class="text-sm text-gray-600">
                    Total Siswa: {{ form.attendances.length }}
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Statistik Keseluruhan -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="text-blue-800 text-sm font-medium">Total Sakit</div>
                        <div class="text-2xl font-bold text-blue-900">{{ overallStats.sick }}</div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="text-yellow-800 text-sm font-medium">Total Izin</div>
                        <div class="text-2xl font-bold text-yellow-900">{{ overallStats.permission }}</div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="text-red-800 text-sm font-medium">Total Alpha</div>
                        <div class="text-2xl font-bold text-red-900">{{ overallStats.alpha }}</div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="text-gray-800 text-sm font-medium">Total Ketidakhadiran</div>
                        <div class="text-2xl font-bold text-gray-900">{{ overallStats.total }}</div>
                    </div>
                </div>

                <!-- Notifikasi -->
                <div v-if="successMessage" class="mb-4 p-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg flex items-center" role="alert">
                    <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    {{ successMessage }}
                </div>

                <div v-if="errorMessage" class="mb-4 p-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg flex items-center" role="alert">
                    <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    {{ errorMessage }}
                </div>

                <!-- Form Input Kehadiran -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="saveAttendances">
                        <div class="p-6">
                            <!-- Header Actions -->
                            <div class="mb-4 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Data Kehadiran Siswa</h3>
                                <button 
                                    type="button" 
                                    @click="resetAllData"
                                    class="px-3 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition-colors"
                                >
                                    Reset Semua
                                </button>
                            </div>

                            <!-- Tabel Desktop -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit (S)</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin (I)</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha (A)</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(student, index) in totalAbsences" :key="student.id" 
                                            class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ student.nis }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input 
                                                    type="number" 
                                                    v-model="form.attendances[index].attendance_sick" 
                                                    class="w-16 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                    min="0" 
                                                    max="999"
                                                    placeholder="0"
                                                >
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input 
                                                    type="number" 
                                                    v-model="form.attendances[index].attendance_permission" 
                                                    class="w-16 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                    min="0" 
                                                    max="999"
                                                    placeholder="0"
                                                >
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input 
                                                    type="number" 
                                                    v-model="form.attendances[index].attendance_alpha" 
                                                    class="w-16 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                    min="0" 
                                                    max="999"
                                                    placeholder="0"
                                                >
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      :class="student.total > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                                    {{ student.total }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Cards Mobile -->
                            <div class="md:hidden space-y-4">
                                <div v-for="(student, index) in totalAbsences" :key="student.id" 
                                     class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ student.name }}</h4>
                                            <p class="text-sm text-gray-500">NIS: {{ student.nis }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                              :class="student.total > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                            Total: {{ student.total }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Sakit</label>
                                            <input 
                                                type="number" 
                                                v-model="form.attendances[index].attendance_sick" 
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                min="0" 
                                                max="999"
                                                placeholder="0"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Izin</label>
                                            <input 
                                                type="number" 
                                                v-model="form.attendances[index].attendance_permission" 
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                min="0" 
                                                max="999"
                                                placeholder="0"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Alpha</label>
                                            <input 
                                                type="number" 
                                                v-model="form.attendances[index].attendance_alpha" 
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                                min="0" 
                                                max="999"
                                                placeholder="0"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer Actions -->
                        <div class="flex items-center justify-between p-6 bg-gray-50 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                {{ form.attendances.length }} siswa • Total ketidakhadiran: {{ overallStats.total }}
                            </div>
                            <PrimaryButton 
                                :class="{ 'opacity-50 cursor-not-allowed': isLoading }" 
                                :disabled="isLoading"
                                class="flex items-center"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isLoading ? 'Menyimpan...' : 'Simpan Data Kehadiran' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Animasi untuk transisi */
.transition-colors {
    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, color 0.15s ease-in-out;
}

/* Custom styling untuk input number */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Hover effects */
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

.hover\:bg-red-100:hover {
    background-color: #fee2e2;
}
</style>