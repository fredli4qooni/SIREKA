<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';
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

// State untuk form dengan semua field baru
const form = reactive({
    id: null,
    // Data Pribadi Siswa
    nis: '', nisn: '', name: '', gender: 'L', birth_place: '', birth_date: '',
    religion: '', previous_education: '', address: '',
    // Data Orang Tua
    father_name: '', mother_name: '', father_job: '', mother_job: '',
    parent_address_street: '', parent_address_village: '', parent_address_sub_district: '',
    parent_address_district: '', parent_address_province: '',
    // Data Wali
    guardian_name: '', guardian_job: '', guardian_address: '', guardian_phone: ''
});

// State untuk tab di dalam modal
const activeTab = ref('pribadi');

// Fungsi untuk mereset form ke keadaan awal
const resetForm = () => {
    Object.keys(form).forEach(key => {
        form[key] = null;
    });
    form.gender = 'L'; // Nilai default
    formErrors.value = {};
    activeTab.value = 'pribadi';
};

// Fungsi untuk mengambil data siswa dari API
const getStudents = () => {
    isLoading.value = true;
    axios.get('/api/students')
        .then(response => {
            students.value = response.data.data;
        })
        .catch(error => console.error("Gagal mengambil data siswa:", error))
        .finally(() => isLoading.value = false);
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
            form[key] = student[key];
        }
    });
    showEditModal.value = true;
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    showConfirmDeleteModal.value = false;
};

// Fungsi untuk submit form (Create & Update)
const submitStudent = () => {
    const url = form.id ? `/api/students/${form.id}` : '/api/students';
    const method = form.id ? 'put' : 'post';

    axios[method](url, form)
        .then(() => {
            closeModal();
            getStudents();
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                formErrors.value = error.response.data.errors;
            } else {
                console.error("Gagal menyimpan data:", error);
            }
        });
};

// Fungsi untuk menghapus siswa
const openConfirmDeleteModal = (student) => {
    studentToDelete.value = student;
    showConfirmDeleteModal.value = true;
};

const deleteStudent = () => {
    axios.delete(`/api/students/${studentToDelete.value.id}`)
        .then(() => {
            closeModal();
            getStudents();
            studentToDelete.value = null;
        })
        .catch(error => {
            console.error("Gagal menghapus data:", error);
            closeModal();
        });
};
</script>

<template>

    <Head title="Manajemen Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Data Induk Siswa</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <PrimaryButton @click="openCreateModal">+ Tambah Siswa</PrimaryButton>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIS</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NISN</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Peserta Didik</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        L/P</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="isLoading">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                                </tr>
                                <tr v-else-if="students.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data siswa.
                                    </td>
                                </tr>
                                <tr v-for="(student, index) in students" :key="student.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ student.nis }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ student.nisn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                        student.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{
                                        student.gender
                                    }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a :href="route('rapor.cetak_sampul', { student: student.id })" target="_blank"
                                            class="text-gray-600 hover:text-gray-900 mr-2 font-semibold">
                                            Sampul
                                        </a>
                                        <a :href="route('rapor.cetak_buku_induk', { student: student.id })"
                                            target="_blank"
                                            class="text-blue-600 hover:text-blue-900 mr-2 font-semibold">
                                            Buku Induk
                                        </a>

                                        <!-- Tombol Cetak Rapor (sudah ada) -->
                                        <a :href="route('rapor.cetak', { student: student.id })" target="_blank"
                                            class="text-green-600 hover:text-green-900 mr-2 font-semibold">
                                            Rapor
                                        </a>
                                        <SecondaryButton @click="openEditModal(student)" class="mr-2">Edit
                                        </SecondaryButton>
                                        <DangerButton @click="openConfirmDeleteModal(student)">Hapus</DangerButton>
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
                <h2 class="text-lg font-medium text-gray-900">{{ form.id ? 'Edit' : 'Tambah' }} Data Siswa</h2>

                <!-- Navigasi Tab -->
                <div class="border-b border-gray-200 mt-4">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'pribadi'"
                            :class="[activeTab === 'pribadi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">Data
                            Pribadi</button>
                        <button @click="activeTab = 'orangtua'"
                            :class="[activeTab === 'orangtua' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">Data
                            Orang Tua</button>
                        <button @click="activeTab = 'wali'"
                            :class="[activeTab === 'wali' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">Data
                            Wali</button>
                    </nav>
                </div>

                <form @submit.prevent="submitStudent" class="mt-6">
                    <!-- Tab Data Pribadi -->
                    <div v-show="activeTab === 'pribadi'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="name" value="Nama Lengkap" />
                            <TextInput id="name" v-model="form.name" required class="mt-1 block w-full" />
                            <InputError :message="formErrors.name ? formErrors.name[0] : ''" />
                        </div>
                        <div>
                            <InputLabel for="gender" value="Jenis Kelamin" />
                            <select id="gender" v-model="form.gender"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel for="nis" value="NIS" />
                            <TextInput id="nis" v-model="form.nis" required class="mt-1 block w-full" />
                            <InputError :message="formErrors.nis ? formErrors.nis[0] : ''" />
                        </div>
                        <div>
                            <InputLabel for="nisn" value="NISN" />
                            <TextInput id="nisn" v-model="form.nisn" class="mt-1 block w-full" />
                            <InputError :message="formErrors.nisn ? formErrors.nisn[0] : ''" />
                        </div>
                        <div>
                            <InputLabel for="birth_place" value="Tempat Lahir" />
                            <TextInput id="birth_place" v-model="form.birth_place" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="birth_date" value="Tanggal Lahir" />
                            <TextInput id="birth_date" v-model="form.birth_date" type="date"
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="religion" value="Agama" />
                            <TextInput id="religion" v-model="form.religion" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="previous_education" value="Pendidikan Sebelumnya" />
                            <TextInput id="previous_education" v-model="form.previous_education"
                                class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="address" value="Alamat Peserta Didik" /><textarea id="address"
                                v-model="form.address" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                    </div>

                    <!-- Tab Data Orang Tua -->
                    <div v-show="activeTab === 'orangtua'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="father_name" value="Nama Ayah" />
                            <TextInput id="father_name" v-model="form.father_name" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="mother_name" value="Nama Ibu" />
                            <TextInput id="mother_name" v-model="form.mother_name" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="father_job" value="Pekerjaan Ayah" />
                            <TextInput id="father_job" v-model="form.father_job" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="mother_job" value="Pekerjaan Ibu" />
                            <TextInput id="mother_job" v-model="form.mother_job" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <p class="font-medium text-sm text-gray-700">Alamat Orang Tua</p>
                        </div>
                        <div>
                            <InputLabel for="parent_address_street" value="Jalan" />
                            <TextInput id="parent_address_street" v-model="form.parent_address_street"
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="parent_address_village" value="Kelurahan / Desa" />
                            <TextInput id="parent_address_village" v-model="form.parent_address_village"
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="parent_address_sub_district" value="Kecamatan" />
                            <TextInput id="parent_address_sub_district" v-model="form.parent_address_sub_district"
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="parent_address_district" value="Kabupaten / Kota" />
                            <TextInput id="parent_address_district" v-model="form.parent_address_district"
                                class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="parent_address_province" value="Provinsi" />
                            <TextInput id="parent_address_province" v-model="form.parent_address_province"
                                class="mt-1 block w-full" />
                        </div>
                    </div>

                    <!-- Tab Data Wali -->
                    <div v-show="activeTab === 'wali'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="guardian_name" value="Nama Wali" />
                            <TextInput id="guardian_name" v-model="form.guardian_name" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <InputLabel for="guardian_job" value="Pekerjaan Wali" />
                            <TextInput id="guardian_job" v-model="form.guardian_job" class="mt-1 block w-full" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="guardian_address" value="Alamat Wali" /><textarea id="guardian_address"
                                v-model="form.guardian_address" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <div>
                            <InputLabel for="guardian_phone" value="Nomor Telepon Wali" />
                            <TextInput id="guardian_phone" v-model="form.guardian_phone" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 pt-6 border-t">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Simpan Data Siswa
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal Konfirmasi Hapus -->
        <Modal :show="showConfirmDeleteModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Apakah Anda Yakin?</h2>
                <p class="mt-1 text-sm text-gray-600">Data siswa <span v-if="studentToDelete" class="font-medium">"{{
                    studentToDelete.name }}"</span> akan dihapus secara permanen.</p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                    <DangerButton class="ms-3" @click="deleteStudent">Hapus Siswa</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>