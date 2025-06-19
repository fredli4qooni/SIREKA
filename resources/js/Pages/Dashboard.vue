<!-- resources/js/Pages/Dashboard.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Menerima props dari controller/route Laravel
const props = defineProps({
    stats: Object,
    schoolName: String,
    auth: Object, // auth prop sudah otomatis dikirim oleh Inertia
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Banner Selamat Datang - Elegant Red & White -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl mb-8 border border-gray-100">
                    <div class="relative bg-gradient-to-r from-white to-red-50 text-gray-800 rounded-3xl overflow-hidden">
                        <!-- Subtle Pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="absolute inset-0"
                                style="background-image: radial-gradient(circle at 1px 1px, rgba(220,38,38,0.1) 1px, transparent 0); background-size: 30px 30px;">
                            </div>
                        </div>

                        <div class="relative p-8 md:p-12 flex flex-col lg:flex-row items-center justify-between">
                            <!-- Konten Teks -->
                            <div class="flex-1 lg:pr-8">
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-50 text-red-700 mb-6 border border-red-100">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Sistem Rapor Kurikulum Merdeka
                                </div>

                                <h2 class="text-4xl md:text-5xl font-light mb-4 text-gray-900 leading-tight">
                                    Selamat Datang,
                                    <span class="font-semibold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">
                                        {{ props.auth.user.name }}
                                    </span>
                                </h2>

                                <p class="text-xl text-gray-600 leading-relaxed mb-6 font-light">
                                    Kelola penilaian dan rapor siswa 
                                    <span class="font-medium text-red-700">{{ props.schoolName }}</span>
                                </p>

                                <div class="flex items-center text-red-600">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium">Platform terintegrasi untuk manajemen rapor digital</span>
                                </div>
                            </div>

                            <!-- Area Ilustrasi -->
                            <div class="flex-shrink-0 lg:w-80 mt-8 lg:mt-0">
                                <div class="relative">
                                    <div class="w-full h-64 lg:h-80 bg-gradient-to-br from-red-50 to-white rounded-3xl border border-red-100 flex items-center justify-center shadow-inner">
                                        <img src="/images/logo.png" alt="Dashboard Illustration"
                                            class="w-full h-64 lg:h-80 object-contain rounded-3xl opacity-90" />
                                    </div>
                                    <!-- Elemen dekoratif minimal -->
                                    <div class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 rounded-full"></div>
                                    <div class="absolute -bottom-2 -left-2 w-3 h-3 bg-red-300 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Statistik - Minimalis -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <!-- Kartu Jumlah Siswa -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Siswa</h3>
                                    </div>
                                    <div>
                                        <p class="text-4xl font-light text-gray-900 mb-2">{{ props.stats.total_students }}</p>
                                        <p class="text-sm text-gray-500">Siswa terdaftar aktif</p>
                                    </div>
                                </div>
                                <div class="w-2 h-16 bg-gradient-to-b from-red-500 to-red-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Jumlah Mata Pelajaran -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-md transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Mata Pelajaran</h3>
                                    </div>
                                    <div>
                                        <p class="text-4xl font-light text-gray-900 mb-2">{{ props.stats.total_subjects }}</p>
                                        <p class="text-sm text-gray-500">Mata pelajaran dinilai</p>
                                    </div>
                                </div>
                                <div class="w-2 h-16 bg-gradient-to-b from-red-500 to-red-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Utama - Elegant & Minimal -->
                <div class="mb-12">
                    <div class="text-center mb-10">
                        <h3 class="text-3xl font-light text-gray-900 mb-2">Menu Utama</h3>
                        <div class="w-16 h-px bg-red-500 mx-auto"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Input Rapor -->
                        <Link :href="route('rapor.input')"
                            class="group relative bg-white rounded-3xl shadow-sm hover:shadow-lg transition-all duration-500 border border-gray-100 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative p-8 text-center">
                                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-100 transition-colors duration-300">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                                <h5 class="text-xl font-medium text-gray-900 mb-3">Input Rapor</h5>
                                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                                    Isi capaian pembelajaran dan predikat siswa dengan mudah dan efisien
                                </p>
                                <div class="flex items-center justify-center text-red-600 font-medium text-sm group-hover:text-red-700">
                                    <span>Mulai Input</span>
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </Link>

                        <!-- Manajemen Siswa -->
                        <Link :href="route('students.index')"
                            class="group relative bg-white rounded-3xl shadow-sm hover:shadow-lg transition-all duration-500 border border-gray-100 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative p-8 text-center">
                                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-100 transition-colors duration-300">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h5 class="text-xl font-medium text-gray-900 mb-3">Manajemen Siswa</h5>
                                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                                    Tambah, edit, atau hapus data siswa dengan interface yang user-friendly
                                </p>
                                <div class="flex items-center justify-center text-red-600 font-medium text-sm group-hover:text-red-700">
                                    <span>Kelola Siswa</span>
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </Link>

                        <!-- Pengaturan -->
                        <Link :href="route('settings.page')"
                            class="group relative bg-white rounded-3xl shadow-sm hover:shadow-lg transition-all duration-500 border border-gray-100 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative p-8 text-center">
                                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-100 transition-colors duration-300">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h5 class="text-xl font-medium text-gray-900 mb-3">Pengaturan</h5>
                                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                                    Ubah data sekolah dan konfigurasi sistem rapor sesuai kebutuhan
                                </p>
                                <div class="flex items-center justify-center text-red-600 font-medium text-sm group-hover:text-red-700">
                                    <span>Atur Sistem</span>
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Footer Info - Minimal -->
                <div class="text-center py-8">
                    <div class="w-16 h-px bg-gray-300 mx-auto mb-4"></div>
                    <p class="text-gray-400 text-sm font-light">
                        Sistem Rapor Digital Kurikulum Merdeka — {{ props.schoolName }}
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>