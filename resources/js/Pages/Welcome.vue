<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>

    <Head title="Selamat Datang" />

    <div class="bg-white text-gray-800 min-h-screen">
        <!-- Header Navigasi -->
        <header class="bg-white shadow-sm border-b border-red-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo / Nama Aplikasi -->
                    <div class="flex-shrink-0">
                        <Link href="/" class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-red-600 to-red-700 bg-clip-text text-transparent">
                            SIREKA
                        </span>
                        </Link>
                    </div>

                    <!-- Tombol Login / Register -->
                    <nav class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <!-- Jika user sudah login -->
                            <Link :href="route('dashboard')"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-full transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <!-- Jika user belum login -->
                            <Link v-if="canLogin" :href="route('login')"
                                class="px-6 py-2.5 text-sm font-semibold text-red-600 hover:text-red-700 border border-red-200 hover:border-red-300 rounded-full transition-all duration-300 hover:bg-red-50">
                            Masuk
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <!-- Hero Section -->
            <section class="relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-50 via-white to-red-25"></div>
                <div class="absolute inset-0 opacity-5">
                    <div
                        class="absolute top-0 -left-4 w-72 h-72 bg-red-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse">
                    </div>
                    <div
                        class="absolute top-0 -right-4 w-72 h-72 bg-red-200 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000">
                    </div>
                    <div
                        class="absolute -bottom-8 left-20 w-72 h-72 bg-red-100 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000">
                    </div>
                </div>

                <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
                    <div class="text-center">
                        <!-- Main Title -->
                        <div class="mb-8">
                            <div
                                class="inline-flex items-center px-4 py-2 bg-red-100 rounded-full text-red-700 text-sm font-medium mb-6">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Kurikulum Merdeka
                            </div>
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 tracking-tight mb-6">
                                <span class="block">SIREKA</span>
                                <span class="block text-3xl sm:text-4xl lg:text-5xl font-normal text-gray-600 mt-2">
                                    Sistem Rapor Elektronik
                                </span>
                            </h1>
                        </div>

                        <!-- Description -->
                        <p class="max-w-3xl mx-auto text-xl text-gray-600 leading-relaxed mb-12">
                            Platform digital yang memudahkan guru dalam mengelola data siswa, mengisi capaian
                            pembelajaran, dan
                            mencetak rapor sesuai standar Kurikulum Merdeka dengan interface yang intuitif dan modern.
                        </p>

                        <!-- CTA Button -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <Link :href="route('login')"
                                class="group relative inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <span>Mulai Sekarang</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            </Link>
                            <div class="flex items-center space-x-2 text-gray-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7.001c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Aman & Terpercaya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-20 bg-gray-50">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                            Mengapa Memilih SIREKA?
                        </h2>
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                            Solusi terdepan untuk manajemen rapor elektronik dengan fitur lengkap dan mudah digunakan
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-red-50">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Kelola Data Mudah</h3>
                            <p class="text-gray-600">Input dan kelola data siswa dengan interface yang intuitif dan
                                user-friendly</p>
                        </div>

                        <div
                            class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-red-50">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Capaian Pembelajaran</h3>
                            <p class="text-gray-600">Sistem penilaian yang sesuai dengan standar Kurikulum Merdeka
                                terbaru</p>
                        </div>

                        <div
                            class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-red-50">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Cetak Rapor Instan</h3>
                            <p class="text-gray-600">Generate dan cetak rapor dalam format PDF dengan template yang
                                profesional
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-0">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">SIREKA</span>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-sm text-gray-600">
                            © {{ new Date().getFullYear() }} SIREKA - Sistem Rapor Elektronik Kurikulum Merdeka
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Developed with ❤️ by <span class="font-medium text-red-600">Fredli Fourqoni</span>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>