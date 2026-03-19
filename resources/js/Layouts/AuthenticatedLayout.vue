<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue'

const animateMenu = ref(false)

onMounted(() => {
    setTimeout(() => {
        animateMenu.value = true
    }, 100)
})
const openTransaksi = ref(false)
const openRekap = ref(false)
const openMaster = ref(false)
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 md:flex">
            <button type="button" @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex md:hidden items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>
            <div
                v-if="showingNavigationDropdown"
                @click="showingNavigationDropdown = false"
                class="fixed inset-0 bg-black bg-opacity-40 md:hidden z-30" 
            />
            <aside :class="[
                    'fixed md:static top-0 left-0 z-40 w-64 min-h-screen bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out',
                    showingNavigationDropdown ? 'translate-x-0' : '-translate-x-full',
                    'md:translate-x-0'
                ]"
            >
                <div class="flex flex-col overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <TransitionGroup name="menu" tag="ul" class="space-y-2">
                        <li v-if="animateMenu" key="transaksi" style="transition-delay: 0.5s">
                            <button @click="openTransaksi = !openTransaksi" type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-pages" data-collapse-toggle="dropdown-pages">
                                <svg class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap">Transaksi</span>
                                <svg :class="openTransaksi ? 'rotate-180 transition-transform duration-300' : 'rotate-0 transition-transform duration-300'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <Transition name="slide">
                                <ul v-show="openTransaksi" class="py-2 space-y-2">
                                   <DropdownLink
                                        :href="route('pembelian')"
                                    >
                                    🛒 Pembelian
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('penjualan')"
                                    >
                                    🏪 Penjualan
                                    </DropdownLink>
                                </ul>
                            </Transition>
                        </li>
                        <li v-if="animateMenu" key="rekap" style="transition-delay: 1s">
                            <button @click="openRekap = !openRekap" type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-sales" data-collapse-toggle="dropdown-sales">
                                <svg class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap">Rekapitulasi</span>
                                <svg :class="openRekap ? 'rotate-180 transition-transform duration-300' : 'rotate-0 transition-transform duration-300'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <Transition name="slide">
                                <ul v-show="openRekap" class="py-2 space-y-2">
                                    <li>
                                        <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Products</a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Billing</a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Invoice</a>
                                    </li>
                                </ul>
                            </Transition>
                        </li>
                        <li v-if="animateMenu" key="master" style="transition-delay: 1.5s">
                            <button @click="openMaster = !openMaster" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-authentication" data-collapse-toggle="dropdown-authentication">
                                <svg class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap">Master</span>
                                <svg :class="openMaster ? 'rotate-180 transition-transform duration-300' : 'rotate-0 transition-transform duration-300'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <Transition name="slide">
                                <ul v-show="openMaster" class="py-2 space-y-2">
                                    <DropdownLink
                                        :href="route('customer')"
                                    >
                                    👥 Customers
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('driver')"
                                    >
                                    🚚 Driver
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('vendor')"
                                    >
                                    🏭 Vendor
                                    </DropdownLink>
                                </ul>
                            </Transition>
                        </li>
                    </TransitionGroup>
                    <TransitionGroup name="menu" tag="ul" class="pt-5 mt-5 space-y-2 border-t border-gray-200 dark:border-gray-700">
                        <li v-if="animateMenu" key="profile" style="transition-delay: 2s">
                            <DropdownLink
                                :href="route('profile.edit')"
                            >
                               👤 Profile
                            </DropdownLink>
                        </li>
                        <li v-if="animateMenu" key="logout" style="transition-delay: 2.5s">
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                               🚪 Log Out
                            </DropdownLink>
                        </li>
                    </TransitionGroup>
                    <h2 class="mt-auto font-light text-slate-100 text-xs text-center">Created by Fajar Setiawan</h2>
                </div>
            </aside>
            
            <div class="w-full">
                <!-- Page Heading -->
                <header
                    class="bg-[linear-gradient(110deg,_#a855f7,_#ec4899,_#a855f7)] bg-[length:200%_200%] animate-gradientMove transition-all duration-1000 shadow md:mx-4 lg:mx-8 xl:mx-12 md:my-5 rounded-lg"
                    v-if="$slots.header"
                >
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
    overflow: hidden;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.slide-enter-to,
.slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
.menu-enter-active {
    transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}

.menu-enter-from {
    opacity: 0;
    transform: translateX(-30px);
}

.menu-enter-to {
    opacity: 1;
    transform: translateX(0);
}
</style>