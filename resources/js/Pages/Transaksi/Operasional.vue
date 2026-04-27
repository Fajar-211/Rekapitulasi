<script setup>
import Loading from '@/Components/Loading.vue';
import Paginate from '@/Components/Paginate.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"
import { computed } from 'vue';

let timeout = null;
const menu = ref(null);
const isloading = ref(false);
const isloaddelete = ref(false);
const isloadcreate = ref(false);
const isloadedit = ref(false);
const isloadshow = ref(false);

const createoperasional = ref(null)
const createjumlah = ref(null)

const editoperasional = ref(null)
const editjumlah = ref(null)

const search = ref('');
const form = ref({
    id: '',
    tanggal: new Date().toISOString().split('T')[0],
    type: '',
    jumlah: null,
})
const err = ref({});

const operasionals = ref([]);
const operasional = ref({});
const type = ref([]);
const paginate = ref({});

const openModalCreate = ref(false);
const openModalUpdate = ref(false);
const openModalDelete = ref(false);
const openModalPreview = ref(false);

const openTypeModal = ref(false);

async function getoperasionals(page = 1) {
    try{
        isloading.value = true
        const response = await axios.get('/api/transaksi/operasionals', {
            params: {
                page,
                search: search.value
            }
        });
        operasionals.value = response.data.operasionals.data;
        paginate.value = response.data.operasionals;
        type.value = response.data.type;
    }catch(error){
        Swal.fire({
            title: error.response.status,
            text: 'Terjadi kesalahan saat mengambil data operasional 🚫',
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloading.value = false
    }
}
async function create() {
    try{
        isloadcreate.value = true;
        form.value.jumlah = parseNumber(form.value.jumlah);
        const response = await axios.post('/api/transaksi/operasional', form.value);
        reset();
        await nextTick()
        createoperasional.value?.$el.querySelector('input')?.focus()
        //getcustomers();
        console.log(response.data.message);
    }catch(error){
        console.log(error.response?.data);
        err.value = error.response?.data?.errors ?? {}
        Swal.fire({
            title: error.response?.status,
            text: error.response?.data?.message,
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloadcreate.value = false
    }
}
async function edit() {
    try{
        isloadedit.value = true
        form.value.jumlah = parseNumber(form.value.jumlah);
        const response = await axios.patch(`/api/transaksi/operasional/${form.value.id}`, form.value);
        openModalUpdate.value = false;
        reset();
        getoperasionals();
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data operasional berhasil dirubah',
            icon: 'success',
            confirmButtonText: 'OK'
        })
    }catch(error){
        err.value = error.response.data.errors;
        Swal.fire({
            title: error.response.status,
            text: error.response.data.message,
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloadedit.value = false
    }
}
async function hapus() {
    try{
        isloaddelete.value = true
        const response = await axios.delete(`/api/transaksi/operasional/${form.value.id}`);
        Swal.fire({
            title: 'Berhasil!',
            text: 'Berhasil menghapus operasional',
            icon: 'success',
            confirmButtonText: 'OK'
        })
        openModalDelete.value = false;
        reset();
        getoperasionals();
        //console.log(response.data);
    }catch(error){
        Swal.fire({
            title: 'Gagal!',
            text: `Kode error ${error.response.status}`,
            icon: 'error',
            confirmButtonText: 'Coba lagi nanti'
        })
        openModalDelete.value = false;
        reset();
        //console.log(error.response);
    }finally{
        isloaddelete.value = false
    }
}
async function show() {
    try{
        isloadshow.value = true;
        const response = await axios.get(`/api/transaksi/saler/${form.value.id}`);
        saler.value = response.data.saler;
        console.log(response.data.saler);
    }catch(error){
        openModalPreview.value = false;
        reset();
        Swal.fire({
            title: error.response.status,
            text: 'Terjadi kesalahan saat mengambil data penjualan 🚫',
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloadshow.value = false
    }
}

function prev(page){
    getoperasionals(page);
}
function next(page){
    getoperasionals(page);
}
function page(page){
    getoperasionals(page);
}

function detail(operasional){
    form.value.id = operasional.id;
    form.value.type = operasional.type.id;
    form.value.jumlah = operasional.jumlah;
    openModalUpdate.value = true;
}
function del(operasional){
    form.value.id = operasional.id;
    openModalDelete.value = true
}
function preview(operasional){
    openModalPreview.value = true
    form.value.id = operasional.id;
    show()
}

function reset(){
    form.value.id = '';
    form.value.type = '';
    form.value.jumlah = null;
    err.value = {};

    //type.value = [];
}

//tipe operasional
const createtype = ref(null)
async function handleopentype(){
    openTypeModal.value = true;
    await nextTick()
    type.value?.focus();
}
function handleclosetype(){
    openTypeModal.value = false;
    resettype();
}
function resettype(){
    formtype.value.type = '';
    createtype.value = null;
}
const formtype = ref({
    type: '',
})
const errtype = ref({});
const isloadtype = ref(false);
async function createtypeform() {
    try{
        isloadtype.value = true;
        const response = await axios.post('/api/master/type', formtype.value);
        handleclosetype()
        type.value.push(response.data.type);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Berhasil membuat Tipe Operasional',
            showConfirmButton: false,
            timer: 2000
        })
        await nextTick()
        createtype.value?.$el.querySelector('input')?.focus()
    }catch(error){
        console.log(error);
        errtype.value = error.response.data?.errors ?? {};
        Swal.fire({
            title: error.response.status,
            text: error.response.data?.message,
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloadtype.value = false;
    }
}

watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        getoperasionals(1);
    }, 400);
});
function handleClickOutside() {
    menu.value = null
}
onBeforeUnmount(() => {
    window.removeEventListener('click', handleClickOutside)
})
onMounted(()=>{
    getoperasionals();
    window.addEventListener('click', handleClickOutside);
});
const typeUserClick = ref(false)
watch(openModalCreate, async (val) => {
    if (val) {
        reset()
        err.value = {}
        typeUserClick.value = false;
        await nextTick()
        createoperasional.value?.$el.querySelector('input')?.focus()
    }
})
function dropdownCustomer() {
    return typeUserClick.value
}

watch(openModalUpdate, async (val) => {
    if (val) {
        await nextTick()
        editoperasional.value?.$el.querySelector('input')?.focus()
    }
})

//format tnaggal, uang dan ref nya
function formatJumlah(e) {
    let value = e.target.value.replace(/\D/g, '')
    if (!value) {
        form.value.jumlah = ''
        return
    }
    form.value.jumlah = Number(value).toLocaleString('id-ID')
}
function parseNumber(value) {
    if (!value) return null
    return Number(
        String(value)
            .replace(/\./g, '')  // hapus titik ribuan
            .replace(',', '.')   // ubah koma jadi titik
    )
}
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}
</script>

<template>
    <Head title="Penjualan" />
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Operasional
            </h2>
        </template>

        <div class="mx-auto max-w-screen-xl px-4 lg:px-12 mt-10">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input v-model="search" type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-cyan-500 block w-full pl-10 p-2" placeholder="Search">
                                </div>
                            </form>
                        </div>
                        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button type="button" @click="openModalCreate = true" class="flex items-center justify-center text-white bg-cyan-400 hover:bg-cyan-600 focus:ring-2 focus:ring-cyan-200 font-medium rounded-lg text-sm px-4 py-2 transition-all duration-300">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Add Operasional
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">#</th>
                                    <th scope="col" class="px-4 py-3">TANGGAL</th>
                                    <th scope="col" class="px-4 py-3">JENIS BIAYA</th>
                                    <th scope="col" class="px-4 py-3">JUMLAH</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="operasionals.length > 0">
                                    <tr v-for="(operasional, index) in operasionals" :key="index" class="border-b dark:border-gray-700">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ paginate.from + index }}</th>
                                        <td class="px-4 py-3">{{ formatDate(operasional.created_at) }}</td>
                                        <td class="px-4 py-3">{{ operasional.type.type }}</td>
                                        <td class="px-4 py-3">{{ Number(operasional.jumlah).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3 flex items-center justify-end">
                                            <button @click.stop="menu = menu === index ? null : index" class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none" type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div v-if="index === menu" class="z-10 w-44 absolute bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm" aria-labelledby="apple-imac-27-dropdown-button">
                                                    <li>
                                                        <button type="button" @click.stop="detail(operasional)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                            </svg>
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <!-- <li>
                                                        <button type="button" @click.stop="preview(operasional)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            Preview
                                                        </button>
                                                    </li> -->
                                                    <li>
                                                        <button type="button" @click="del(operasional)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-500 dark:hover:text-red-400">
                                                            <svg class="w-4 h-4 mr-2" viewbox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06631 0.30087 7.89922 0.300781H6.09922ZM4.29922 5.70078C4.29922 5.46209 4.39404 5.23317 4.56282 5.06439C4.73161 4.8956 4.96052 4.80078 5.19922 4.80078C5.43791 4.80078 5.66683 4.8956 5.83561 5.06439C6.0044 5.23317 6.09922 5.46209 6.09922 5.70078V11.1008C6.09922 11.3395 6.0044 11.5684 5.83561 11.7372C5.66683 11.906 5.43791 12.0008 5.19922 12.0008C4.96052 12.0008 4.73161 11.906 4.56282 11.7372C4.39404 11.5684 4.29922 11.3395 4.29922 11.1008V5.70078ZM8.79922 4.80078C8.56052 4.80078 8.33161 4.8956 8.16282 5.06439C7.99404 5.23317 7.89922 5.46209 7.89922 5.70078V11.1008C7.89922 11.3395 7.99404 11.5684 8.16282 11.7372C8.33161 11.906 8.56052 12.0008 8.79922 12.0008C9.03791 12.0008 9.26683 11.906 9.43561 11.7372C9.6044 11.5684 9.69922 11.3395 9.69922 11.1008V5.70078C9.69922 5.46209 9.6044 5.23317 9.43561 5.06439C9.26683 4.8956 9.03791 4.80078 8.79922 4.80078Z" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="6" class="text-center">Data Operasional tidak ada</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <Paginate :paginate="paginate" @prev="prev" @next="next" @page="page" />
                </div>
            </div>

            <!-- Create modal -->
            <div v-if="openModalCreate" tabindex="-1" aria-hidden="true" class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Operasional</h3>
                            <button type="button" @click="openModalCreate = false; reset(); getoperasionals()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="createProductModal" data-modal-toggle="createProductModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form @submit.prevent="create">
                            <div class="grid grid-cols-1 mb-4 gap-4">
                                <div>
                                    <div class="relative">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tanggal</label>
                                        <input v-model="form.tanggal" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Tonase">
                                    </div>
                                    <p v-if="err.jumlah" class="text-xs text-red-400">{{ err.jumlah[0] }}</p>
                                </div>
                                <div class="relative">
                                    <vSelect
                                        ref="createoperasional"
                                        @option:selected="createjumlah.focus()"
                                        v-model="form.type"
                                        :options="type"
                                        label="type"
                                        :reduce="t => t.id"
                                        :open-on-focus="false"
                                        :dropdownShouldOpen="dropdownCustomer"
                                        @mousedown="typeUserClick = true"
                                        @close="typeUserClick = false"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    >
                                        <template #no-options>
                                            <div class="text-sm p-2">
                                                <button
                                                    type="button"
                                                    class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                                    @click="handleopentype"
                                                >
                                                    + Tambah Tipe Operaional
                                                </button>
                                            </div>
                                        </template>
                                    </vSelect>
                                    <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Jenis Operasional</label>
                                </div>
                                <div>
                                    <div class="relative">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Jumlah</label>
                                        <input ref="createjumlah" @keydown.enter.prevent="create" @input="formatJumlah" v-model="form.jumlah" type="text" name="brand" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    </div>
                                    <p v-if="err.jumlah" class="text-xs text-red-400">{{ err.jumlah[0] }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-x-5">
                                <div>
                                    <button type="submit" :disabled="isloadcreate" :class="isloadcreate ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ isloadcreate ? 'Menambah' : 'Tambah baru' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Update modal -->
            <div v-if="openModalUpdate" tabindex="-1" aria-hidden="true" class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Operational</h3>
                            <button type="button" @click.stop="openModalUpdate = false; reset" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="updateProductModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form @submit.prevent="edit">
                            <div class="grid gap-4 mb-4">
                                <div class="grid grid-cols-3">
                                    <div>
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                                    </div>
                                    <div class="col-span-2">
                                        <vSelect
                                            ref="editoperasional"
                                            @option:selected="editjumlah.focus()"
                                            v-model="form.type"
                                            :options="type"
                                            label="type"
                                            :reduce="c => c.id"
                                            :open-on-focus="false"
                                            :dropdownShouldOpen="dropdownCustomer"
                                            @mousedown="typeUserClick = true"
                                            @close="typeUserClick = false"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        >
                                            <template #no-options>
                                                <div class="text-sm p-2">
                                                    <button
                                                        type="button"
                                                        class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                                        @click="handleopentype"
                                                    >
                                                        + Tambah Jenis Operasional
                                                    </button>
                                                </div>
                                            </template>
                                        </vSelect>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3">
                                    <div>
                                        <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input ref="editjumlah" @keydown.enter.prevent="edit" @input="formatJumlah" v-model="form.jumlah" type="text" name="brand" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Tonase">
                                        <p v-if="err.jumlah" class="text-xs text-red-400">{{ err.jumlah[0] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-x-5">
                                <div>
                                    <button type="submit" :disabled="isloadedit" :class="isloadedit ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ isloadedit ? 'Mengedit' : 'Edit' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete modal -->
            <div v-if="openModalDelete" tabindex="-1" aria-hidden="true" class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <button type="button" @click.stop="openModalDelete = false; reset" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="deleteModal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="mb-4 text-gray-500 dark:text-gray-300">Are you sure you want to delete this item?</p>
                        <form @submit.prevent="hapus">
                            <div class="flex justify-center items-center space-x-4">
                                <button @click="openModalDelete = false; reset" type="button" class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Tidak, batal</button>
                                <button type="submit" :disabled="isloaddelete" :class="isloaddelete ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">{{ isloaddelete ? 'Menghapus' : 'Ya, saya yakin' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Create type -->
            <div v-if="openTypeModal" tabindex="-1" aria-hidden="true" class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Operational Type</h3>
                            <button type="button" @click="openTypeModal = false; resettype" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="createProductModal" data-modal-toggle="createProductModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form @submit.prevent="createtypeform">
                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Operasional</label>
                                    <input ref="createtype" @keydown.enter.prevent="createtypeform" v-model="formtype.type" type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama depan">
                                    <p v-if="errtype.type" class="text-xs text-red-400">{{ errtype.type[0] }}</p>
                                </div>
                            </div>
                            <button type="submit" :disabled="isloadtype" :class="isloadtype ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                {{ isloadtype ? 'Menambah' : 'Tambah baru' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </AuthenticatedLayout>
</template>
