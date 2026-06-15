<script setup>
import Loading from '@/Components/Loading.vue';
import Paginate from '@/Components/Paginate.vue';
import create from '@/Components/pembelian/create.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"
import { useCurrencyInput } from 'vue-currency-input'
import { computed } from 'vue';
import { formatTanggal } from '@/Components/composable/Tanggal.js';
import Update from '@/Components/pembelian/update.vue';
import Show from '@/Components/pembelian/show.vue';
import { tonaseview } from '@/Components/composable/Format.js';

const { date } = formatTanggal();

let timeout = null;
const menu = ref(null);
const isloading = ref(false);
const isloadedit = ref(false);
const isloadshow = ref(false);

const data = ref({
    id: '',
    tanggal: '',
    vendor: {},
    driver: {},
    payment: '',
    status: {},
    tonase: '',
    harga: '',
    size: '',
    mati: '',
    kompensasi: ''
})

//ref edit pembelian
const editvendor = ref(null)
const editdriver = ref(null)
const edithargapembelian = ref(null)
const edittonasepembelian = ref(null)
const editstatuspembelian = ref(null)
//ref edit penjualan
const editcustomer = ref(null)
const editstatuspenjualan = ref(null)
const edithargapenjualan = ref(null);
const edithargagp = ref(null);
const edittonasepenjualan = ref(null);
const edittonasegp = ref(null);
const editkasbon = ref(null);
const editbongkar = ref(null);
const editmati = ref(null);

const search = ref('');


const purchases = ref([]);
const purchas = ref({});
const vendors = ref([]);
const drivers = ref([]);
const paginate = ref({});
const statuses = ref([]);
const payments = ref([]);
const customers = ref([]);

const openModalCreate = ref(false);
const openModalUpdate = ref(false);
const openModalPreview = ref(false);

async function getpurchases(page = 1) {
    try{
        isloading.value = true
        const response = await axios.get('/api/transaksi/purchases', {
            params: {
                page,
                search: search.value
            }
        });
        purchases.value = response.data.purchases.data;
        paginate.value = response.data.purchases;
        vendors.value = response.data.vendors;
        drivers.value = response.data.drivers;
        statuses.value = response.data.statuses;
        payments.value = response.data.payments;
        customers.value = response.data.customers;
    }catch(error){
        Swal.fire({
            title: error.response.status,
            text: 'Terjadi kesalahan saat mengambil data pembelian 🚫',
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloading.value = false
    }
}

async function edit() {
    try{
        isloadedit.value = true
        form.value.tonase = parseNumber(form.value.tonase);
        const response = await axios.patch(`/api/transaksi/purchase/${form.value.id}`, form.value);
        openModalUpdate.value = false;
        reset();
        getpurchases();
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data pembayaran berhasil dirubah',
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

async function show() {
    try{
        isloadshow.value = true;
        const response = await axios.get(`/api/transaksi/purchase/${form.value.id}`);
        purchas.value = response.data.purchase;
        console.log(response.data.purchase);
    }catch(error){
        openModalPreview.value = false;
        reset();
        Swal.fire({
            title: error.response.status,
            text: 'Terjadi kesalahan saat mengambil data pembelian 🚫',
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloadshow.value = false
    }
}

function prev(page){
    getpurchases(page);
}
function next(page){
    getpurchases(page);
}
function page(page){
    getpurchases(page);
}

function detail(purchase){
    data.value.id = purchase.id;
    data.value.vendor = purchase.vendor;
    data.value.driver = purchase.driver;
    data.value.size = purchase.size;
    data.value.tonase = purchase.tonase;
    data.value.harga = purchase.harga;
    data.value.status = purchase.status;
    data.value.payment = purchase.methode;
    data.value.mati = purchase.mati;
    data.value.kompensasi = purchase.kompensasi;
    openModalUpdate.value = true;
}

function preview(purchase){
    openModalPreview.value = true
    form.value.id = purchase.id;
    show()
}

function reset(){
    data.value.id = '';
    data.value.vendor = {};
    data.value.driver = {};
    data.value.size = '';
    data.value.tonase = '';
    data.value.harga = '';
    data.value.status = {};
    data.value.payment = {};
    data.value.mati = '';
    data.value.kompensasi = '';
    purchas.value = [];
}

watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        getpurchases(1);
    }, 400);
});
function handleClickOutside() {
    menu.value = null
}
onBeforeUnmount(() => {
    window.removeEventListener('click', handleClickOutside)
})
onMounted(()=>{
    getpurchases();
    window.addEventListener('click', handleClickOutside);
});



//emit
function handleclosecreate(){
    openModalCreate.value = false;
}
function handlecloseupdate(){
    openModalUpdate.value = false;
    reset();
}
function handlesuccesupdate(value){
    openModalUpdate.value = false;
    reset();
    if(value){
        Swal.fire({
            toast: true,
            position: 'top-end', // kanan atas
            icon: 'success',
            title: 'Data berhasil disimpan',
            showConfirmButton: false,
            timer: 3000, // 3 detik
            timerProgressBar: true
        });
    }
    getpurchases();
}

</script>

<template>
    <Head title="Pembelian" />
    <AuthenticatedLayout>
        <Loading v-if="isloading" />
        <div v-else class="py-12">
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
                                Tambah Pembelian
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">#</th>
                                    <th scope="col" class="px-4 py-3">TANGGAL</th>
                                    <th scope="col" class="px-4 py-3">VENDOR</th>
                                    <th scope="col" class="px-4 py-3">SOPIR</th>
                                    <th scope="col" class="px-4 py-3">TONASE</th>
                                    <th scope="col" class="px-4 py-3">HARGA</th>
                                    <th scope="col" class="px-4 py-3">JUMLAH</th>
                                    <th scope="col" class="px-4 py-3">STATUS</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="purchases.length > 0">
                                    <tr v-for="(purchase, index) in purchases" :key="index" class="border-b dark:border-gray-700">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ paginate.from + index }}</th>
                                        <td class="px-4 py-3">{{ date(purchase.created_at) }}</td>
                                        <td class="px-4 py-3">{{ purchase.vendor.nama }}</td>
                                        <td class="px-4 py-3">{{ purchase.driver.nama }}</td>
                                        <td class="px-4 py-3">{{ tonaseview(Number(purchase.tonase)) }}</td>
                                        <td class="px-4 py-3">{{ Number(purchase.harga).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3">{{ Number(purchase.jumlah).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3" :class="purchase.status.status == 'Lunas' ? 'text-emerald-500' : 'text-red-500' ">{{ purchase.status.status }}</td>
                                        <td class="px-4 py-3 flex items-center justify-end">
                                            <button @click.stop="menu = menu === index ? null : index" class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none" type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div v-if="index === menu" class="z-10 w-44 absolute bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm" aria-labelledby="apple-imac-27-dropdown-button">
                                                    <li>
                                                        <button type="button" @click.stop="detail(purchase)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                            </svg>
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" @click.stop="preview(purchase)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                            </svg>
                                                            Preview
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="6" class="text-center">Data pembelian tidak ada</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <Paginate :paginate="paginate" @prev="prev" @next="next" @page="page" />
                </div>
            </div>

            <create v-if="openModalCreate" 
                        :vendors="vendors" 
                        :drivers="drivers" 
                        :statuses="statuses" 
                        :payments="payments" 
                        :customers="customers"
                        @close="handleclosecreate"
            />
            <Update v-if="openModalUpdate"
                    :vendors="vendors" 
                    :drivers="drivers" 
                    :statuses="statuses" 
                    :payments="payments"
                    :data="data"
                    @close="handlecloseupdate"
                    @succes="handlesuccesupdate"
            />
            <Show />
        </div>
    </AuthenticatedLayout>
</template>


