<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, onMounted, computed } from 'vue';
import axios from 'axios';

// Import semua komponen yang dibutuhkan
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// State untuk data dan UI
const students = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showConfirmDeleteModal = ref(false);
const studentToDelete = ref(null);
const formErrors = ref({});
const isLoading = ref(true);
const isSubmitting = ref(false);
const searchQuery = ref('');

// State untuk form dengan semua field baru
const form = reactive({
    id: null,
    // Data Pribadi Siswa
    nis: '', 
    nisn: '', 
    name: '', 
    gender: 'L', 
    birth_place: '', 
    birth_date: '',
    religion: '', 
    previous_education: '', 
    address: '',
    // Data Orang Tua
    father_name: '', 
    mother_name: '', 
    father_job: '', 
    mother_job: '',
    parent_address_street: '', 
    parent_address_village: '', 
    parent_address_sub_district: '',
    parent_address_district: '', 
    parent_address_province: '',
    // Data Wali
    guardian_name: '', 
    guardian_job: '', 
    guardian_address: '', 
    guardian_phone: ''
});

// State untuk tab di dalam modal
const activeTab = ref('pribadi');

// Computed property untuk filter siswa berdasarkan pencarian
const filteredStudents = computed(() => {
    if (!searchQuery.value.trim()) {
        return students.value;
    }
    
    const query = searchQuery.value.toLowerCase();
    return students.value.filter(student => 
        student.name.toLowerCase().includes(query) ||
        student.nis.toLowerCase().includes(query) ||
        student.nisn.toLowerCase().includes(query)
    );
});

// Opsi agama untuk dropdown
const religionOptions = [
    'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'
];

// Fungsi untuk mereset form ke keadaan awal
const resetForm = () => {
    Object.keys(form).forEach(key => {
        if (key === 'gender') {
            form[key] = 'L'; // Nilai default
        } else if (key === 'id') {
            form[key] = null;
        } else {
            form[key] = '';
        }
    });
    formErrors.value = {};
    activeTab.value = 'pribadi';
};

// Fungsi untuk mengambil data siswa dari API
const getStudents = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/students');
        students.value = response.data.data;
    } catch (error) {
        console.error("Gagal mengambil data siswa:", error);
        // Bisa tambahkan notifikasi error di sini
    } finally {
        isLoading.value = false;
    }
};

// Panggil getStudents saat komponen dimuat
onMounted(() => {
    getStudents();
});

// Fungsi untuk membuka modal
const openCreateModal = () => {
    resetForm();
    showCreateModal.value = true;
};

const openEditModal = (student) => {
    resetForm();
    // Salin semua data siswa ke form
    Object.keys(form).forEach(key => {
        if (student.hasOwnProperty(key)) {
            form[key] = student[key] || '';
        }
    });
    form.id = student.id;
    showEditModal.value = true;
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    showConfirmDeleteModal.value = false;
    resetForm();
};

// Validasi form sebelum submit
const validateForm = () => {
    const errors = {};
    
    // Validasi field wajib
    if (!form.name.trim()) errors.name = ['Nama lengkap harus diisi'];
    if (!form.nis.trim()) errors.nis = ['NIS harus diisi'];
    
    // Validasi format NIS dan NISN (hanya angka)
    if (form.nis && !/^\d+$/.test(form.nis)) {
        errors.nis = ['NIS harus berupa angka'];
    }
    if (form.nisn && !/^\d+$/.test(form.nisn)) {
        errors.nisn = ['NISN harus berupa angka'];
    }
    
    // Validasi nomor telepon wali
    if (form.guardian_phone && !/^[\d\s\-\+\(\)]+$/.test(form.guardian_phone)) {
        errors.guardian_phone = ['Format nomor telepon tidak valid'];
    }
    
    formErrors.value = errors;
    return Object.keys(errors).length === 0;
};

// Fungsi untuk submit form (Create & Update)
const submitStudent = async () => {
    if (!validateForm()) {
        return;
    }
    
    try {
        isSubmitting.value = true;
        const url = form.id ? `/api/students/${form.id}` : '/api/students';
        const method = form.id ? 'put' : 'post';

        await axios[method](url, form);
        closeModal();
        await getStudents();
        
        // Reset form setelah berhasil
        resetForm();
    } catch (error) {
        if (error.response && error.response.status === 422) {
            formErrors.value = error.response.data.errors;
        } else {
            console.error("Gagal menyimpan data:", error);
            // Bisa tambahkan notifikasi error di sini
        }
    } finally {
        isSubmitting.value = false;
    }
};

// Fungsi untuk menghapus siswa
const openConfirmDeleteModal = (student) => {
    studentToDelete.value = student;
    showConfirmDeleteModal.value = true;
};

const deleteStudent = async () => {
    try {
        await axios.delete(`/api/students/${studentToDelete.value.id}`);
        closeModal();
        await getStudents();
        studentToDelete.value = null;
    } catch (error) {
        console.error("Gagal menghapus data:", error);
        closeModal();
    }
};

// Fungsi untuk navigasi tab
const switchTab = (tab) => {
    activeTab.value = tab;
};

// Fungsi untuk format tanggal
const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID');
};
</script>

<template>
    <Head title="Manajemen Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Data Induk Siswa
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Actions -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <PrimaryButton @click="openCreateModal" class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Siswa
                        </PrimaryButton>
                        <span class="text-sm text-gray-600">
                            Total: {{ students.length }} siswa
                        </span>
                    </div>
                    
                    <!-- Search Box -->
                    <div class="relative">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Cari berdasarkan nama, NIS, atau NISN..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-80"
                        />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Main Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIS
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NISN
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Peserta Didik
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        L/P
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tempat, Tgl Lahir
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Loading State -->
                                <tr v-if="isLoading">
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-gray-500">Memuat data...</span>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Empty State -->
                                <tr v-else-if="filteredStudents.length === 0 && !searchQuery">
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">Belum ada data siswa</p>
                                            <p class="text-sm">Klik tombol "Tambah Siswa" untuk menambahkan data pertama.</p>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- No Search Results -->
                                <tr v-else-if="filteredStudents.length === 0 && searchQuery">
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <p>Tidak ada hasil untuk pencarian "{{ searchQuery }}"</p>
                                    </td>
                                </tr>
                                
                                <!-- Data Rows -->
                                <tr v-for="(student, index) in filteredStudents" :key="student.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ student.nis || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ student.nisn || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ student.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <span :class="student.gender === 'L' ? 'text-blue-600 bg-blue-100' : 'text-pink-600 bg-pink-100'" 
                                              class="px-2 py-1 text-xs font-medium rounded-full">
                                            {{ student.gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ student.birth_place ? `${student.birth_place}, ${formatDate(student.birth_date)}` : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Print Actions -->
                                            <div class="flex space-x-1">
                                                <a :href="route('rapor.cetak_buku_induk', { student: student.id })"
                                                   target="_blank"
                                                   class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 rounded hover:bg-blue-50"
                                                   title="Cetak Buku Induk">
                                                    Buku Induk
                                                </a>
                                                <a :href="route('rapor.cetak', { student: student.id })" 
                                                   target="_blank"
                                                   class="text-green-600 hover:text-green-900 text-xs px-2 py-1 rounded hover:bg-green-50"
                                                   title="Cetak Rapor">
                                                    Rapor
                                                </a>
                                            </div>
                                            
                                            <!-- Edit/Delete Actions -->
                                            <div class="flex space-x-1 ml-2 pl-2 border-l border-gray-200">
                                                <button @click="openEditModal(student)"
                                                        class="text-indigo-600 hover:text-indigo-900 text-xs px-2 py-1 rounded hover:bg-indigo-50"
                                                        title="Edit Data">
                                                    Edit
                                                </button>
                                                <button @click="openConfirmDeleteModal(student)"
                                                        class="text-red-600 hover:text-red-900 text-xs px-2 py-1 rounded hover:bg-red-50"
                                                        title="Hapus Data">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah / Edit Siswa -->
        <Modal :show="showCreateModal || showEditModal" @close="closeModal" max-width="4xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ form.id ? 'Edit' : 'Tambah' }} Data Siswa
                    </h2>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium', 
                                         activeTab === 'pribadi' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600']">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Data Pribadi</span>
                        </div>
                        <div class="flex-1 border-t border-gray-300"></div>
                        <div class="flex items-center">
                            <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium', 
                                         activeTab === 'orangtua' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600']">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Data Orang Tua</span>
                        </div>
                        <div class="flex-1 border-t border-gray-300"></div>
                        <div class="flex items-center">
                            <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium', 
                                         activeTab === 'wali' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600']">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Data Wali</span>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Tab -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="switchTab('pribadi')"
                                :class="[
                                    activeTab === 'pribadi' 
                                        ? 'border-indigo-500 text-indigo-600' 
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none'
                                ]">
                            Data Pribadi
                        </button>
                        <button @click="switchTab('orangtua')"
                                :class="[
                                    activeTab === 'orangtua' 
                                        ? 'border-indigo-500 text-indigo-600' 
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none'
                                ]">
                            Data Orang Tua
                        </button>
                        <button @click="switchTab('wali')"
                                :class="[
                                    activeTab === 'wali' 
                                        ? 'border-indigo-500 text-indigo-600' 
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none'
                                ]">
                            Data Wali
                        </button>
                    </nav>
                </div>

                <form @submit.prevent="submitStudent">
                    <!-- Tab Data Pribadi -->
                    <div v-show="activeTab === 'pribadi'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="name" value="Nama Lengkap" class="required" />
                                <TextInput id="name" 
                                          v-model="form.name" 
                                          required 
                                          class="mt-1 block w-full"
                                          :class="{ 'border-red-500': formErrors.name }" />
                                <InputError :message="formErrors.name ? formErrors.name[0] : ''" class="mt-1" />
                            </div>
                            
                            <div>
                                <InputLabel for="gender" value="Jenis Kelamin" />
                                <select id="gender" 
                                        v-model="form.gender"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            
                            <div>
                                <InputLabel for="nis" value="NIS" class="required" />
                                <TextInput id="nis" 
                                          v-model="form.nis" 
                                          required 
                                          class="mt-1 block w-full"
                                          :class="{ 'border-red-500': formErrors.nis }"
                                          placeholder="Nomor Induk Siswa" />
                                <InputError :message="formErrors.nis ? formErrors.nis[0] : ''" class="mt-1" />
                            </div>
                            
                            <div>
                                <InputLabel for="nisn" value="NISN" />
                                <TextInput id="nisn" 
                                          v-model="form.nisn" 
                                          class="mt-1 block w-full"
                                          :class="{ 'border-red-500': formErrors.nisn }"
                                          placeholder="Nomor Induk Siswa Nasional" />
                                <InputError :message="formErrors.nisn ? formErrors.nisn[0] : ''" class="mt-1" />
                            </div>
                            
                            <div>
                                <InputLabel for="birth_place" value="Tempat Lahir" />
                                <TextInput id="birth_place" 
                                          v-model="form.birth_place" 
                                          class="mt-1 block w-full"
                                          placeholder="Kota tempat lahir" />
                            </div>
                            
                            <div>
                                <InputLabel for="birth_date" value="Tanggal Lahir" />
                                <TextInput id="birth_date" 
                                          v-model="form.birth_date" 
                                          type="date"
                                          class="mt-1 block w-full" />
                            </div>
                            
                            <div>
                                <InputLabel for="religion" value="Agama" />
                                <select id="religion" 
                                        v-model="form.religion"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Pilih Agama</option>
                                    <option v-for="religion in religionOptions" :key="religion" :value="religion">
                                        {{ religion }}
                                    </option>
                                </select>
                            </div>
                            
                            <div>
                                <InputLabel for="previous_education" value="Pendidikan Sebelumnya" />
                                <TextInput id="previous_education" 
                                          v-model="form.previous_education"
                                          class="mt-1 block w-full"
                                          placeholder="TK/PAUD sebelumnya" />
                            </div>
                        </div>
                        
                        <div>
                            <InputLabel for="address" value="Alamat Peserta Didik" />
                            <textarea id="address"
                                     v-model="form.address" 
                                     rows="3"
                                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                     placeholder="Alamat lengkap tempat tinggal siswa"></textarea>
                        </div>
                    </div>

                    <!-- Tab Data Orang Tua -->
                    <div v-show="activeTab === 'orangtua'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="father_name" value="Nama Ayah" />
                                <TextInput id="father_name" 
                                          v-model="form.father_name" 
                                          class="mt-1 block w-full"
                                          placeholder="Nama lengkap ayah" />
                            </div>
                            
                            <div>
                                <InputLabel for="mother_name" value="Nama Ibu" />
                                <TextInput id="mother_name" 
                                          v-model="form.mother_name" 
                                          class="mt-1 block w-full"
                                          placeholder="Nama lengkap ibu" />
                            </div>
                            
                            <div>
                                <InputLabel for="father_job" value="Pekerjaan Ayah" />
                                <TextInput id="father_job" 
                                          v-model="form.father_job" 
                                          class="mt-1 block w-full"
                                          placeholder="Pekerjaan/profesi ayah" />
                            </div>
                            
                            <div>
                                <InputLabel for="mother_job" value="Pekerjaan Ibu" />
                                <TextInput id="mother_job" 
                                          v-model="form.mother_job" 
                                          class="mt-1 block w-full"
                                          placeholder="Pekerjaan/profesi ibu" />
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-sm text-gray-700 mb-4">Alamat Orang Tua</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="parent_address_street" value="Jalan" />
                                    <TextInput id="parent_address_street" 
                                              v-model="form.parent_address_street"
                                              class="mt-1 block w-full"
                                              placeholder="Nama jalan dan nomor rumah" />
                                </div>
                                
                                <div>
                                    <InputLabel for="parent_address_village" value="Kelurahan / Desa" />
                                    <TextInput id="parent_address_village" 
                                              v-model="form.parent_address_village"
                                              class="mt-1 block w-full"
                                              placeholder="Nama kelurahan atau desa" />
                                </div>
                                
                                <div>
                                    <InputLabel for="parent_address_sub_district" value="Kecamatan" />
                                    <TextInput id="parent_address_sub_district" 
                                              v-model="form.parent_address_sub_district"
                                              class="mt-1 block w-full"
                                              placeholder="Nama kecamatan" />
                                </div>
                                
                                <div>
                                    <InputLabel for="parent_address_district" value="Kabupaten / Kota" />
                                    <TextInput id="parent_address_district" 
                                              v-model="form.parent_address_district"
                                              class="mt-1 block w-full"
                                              placeholder="Nama kabupaten atau kota" />
                                </div>
                                
                                <div class="md:col-span-2">
                                    <InputLabel for="parent_address_province" value="Provinsi" />
                                    <TextInput id="parent_address_province" 
                                              v-model="form.parent_address_province"
                                              class="mt-1 block w-full"
                                              placeholder="Nama provinsi" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Data Wali -->
                    <div v-show="activeTab === 'wali'" class="space-y-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>Informasi:</strong> Data wali diisi jika siswa tidak tinggal dengan orang tua kandung atau memiliki wali yang bertanggung jawab.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="guardian_name" value="Nama Wali" />
                                <TextInput id="guardian_name" 
                                          v-model="form.guardian_name" 
                                          class="mt-1 block w-full"
                                          placeholder="Nama lengkap wali" />
                            </div>
                            
                            <div>
                                <InputLabel for="guardian_job" value="Pekerjaan Wali" />
                                <TextInput id="guardian_job" 
                                          v-model="form.guardian_job" 
                                          class="mt-1 block w-full"
                                          placeholder="Pekerjaan/profesi wali" />
                            </div>
                            
                            <div>
                                <InputLabel for="guardian_phone" value="Nomor Telepon Wali" />
                                <TextInput id="guardian_phone" 
                                          v-model="form.guardian_phone" 
                                          class="mt-1 block w-full"
                                          :class="{ 'border-red-500': formErrors.guardian_phone }"
                                          placeholder="Nomor telepon yang bisa dihubungi" />
                                <InputError :message="formErrors.guardian_phone ? formErrors.guardian_phone[0] : ''" class="mt-1" />
                            </div>
                        </div>
                        
                        <div>
                            <InputLabel for="guardian_address" value="Alamat Wali" />
                            <textarea id="guardian_address"
                                     v-model="form.guardian_address" 
                                     rows="3"
                                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                     placeholder="Alamat lengkap tempat tinggal wali"></textarea>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-8">
                        <div class="flex space-x-3">
                            <button v-if="activeTab !== 'pribadi'"
                                    type="button"
                                    @click="switchTab(activeTab === 'wali' ? 'orangtua' : 'pribadi')"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Sebelumnya
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <SecondaryButton @click="closeModal" :disabled="isSubmitting">
                                Batal
                            </SecondaryButton>
                            
                            <button v-if="activeTab !== 'wali'"
                                    type="button"
                                    @click="switchTab(activeTab === 'pribadi' ? 'orangtua' : 'wali')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Selanjutnya
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            
                            <PrimaryButton v-if="activeTab === 'wali'"
                                          class="inline-flex items-center" 
                                          :class="{ 'opacity-25': isSubmitting }"
                                          :disabled="isSubmitting">
                                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ isSubmitting ? 'Menyimpan...' : 'Simpan Data Siswa' }}
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal Konfirmasi Hapus -->
        <Modal :show="showConfirmDeleteModal" @close="closeModal" max-width="md">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus Data</h3>
                    </div>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus data siswa?
                    </p>
                    <div v-if="studentToDelete" class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm">
                            <span class="font-medium">Nama:</span> {{ studentToDelete.name }}<br>
                            <span class="font-medium">NIS:</span> {{ studentToDelete.nis }}<br>
                            <span class="font-medium">NISN:</span> {{ studentToDelete.nisn || '-' }}
                        </p>
                    </div>
                    <p class="text-sm text-red-600 mt-3">
                        <strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan lagi.
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <SecondaryButton @click="closeModal">
                        Batal
                    </SecondaryButton>
                    <DangerButton @click="deleteStudent" class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Data Siswa
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles untuk field required */
.required::after {
    content: " *";
    color: #ef4444;
    font-weight: bold;
}

/* Hover effect untuk baris tabel */
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

/* Styling untuk tab aktif */
.border-indigo-500 {
    border-color: #6366f1;
}

.text-indigo-600 {
    color: #4f46e5;
}

/* Loading spinner animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Custom scrollbar untuk modal */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>