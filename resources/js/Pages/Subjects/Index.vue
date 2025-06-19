<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'; // <-- TAMBAHKAN 'Link' DI SINI
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// Menerima data 'subjects' dari controller Laravel via props
const props = defineProps({
    subjects: Array,
});

// Mengakses properti global Inertia, termasuk flash messages
const page = usePage();

// === PERBAIKAN DI SINI ===
// Menggunakan optional chaining (?.) untuk mengakses properti dengan aman.
// Jika page.props.flash tidak ada, hasilnya akan undefined (bukan error).
const successMessage = computed(() => page.props.flash?.message);
const errorMessage = computed(() => page.props.flash?.error);
// === BATAS PERBAIKAN ===

const showModal = ref(false);
const isEditMode = ref(false);
const subjectToDelete = ref(null);
const showConfirmDeleteModal = ref(false);

// Menggunakan helper useForm dari Inertia untuk menangani state form,
// error validasi, dan status loading (processing).
const form = useForm({
    id: null,
    name: '',
    order: '',
});

// Fungsi untuk membuka modal, baik untuk mode tambah maupun edit
const openModal = (subject = null) => {
    form.clearErrors(); // Bersihkan error validasi dari sesi sebelumnya
    if (subject) {
        // Mode Edit: isi form dengan data mata pelajaran yang dipilih
        isEditMode.value = true;
        form.id = subject.id;
        form.name = subject.name;
        form.order = subject.order;
    } else {
        // Mode Tambah: reset form ke kondisi awal
        isEditMode.value = false;
        form.reset();
    }
    showModal.value = true;
};

// Fungsi untuk menutup semua modal
const closeModal = () => {
    showModal.value = false;
    showConfirmDeleteModal.value = false;
};

// Fungsi untuk mengirim data form ke backend
const submitForm = () => {
    if (isEditMode.value) {
        // Kirim request PUT untuk update
        form.put(route('subjects.update', form.id), {
            preserveScroll: true, // Agar halaman tidak scroll ke atas setelah submit
            onSuccess: () => closeModal(),
        });
    } else {
        // Kirim request POST untuk membuat data baru
        form.post(route('subjects.store'), {
            preserveScroll: true,

            onSuccess: () => closeModal(),
        });
    }
};

// Fungsi untuk membuka modal konfirmasi hapus
const confirmDelete = (subject) => {
    subjectToDelete.value = subject;
    showConfirmDeleteModal.value = true;
};

// Fungsi untuk menghapus data mata pelajaran
const deleteSubject = () => {
    useForm({}).delete(route('subjects.destroy', subjectToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <Head title="Manajemen Mata Pelajaran" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Mata Pelajaran</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Tampilkan Flash Messages jika ada -->
                <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ successMessage }}</div>
                <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ errorMessage }}</div>

                <div class="mb-4 flex justify-end">
                    <PrimaryButton @click="openModal()">+ Tambah Mata Pelajaran</PrimaryButton>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Urut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Pelajaran</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="subject in props.subjects" :key="subject.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ subject.order }}</td>
                                <td class="px-6 py-4 whitespace-nowrap"><Link :href="route('subjects.show', subject.id)" class="text-indigo-600 hover:text-indigo-900 font-medium">
        {{ subject.name }}
    </Link></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <SecondaryButton @click="openModal(subject)" class="mr-2">Edit</SecondaryButton>
                                    <DangerButton @click="confirmDelete(subject)">Hapus</DangerButton>
                                </td>
                            </tr>
                            <tr v-if="props.subjects.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada mata pelajaran yang ditambahkan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal untuk Tambah/Edit Mata Pelajaran -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">{{ isEditMode ? 'Edit' : 'Tambah' }} Mata Pelajaran</h2>
                <form @submit.prevent="submitForm" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Mata Pelajaran" />
                        <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required autofocus />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="order" value="Nomor Urut di Rapor" />
                        <TextInput id="order" v-model="form.order" type="number" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.order" class="mt-2" />
                    </div>
                    <div class="flex justify-end pt-4 border-t mt-6">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal untuk Konfirmasi Hapus -->
        <Modal :show="showConfirmDeleteModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Hapus Mata Pelajaran?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Anda yakin ingin menghapus mata pelajaran "{{ subjectToDelete?.name }}"? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                    <DangerButton @click="deleteSubject" class="ml-2">Hapus</DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>