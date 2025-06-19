<!-- resources/js/Pages/P5/Input.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, watch } from 'vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    students: Array,
    settings: Object,
});

const selectedStudentId = ref(null);
const projects = ref([]);
const isLoading = ref(false);

// Form
const form = reactive({
    id: null,
    academic_year: props.settings.academic_year,
    project_name: '',
    description: ''
});
const formErrors = ref({});
const showModal = ref(false);
const isEditMode = ref(false);

// Modal Hapus
const showConfirmDeleteModal = ref(false);
const projectToDelete = ref(null);

// Ambil data P5 saat siswa dipilih
const fetchP5Data = () => {
    if (!selectedStudentId.value) {
        projects.value = [];
        return;
    }
    isLoading.value = true;
    axios.get(`/api/students/${selectedStudentId.value}/p5projects`)
        .then(res => projects.value = res.data.data)
        .finally(() => isLoading.value = false);
};
watch(selectedStudentId, fetchP5Data);

// Fungsi Modal
const openModal = (project = null) => {
    formErrors.value = {};
    if (project) { // Mode Edit
        isEditMode.value = true;
        form.id = project.id;
        form.project_name = project.project_name;
        form.description = project.description;
    } else { // Mode Tambah
        isEditMode.value = false;
        form.id = null;
        form.project_name = '';
        form.description = '';
    }
    showModal.value = true;
};
const closeModal = () => {
    showModal.value = false;
    showConfirmDeleteModal.value = false;
};

// Fungsi CRUD
const submitForm = () => {
    const url = isEditMode.value ? `/api/p5projects/${form.id}` : `/api/students/${selectedStudentId.value}/p5projects`;
    const method = isEditMode.value ? 'put' : 'post';

    axios[method](url, form)
        .then(() => {
            closeModal();
            fetchP5Data(); // Refresh list
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                formErrors.value = error.response.data.errors;
            }
        });
};

const confirmDelete = (project) => {
    projectToDelete.value = project;
    showConfirmDeleteModal.value = true;
};

const deleteProject = () => {
    axios.delete(`/api/p5projects/${projectToDelete.value.id}`)
        .then(() => {
            closeModal();
            fetchP5Data();
        });
};
</script>

<template>

    <Head title="Input Proyek P5" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Proyek Penguatan Profil Pelajar
                Pancasila (P5)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Pilih Siswa -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <label for="student" class="block font-medium text-sm text-gray-700">Pilih Siswa</label>
                    <select v-model="selectedStudentId" id="student"
                        class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm">
                        <option :value="null" disabled>-- Pilih seorang siswa --</option>
                        <option v-for="student in props.students" :key="student.id" :value="student.id">{{ student.name
                            }}
                        </option>
                    </select>
                </div>

                <!-- Daftar Proyek P5 -->
                <div v-if="selectedStudentId" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Proyek</h3>
                        <PrimaryButton @click="openModal()">+ Tambah Proyek</PrimaryButton>
                    </div>
                    <div v-if="isLoading">Memuat...</div>
                    <div v-else-if="projects.length > 0" class="space-y-4">
                        <div v-for="project in projects" :key="project.id" class="p-4 border rounded-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ project.project_name }}</h4>
                                    <p class="mt-2 text-sm text-gray-600 whitespace-pre-wrap">{{ project.description }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 ml-4">
                                    <SecondaryButton @click="openModal(project)" class="mr-2">Edit</SecondaryButton>
                                    <DangerButton @click="confirmDelete(project)">Hapus</DangerButton>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center text-gray-500">Belum ada proyek P5 yang diinput untuk siswa ini.
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah/Edit -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">{{ isEditMode ? 'Edit' : 'Tambah' }} Proyek P5</h2>
                <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="project_name" value="Nama Proyek" />
                        <TextInput id="project_name" v-model="form.project_name" class="mt-1 block w-full" required />
                        <InputError :message="formErrors.project_name ? formErrors.project_name[0] : ''" />
                    </div>
                    <div>
                        <InputLabel for="description" value="Narasi Deskriptif" />
                        <textarea id="description" v-model="form.description" rows="5"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                        <InputError :message="formErrors.description ? formErrors.description[0] : ''" />
                    </div>
                    <div class="flex justify-end">
                        <PrimaryButton>Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal Hapus -->
        <Modal :show="showConfirmDeleteModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium">Hapus Proyek?</h2>
                <p class="mt-2">Anda yakin ingin menghapus proyek "{{ projectToDelete?.project_name }}"?</p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                    <DangerButton @click="deleteProject" class="ml-2">Hapus</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>