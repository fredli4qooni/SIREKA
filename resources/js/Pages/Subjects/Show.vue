<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// --- PROPS ---
const props = defineProps({
    subject: Object,
});

// --- STATE MANAGEMENT ---
const activeTab = ref('tp'); // 'tp' atau 'materi'

// --- STATE UNTUK TUJUAN PEMBELAJARAN (TP) ---
const localLearningGoals = ref([...props.subject.learning_goals]);
const tpForm = useForm({
    id: null,
    subject_id: props.subject.id,
    goal_description: '',
    scope: '',
});
const showTpModal = ref(false);
const isTpEditMode = ref(false);

// --- STATE UNTUK MATERI SUMATIF ---
const localSummativeMaterials = ref([...props.subject.summative_materials]);
const materialForm = useForm({
    id: null,
    subject_id: props.subject.id,
    material_name: '',
    order: 0,
});
const showMaterialModal = ref(false);
const isMaterialEditMode = ref(false);


// --- FUNGSI-FUNGSI ---

// Fungsi untuk me-refresh semua data di halaman ini
const refreshData = async () => {
    try {
        const response = await axios.get(route('api.subjects.show', props.subject.id));
        localLearningGoals.value = response.data.learning_goals;
        localSummativeMaterials.value = response.data.summative_materials;
    } catch (error) {
        console.error("Gagal me-refresh data:", error);
    }
};

// --- FUNGSI UNTUK TUJUAN PEMBELAJARAN (TP) ---
const openTpModal = (goal = null) => {
    tpForm.clearErrors();
    if (goal) {
        isTpEditMode.value = true;
        tpForm.id = goal.id;
        tpForm.goal_description = goal.goal_description;
        tpForm.scope = goal.scope;
    } else {
        isTpEditMode.value = false;
        tpForm.reset(); // Mereset form ke nilai awal
    }
    tpForm.subject_id = props.subject.id;
    showTpModal.value = true;
};

const closeTpModal = () => showTpModal.value = false;

const submitTpForm = () => {
    const url = isTpEditMode.value ? route('api.learning-goals.update', tpForm.id) : route('api.learning-goals.store');
    const options = {
        onSuccess: () => {
            closeTpModal();
            refreshData();
        },
        onError: () => {
            // Error akan otomatis ditangani oleh form helper Inertia
        }
    };

    if (isTpEditMode.value) {
        tpForm.put(url, options);
    } else {
        tpForm.post(url, options);
    }
};

const deleteGoal = (goalId) => {
    if (confirm('Apakah Anda yakin ingin menghapus Tujuan Pembelajaran ini?')) {
        const deleteForm = useForm({});
        deleteForm.delete(route('api.learning-goals.destroy', goalId), {
            onSuccess: () => refreshData()
        });
    }
};


// --- FUNGSI UNTUK MATERI SUMATIF ---
const openMaterialModal = (material = null) => {
    materialForm.clearErrors();
    if (material) {
        isMaterialEditMode.value = true;
        materialForm.id = material.id;
        materialForm.material_name = material.material_name;
        materialForm.order = material.order;
    } else {
        isMaterialEditMode.value = false;
        materialForm.reset();
        materialForm.order = (localSummativeMaterials.value.length + 1);
    }
    materialForm.subject_id = props.subject.id;
    showMaterialModal.value = true;
};

const closeMaterialModal = () => showMaterialModal.value = false;

const submitMaterialForm = () => {
    const url = isMaterialEditMode.value ? route('api.summative-materials.update', materialForm.id) : route('api.summative-materials.store');
    const options = {
        onSuccess: () => {
            closeMaterialModal();
            refreshData();
        }
    };
    if (isMaterialEditMode.value) {
        materialForm.put(url, options);
    } else {
        materialForm.post(url, options);
    }
};

const deleteMaterial = (materialId) => {
    if (confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
        const deleteForm = useForm({});
        deleteForm.delete(route('api.summative-materials.destroy', materialId), {
            onSuccess: () => refreshData()
        });
    }
};

</script>

<template>
    <Head :title="`Detail ${subject.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Mata Pelajaran: {{ subject.name }}
            </h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <Link :href="route('subjects.index')" class="text-indigo-600 hover:underline">← Kembali ke Daftar Mata Pelajaran</Link>
                </div>

                <!-- Navigasi Tab -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'tp'" :class="[activeTab === 'tp' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">
                            Tujuan Pembelajaran
                        </button>
                        <button @click="activeTab = 'materi'" :class="[activeTab === 'materi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">
                            Materi Sumatif
                        </button>
                    </nav>
                </div>

                <!-- Konten Tab -->
                <div>
                    <!-- Konten Tab Tujuan Pembelajaran -->
                    <div v-show="activeTab === 'tp'">
                        <div class="mb-4 flex justify-end">
                            <PrimaryButton @click="openTpModal()">+ Tambah Tujuan Pembelajaran</PrimaryButton>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left w-2/5">Alur Tujuan Pembelajaran</th>
                                        <th class="px-6 py-3 text-left w-2/5">Lingkup Materi</th>
                                        <th class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="goal in localLearningGoals" :key="goal.id">
                                        <td class="px-6 py-4 align-top whitespace-pre-wrap">{{ goal.goal_description }}</td>
                                        <td class="px-6 py-4 align-top">{{ goal.scope }}</td>
                                        <td class="px-6 py-4 text-right align-top">
                                            <SecondaryButton @click="openTpModal(goal)" class="mr-2">Edit</SecondaryButton>
                                            <DangerButton @click="deleteGoal(goal.id)">Hapus</DangerButton>
                                        </td>
                                    </tr>
                                    <tr v-if="localLearningGoals.length === 0">
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada Tujuan Pembelajaran yang ditambahkan.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Konten Tab Materi Sumatif -->
                    <div v-show="activeTab === 'materi'">
                        <div class="mb-4 flex justify-end">
                            <PrimaryButton @click="openMaterialModal()">+ Tambah Materi Sumatif</PrimaryButton>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left">No. Urut</th>
                                        <th class="px-6 py-3 text-left">Nama Materi Sumatif</th>
                                        <th class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="material in localSummativeMaterials" :key="material.id">
                                        <td class="px-6 py-4">{{ material.order }}</td>
                                        <td class="px-6 py-4">{{ material.material_name }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <SecondaryButton @click="openMaterialModal(material)" class="mr-2">Edit</SecondaryButton>
                                            <DangerButton @click="deleteMaterial(material.id)">Hapus</DangerButton>
                                        </td>
                                    </tr>
                                     <tr v-if="localSummativeMaterials.length === 0">
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada materi yang ditambahkan.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah/Edit TP -->
            <Modal :show="showTpModal" @close="closeTpModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium">{{ isTpEditMode ? 'Edit' : 'Tambah' }} Tujuan Pembelajaran</h2>
                    <form @submit.prevent="submitTpForm" class="mt-6 space-y-4">
                        <div>
                            <InputLabel for="scope" value="Lingkup Materi" />
                            <TextInput id="scope" v-model="tpForm.scope" class="mt-1 block w-full" required />
                            <InputError :message="tpForm.errors.scope" />
                        </div>
                        <div>
                            <InputLabel for="goal_description" value="Alur Tujuan Pembelajaran (TP)" />
                            <textarea id="goal_description" v-model="tpForm.goal_description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                            <InputError :message="tpForm.errors.goal_description" />
                        </div>
                        <div class="flex justify-end pt-4">
                            <SecondaryButton @click="closeTpModal">Batal</SecondaryButton>
                            <PrimaryButton type="submit" class="ms-3" :class="{ 'opacity-25': tpForm.processing }" :disabled="tpForm.processing">Simpan</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
            
            <!-- Modal Tambah/Edit Materi Sumatif -->
            <Modal :show="showMaterialModal" @close="closeMaterialModal">
                 <div class="p-6">
                    <h2 class="text-lg font-medium">{{ isMaterialEditMode ? 'Edit' : 'Tambah' }} Materi Sumatif</h2>
                    <form @submit.prevent="submitMaterialForm" class="mt-6 space-y-4">
                        <div>
                            <InputLabel for="material_name" value="Nama Materi" />
                            <TextInput id="material_name" v-model="materialForm.material_name" class="mt-1 block w-full" required />
                             <InputError :message="materialForm.errors.material_name" />
                        </div>
                         <div>
                            <InputLabel for="material_order" value="No. Urut" />
                            <TextInput id="material_order" v-model="materialForm.order" type="number" class="mt-1 block w-full" required />
                            <InputError :message="materialForm.errors.order" />
                        </div>
                        <div class="flex justify-end pt-4">
                            <SecondaryButton @click="closeMaterialModal">Batal</SecondaryButton>
                            <PrimaryButton type="submit" class="ms-3" :class="{ 'opacity-25': materialForm.processing }" :disabled="materialForm.processing">Simpan</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
</div>
        </AuthenticatedLayout>
    </template>