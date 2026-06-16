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
import { useCurrencyInput } from 'vue-currency-input'
import { computed } from 'vue';
import { Datepicker } from 'flowbite-datepicker';
import Update from '@/Components/penjualan/update.vue';

let timeout = null;
const menu = ref(null);
const isloading = ref(false);
const isloadcreate = ref(false);
const isloadedit = ref(false);
const isloadshow = ref(false);

const tabcreate = ref('');

const createcustomer = ref(null)
const createstatus = ref(null)
const createharga = ref(null);
const createhargagp = ref(null);
const createtonase = ref(null);
const createtonasegp = ref(null);
const createkasbon = ref(null);
const createbongkar = ref(null);
const createmati = ref(null);

const editcustomer = ref(null)
const editstatus = ref(null)
const editharga = ref(null);
const edithargagp = ref(null);
const edittonase = ref(null);
const edittonasegp = ref(null);
const editkasbon = ref(null);
const editbongkar = ref(null);
const editmati = ref(null);

const search = ref('');
const form = ref({
    id: '',
    tanggal: new Date().toISOString().split('T')[0],
    customer: {},
    tonase: null,
    tonasegp: null,
    harga: null,
    hargagp: null,
    kasbon: null,
    bongkar: null,
    mati: null,
    jumlah: null,
    titip: []
})
const jumlah = computed(() => {
    const harga = form.value.harga ? Number(String(form.value.harga).replace(/\./g, '')) : 0
    const hargagp = form.value.hargagp ? Number(String(form.value.hargagp).replace(/\./g, '')) : 0
    const kasbon = form.value.kasbon ? Number(String(form.value.kasbon).replace(/\./g, '')) : 0
    const bongkar = form.value.bongkar ? Number(String(form.value.bongkar).replace(/\./g, '')) : 0
    const tonase = form.value.tonase ? Number(String(form.value.tonase).replace(/\./g, '').replace(',', '.')) : 0
    const tonasegp = form.value.tonasegp ? Number(String(form.value.tonasegp).replace(/\./g, '').replace(',', '.')) : 0
    const mati = form.value.mati ? Number(String(form.value.mati).replace(/\./g, '').replace(',', '.')) : 0
    return (tonase * harga) + (tonasegp * hargagp) - kasbon - bongkar - mati
})
const err = ref({});

const salers = ref([]);
const saler = ref({});
const customers = ref([]);
const paginate = ref({});

const openModalCreate = ref(false);
const openModalUpdate = ref(false);
const openModalPreview = ref(false);

const openCustomerModal = ref(false);

async function getsalers(page = 1) {
    try{
        isloading.value = true
        const response = await axios.get('/api/transaksi/salers', {
            params: {
                page,
                search: search.value
            }
        });
        salers.value = response.data.salers.data;
        paginate.value = response.data.salers;
        customers.value = response.data.customers;
        console.log(response.data.salers);
    }catch(error){
        console.log(error?.response);
        Swal.fire({
            title: error.response.status,
            text: 'Terjadi kesalahan saat mengambil data penjualan 🚫',
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloading.value = false
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
    getsalers(page);
}
function next(page){
    getsalers(page);
}
function page(page){
    getsalers(page);
}
function detail(saler){
    form.value.id = saler.id;
    form.value.customer = saler.customer;
    form.value.harga = saler.harga;
    form.value.hargagp = saler.harga_gp;
    form.value.tonase = saler.tonase;
    form.value.tonasegp = saler.tonase_gp;
    form.value.kasbon = saler.kasbon;
    form.value.bongkar = saler.bongkar;
    form.value.mati = saler.mati;
    form.value.jumlah = saler.jumlah;
    form.value.titip = saler.titip ?? []
    openModalUpdate.value = true;
}
function preview(saler){
    openModalPreview.value = true
    form.value.id = saler.id;
    show()
}

function reset(){
    form.value.id = '';
    form.value.customer = {};
    form.value.harga = null;
    form.value.hargagp = null;
    form.value.tonase = null;
    form.value.tonasegp = null;
    form.value.kasbon = null;
    form.value.bongkar = null;
    form.value.mati = null;
    form.value.jumlah = null;
    form.value.titip = [];
    err.value = {};
    saler.value = [];
}
function handlecloseupdate(){
    openModalUpdate.value = false;
    reset();
}
function handlesuccesupdate(value){
    openCustomerModal.value = false;
    reset();
    if(value){
        Swal.fire({
            toast: true,
            position: 'top-end', // kanan atas
            icon: 'success',
            title: 'Data berhasil diubah',
            showConfirmButton: false,
            timer: 3000, // 3 detik
            timerProgressBar: true
        });
    }
    getsalers();
}
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        getsalers(1);
    }, 400);
});
function handleClickOutside() {
    menu.value = null
}
onBeforeUnmount(() => {
    window.removeEventListener('click', handleClickOutside)
})
onMounted(()=>{
    getsalers();
    window.addEventListener('click', handleClickOutside);
});
const customerUserClick = ref(false)
watch(openModalCreate, async (val) => {
    if (val) {
        reset()
        err.value = {}
        customerUserClick.value = false;
        await nextTick()
        createcustomer.value?.$el.querySelector('input')?.focus()
    }
})


//format tnaggal, uang dan ref nya
function formatHarga(e) {
    let value = e.target.value.replace(/\D/g, '')
    if (!value) {
        form.value.harga = ''
        return
    }
    form.value.harga = Number(value).toLocaleString('id-ID')
}
function formatHargaGP(e) {
    let value = e.target.value.replace(/\D/g, '')
    if (!value) {
        form.value.hargagp = ''
        return
    }
    form.value.hargagp = Number(value).toLocaleString('id-ID')
}
function formatTonase(e) {
    let value = e.target.value
    // hanya izinkan angka dan koma
    value = value.replace(/[^\d,]/g, '')
    // pisah integer dan decimal
    let parts = value.split(',')
    // format ribuan di bagian depan
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    // gabungkan lagi
    form.value.tonase = parts.join(',')
}
function formatTonaseGP(e) {
    let value = e.target.value
    // hanya izinkan angka dan koma
    value = value.replace(/[^\d,]/g, '')
    // pisah integer dan decimal
    let parts = value.split(',')
    // format ribuan di bagian depan
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    // gabungkan lagi
    form.value.tonasegp = parts.join(',')
}
function formatKasbon(e) {
    let value = e.target.value.replace(/\D/g, '')
    if (!value) {
        form.value.kasbon = ''
        return
    }
    form.value.kasbon = Number(value).toLocaleString('id-ID')
}
function formatBongkar(e) {
    let value = e.target.value.replace(/\D/g, '')
    if (!value) {
        form.value.bongkar = ''
        return
    }
    form.value.bongkar = Number(value).toLocaleString('id-ID')
}
function formatMati(e) {
    let value = e.target.value
    // hanya izinkan angka dan koma
    value = value.replace(/[^\d,]/g, '')
    // pisah integer dan decimal
    let parts = value.split(',')
    // format ribuan di bagian depan
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    // gabungkan lagi
    form.value.mati = parts.join(',')
}
function formatTitip(e, index) {
    let value = e.target.value.replace(/\D/g, '')

    if (!value) {
        form.value.titip[index].nominal = ''
        return
    }

    form.value.titip[index].nominal = Number(value).toLocaleString('id-ID')
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
}const formatTonaseview = (val) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(val);
};

</script>

<template>
    <Head title="Penjualan" />
    <AuthenticatedLayout>
        <Loading v-if="isloading" />
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
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">#</th>
                                    <th scope="col" class="px-4 py-3">TANGGAL</th>
                                    <th scope="col" class="px-4 py-3">CUSTOMER</th>
                                    <th scope="col" class="px-4 py-3">TONASE</th>
                                    <th scope="col" class="px-4 py-3">HARGA</th>
                                    <th scope="col" class="px-4 py-3">JUMLAH</th>
                                    <th scope="col" class="px-4 py-3">KASBON</th>
                                    <th scope="col" class="px-4 py-3">BONGKAR</th>
                                    <th scope="col" class="px-4 py-3">TITIP</th>
                                    <th scope="col" class="px-4 py-3">PIUTANG</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="salers.length > 0">
                                    <tr v-for="(saler, index) in salers" :key="index" class="border-b dark:border-gray-700">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ paginate.from + index }}</th>
                                        <td class="px-4 py-3">{{ formatDate(saler.tanggal) }}</td>
                                        <td class="px-4 py-3">{{ saler.customer.nama_depan }}</td>
                                        <td class="px-4 py-3">{{ formatTonaseview(Number(saler.tonase)) }}</td>
                                        <td class="px-4 py-3">{{ Number(saler.harga).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3">{{ Number(saler.jumlah).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3">{{ Number(saler.kasbon).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3">{{ Number(saler.bongkar).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3">{{ saler.titip ? saler.titip : '-' }}</td>
                                        <td class="px-4 py-3">{{ Number(saler.piutang).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-3 flex items-center justify-end">
                                            <button @click.stop="menu = menu === index ? null : index" class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none" type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div v-if="index === menu" class="z-10 w-44 absolute bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm" aria-labelledby="apple-imac-27-dropdown-button">
                                                    <li>
                                                        <button type="button" @click.stop="detail(saler)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                            </svg>
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" @click.stop="preview(saler)" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
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
                                        <td colspan="11" class="text-center">Data penjualan tidak ada</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <Paginate :paginate="paginate" @prev="prev" @next="next" @page="page" />
                </div>
                <Update v-if="openModalUpdate" :data="form" :customers="customers" @close="handlecloseupdate" @succes="handlesuccesupdate" />
            </div>
    </AuthenticatedLayout>
</template>
