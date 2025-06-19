<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// Menerima data 'extracurriculars' dari controller
const props = defineProps({
    extracurriculars: Array,
});

// Untuk menampilkan pesan flash (sukses/error) dari controller
const page = usePage();
const successMessage = computed(() => page.props.flash?.message);
const errorMessage = computed(() => page.props.flash?.error);

const showModal = ref(false);
const isEditMode = ref(false);
const itemToDelete = ref(null); // Gunakan nama generik
const showConfirmDeleteModal = ref(false);

// Gunakan useForm dari Inertia
const form = useForm({
    id: null,
    name: '',
});

const openModal = (item = null) => {
    form.clearErrors();
    if (item) {
        isEditMode.value = true;
        form.id = item.id;
        form.name = item.name;
    } else {
        isEditMode.value = false;
        form.reset(); // Reset form
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    showConfirmDeleteModal.value = false;
};

const submitForm = () => {
    if (isEditMode.value) {
        form.put(route('extracurriculars.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('extracurriculars.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showConfirmDeleteModal.value = true;
};

const deleteItem = () => {
    const deleteForm = useForm({});
    deleteForm.delete(route('extracurriculars.destroy', itemToDelete.value.id), {
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <Head title="Manajemen Ekstrakurikuler" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Ekstrakurikuler</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ successMessage }}</div>
                <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ errorMessage }}</div>

                <div class="mb-4">
                    <PrimaryButton @click="openModal()">+ Tambah Ekstrakurikuler</PrimaryButton>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left w-4/5">Nama Ekstrakurikuler</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in props.extracurriculars" :key="item.id">
                                <td class="px-6 py-4">{{ item.name }}</td>
                                <td class="px-6 py-4 text-right">
                                    <SecondaryButton @click="openModal(item)" class="mr-2">Edit</SecondaryButton>
                                    <DangerButton @click="confirmDelete(item)">Hapus</DangerButton>
                                </td>
                            </tr>
                            <tr v-if="props.extracurriculars.length === 0">
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">Belum ada ekstrakurikuler yang ditambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal Tambah/Edit -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">{{ isEditMode ? 'Edit' : 'Tambah' }} Ekstrakurikuler</h2>
                <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Ekstrakurikuler" />
                        <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required autofocus />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="flex justify-end pt-4">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal Hapus -->
        <Modal :show="showConfirmDeleteModal" @close="closeModal">
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500">Anda yakin ingin menghapus ekstrakurikuler "{{ itemToDelete?.name }}"?</h3>
                <DangerButton @click="deleteItem" class="mr-2">Ya, Hapus</DangerButton>
                <SecondaryButton @click="closeModal">Batal</SecondaryButton>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>