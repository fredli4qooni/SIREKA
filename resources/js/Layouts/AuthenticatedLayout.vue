<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';

// State untuk mengelola sidebar di mode mobile (fitur masa depan)
const sidebarOpen = ref(false);
</script>

<template>
    <div class="h-screen flex overflow-hidden bg-gray-50">
        <!-- ================================= -->
        <!-- === SIDEBAR ELEGANT MINIMAL === -->
        <!-- ================================= -->
        <!-- Sidebar untuk Desktop -->
        <aside class="w-72 bg-white flex-shrink-0 hidden md:flex md:flex-col shadow-lg border-r border-gray-100">
            <!-- Logo Section -->
            <div class="h-24 flex items-center justify-center flex-shrink-0 px-6 bg-gradient-to-r from-red-500 to-red-600 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-full bg-white" style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)"></div>
                </div>
                <Link :href="route('dashboard')" class="flex items-center relative z-10">
                    <!-- Document Icon -->
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <span class="text-white text-xl font-bold tracking-tight">SIREKA</span>
                        <div class="text-white/80 text-xs font-medium leading-tight">
                            Sistem Elektronik Rapot<br>Kurikulum Merdeka
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                <!-- Menu Utama -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-red-500 to-red-300 rounded-full"></div>
                        <h3 class="ml-3 text-xs uppercase text-gray-500 font-semibold tracking-widest">Menu Utama</h3>
                    </div>
                    <div class="space-y-1">
                        <NavLink 
                            :href="route('dashboard')" 
                            :active="route().current('dashboard')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('dashboard') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            </div>
                            Dashboard
                        </NavLink>
                        
                        <NavLink 
                            :href="route('students.index')" 
                            :active="route().current('students.index*')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('students.index*') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            Manajemen Siswa
                        </NavLink>
                    </div>
                </div>

                <!-- Penilaian -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-red-500 to-red-300 rounded-full"></div>
                        <h3 class="ml-3 text-xs uppercase text-gray-500 font-semibold tracking-widest">Penilaian</h3>
                    </div>
                    <div class="space-y-1">
                        <NavLink 
                            :href="route('scores.input.summative')" 
                            :active="route().current('scores.input.summative')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('scores.input.summative') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Input Nilai Sumatif
                        </NavLink>
                        
                        <NavLink 
                            :href="route('rapor.input')" 
                            :active="route().current('rapor.input')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('rapor.input') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2V3a2 2 0 012-2 2 2 0 012 2v8a4 4 0 01-4 4H6a4 4 0 01-4-4V5z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Input Capaian
                        </NavLink>
                        
                        <NavLink 
                            :href="route('extracurriculars.scores.input')" 
                            :active="route().current('extracurriculars.scores.input')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('extracurriculars.scores.input') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Input Nilai Ekskul
                        </NavLink>
                        
                        <NavLink 
                            :href="route('attendances.input')" 
                            :active="route().current('attendances.input')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('attendances.input') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Input Kehadiran
                        </NavLink>
                    </div>
                </div>

                <!-- Data Master -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-red-500 to-red-300 rounded-full"></div>
                        <h3 class="ml-3 text-xs uppercase text-gray-500 font-semibold tracking-widest">Data Master</h3>
                    </div>
                    <div class="space-y-1">
                        <NavLink 
                            :href="route('subjects.index')" 
                            :active="route().current('subjects.index*')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('subjects.index*') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                            Mata Pelajaran & TP
                        </NavLink>
                        
                        <NavLink 
                            :href="route('extracurriculars.index')" 
                            :active="route().current('extracurriculars.index')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('extracurriculars.index') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Manajemen Ekskul
                        </NavLink>
                    </div>
                </div>

                <!-- Laporan -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-0.5 bg-gradient-to-r from-red-500 to-red-300 rounded-full"></div>
                        <h3 class="ml-3 text-xs uppercase text-gray-500 font-semibold tracking-widest">Laporan</h3>
                    </div>
                    <div class="space-y-1">
                        <NavLink 
                            :href="route('report.recap')" 
                            :active="route().current('report.recap')" 
                            :is-sidebar-link="true"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-red-50 hover:text-red-600"
                            :class="route().current('report.recap') ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-gray-700 hover:bg-red-50'"
                        >
                            <div class="w-5 h-5 mr-3 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a1 1 0 00.553-.894l2-1A1 1 0 005 6h2a1 1 0 00.447-.106l2-1z"/>
                                </svg>
                            </div>
                            Rekap Nilai Rapor
                        </NavLink>
                    </div>
                </div>
            </nav>

            <!-- Footer Sidebar -->
            <div class="px-4 pb-4">
                <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-4 border border-red-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">Butuh Bantuan?</p>
                            <p class="text-xs text-gray-600">Hubungi Admin</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- =========================================== -->
        <!-- === BAGIAN KONTEN UTAMA (BISA SCROLL) === -->
        <!-- =========================================== -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Topbar Enhanced -->
            <header class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow-sm border-b border-gray-100">
                <div class="flex-1 px-6 flex justify-end items-center">
                    <!-- Search Bar (Optional) -->
                    <div class="hidden lg:flex items-center mr-4">
                        <div class="relative">
                            <input type="text" placeholder="Cari..." class="w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="flex items-center">
                        <div class="ms-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-white text-sm font-semibold">
                                                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                            {{ $page.props.auth.user.name }}
                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('settings.page')"> Pengaturan Rapor </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Konten Utama -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none bg-gray-50">
                <div class="py-6">
                    <!-- Header Halaman -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8" v-if="$slots.header">
                        <div class="pb-6 border-b border-gray-200">
                             <slot name="header" />
                        </div>
                    </div>

                    <!-- Konten Utama Halaman -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mt-6">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>