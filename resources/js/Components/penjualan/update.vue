<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import vSelect from "vue-select";
import { nextTick } from 'vue';
import { computed } from "vue";
import { formatuanginput, clear, formattonaseinput } from "../composable/Format";
import Swal from "sweetalert2";
import CustomerCreate from "../pembelian/customerCreate.vue";

const emit = defineEmits(['succes', 'close']);
const props = defineProps(['data', 'customers']);
const customers = ref([]);
const customerSearch = ref('');
const triggerCustomerSearch = ref(false);
const openCustomerModal = ref(false);
const tabpenjualan = ref('penjualan');
const loading = ref(false);
const err = ref({});

//ref tag html
const customer = ref(null);
const tonasepenjualan = ref(null);
const hargapenjualan = ref(null);
const tonasegp = ref(null);
const hargagp = ref(null);
const matipenjualan = ref(null);
const kasbon = ref(null);
const bongkar = ref(null);

async function update() {
    try{
        loading.value = true;
        const response = await axios.patch(`/api/transaksi/penjualan/${form.value.id}`, {
            tanggal: form.value.penjualan.tanggal,
            customer: form.value.penjualan.customer.id,
            tonase: clear(form.value.penjualan.tonase),
            tonasegp: clear(form.value.penjualan.tonasegp),
            harga: clear(form.value.penjualan.harga),
            hargagp: clear(form.value.penjualan.hargagp),
            kasbon: clear(form.value.penjualan.kasbon),
            bongkar: clear(form.value.penjualan.bongkar),
            mati: clear(form.value.penjualan.mati),
            titip: form.value.penjualan.titip
        });
    }catch(error){
        err.value = error.response.data.errors;
        Swal.fire({
            title: error.response.status,
            text: error.response.data.message,
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        loading.value = false;
    }
}
function closemodal(){
    emit("close");
}
const form = ref({
    id: '',
    tanggal: new Date().toISOString().split('T')[0],
    customer: '',
    tonase: '',
    tonasegp: '',
    harga: '',
    hargagp: '',
    kasbon: '',
    bongkar: '',
    mati: '',
    titip: []
});
function handleaddtitipan(){
    form.value.titip.push({
        nominal: '',
        tanggal: '',
    });
}

//focus v-select
async function focuscustomer() {
    await nextTick()
    customer.value?.$el.querySelector('input')?.focus();   
}

function dropdownCustomer() {
        return triggerCustomerSearch.value;
    }
const finalCustomers = computed(() => {
    if (!triggerCustomerSearch.value) return []

    return customers.value.filter(v =>
        v.nama_depan.toLowerCase().includes(customerSearch.value.toLowerCase())
    )
})

const handleinputtonasenrpenjualan = (e)=>{
    form.value.tonase = formattonaseinput(e.target.value)
}
const handleinputtonasegppenjualan = (e)=>{
    form.value.tonasegp = formattonaseinput(e.target.value)
}
const handleinputharganrpenjualan = (e)=>{
    form.value.harga = formattonaseinput(e.target.value)
}
const handleinputhargagppenjualan = (e)=>{
    form.value.hargagp = formattonaseinput(e.target.value)
}
const handleinputmatipenjualan = (e)=>{
    form.value.mati = formattonaseinput(e.target.value)
}
const handleinputkasbonpenjualan = (e)=>{
    form.value.kasbon = formatuanginput(e.target.value)
}
const handleinputbongkarpenjualan = (e)=>{
    form.value.bongkar = formatuanginput(e.target.value)
}
const handleinputtitip = (e, index)=>{
    form.value.titip[index].nominal = formatuanginput(e.target.value)
}

//Vseelct customer
function handleEnterCustomer() {
    const results = customers.value.filter(v =>
        v.nama_depan.toLowerCase().includes(customerSearch.value.toLowerCase())
    )
    if (results.length > 0) {
        form.value.customer = results[0] // ✅ object
        triggerCustomerSearch.value = false
        customerSearch.value = ''
        tonasepenjualan.value?.focus();
    } else {
        triggerCustomerSearch.value = true
    }
}

//emit komponen
function handleberhasilcustomer(newcustomer){
    customers.value.push(newcustomer);
    focuscustomer();
    openCustomerModal.value = false;
}
function handleclosecustomer(){
    focuscustomer();
    openCustomerModal.value = false;
}

onMounted(()=>{
    customers.value = props.customers;
    focuscustomer();
    form.value.id = props.data.id;
    form.value.customer = customers.value.find(c => c.id == props.data.customer.id);
    form.value.tonase = props.data.tonase;
    form.value.tonasegp = props.data.tonasegp;
    form.value.harga = props.data.harga;
    form.value.hargagp = props.data.hargagp;
    form.value.bongkar = props.data.bongkar;
    form.value.kasbon = props.data.kasbon;
    form.value.mati = props.data.mati;
});
</script>

<template>
    <div class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <div class="pb-2 rounded-t border-b">
                    <div class="flex justify-between items-center sm:mb-2 dark:border-gray-600">
                        <div class="overflow-hidden max-h-[1.5em]">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Penjualan</h3>
                        </div>
                        <button type="button" @click="closemodal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <button type="button" class="max-w-max inline-block text-sm text-slate-900" @click="tabpenjualan = 'penjualan'" :class="tabpenjualan == 'penjualan' ? 'opacity-100' : 'opacity-50'">Penjualan</button>
                    <span class="text-sm mx-1 transition-all duration-500">{{ tabpenjualan == 'penjualan' ? '>>' : '<<' }}</span>
                    <button type="button" class="max-w-max inline-block text-sm text-slate-900" @click="tabpenjualan = 'titip'" :class="tabpenjualan == 'titip' ? 'opacity-100' : 'opacity-50'">Titip</button>
                </div>
                <form @submit.prevent="update">
                    <div class="flex py-4 overflow-hidden">
                        <Transition name="slide-side">
                            <div v-if="tabpenjualan == 'penjualan'" class="grid gap-4 mb-4 min-w-full px-1">
                                    <div>
                                        <div class="relative">
                                            <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tanggal</label>
                                            <input ref="tanggal" @keydown.enter.prevent="focuscustomer" v-model="form.tanggal" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        </div>
                                        <p v-if="err?.['tanggal']" class="text-red-500 text-xs">{{ err?.['tanggal'][0] }}</p>
                                    </div>
                                    <div class="relative">
                                        <vSelect
                                            ref="customer"
                                            v-model="form.customer"
                                            :options="finalCustomers"
                                            label="nama_depan"
                                            :filterable="false"
                                            :open-on-focus="false"
                                            :dropdownShouldOpen="dropdownCustomer"
                                            @search="val => {
                                                customerSearch = val
                                                triggerCustomerSearch = false
                                            }"
                                            @keydown.enter.prevent="handleEnterCustomer"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        >
                                            <template #no-options>
                                                <div class="text-sm p-2">
                                                    <button
                                                        v-if="triggerCustomerSearch && customerSearch"
                                                        type="button"
                                                        class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                                        @click="openCustomerModal = true"
                                                    >
                                                        + Tambah Customer
                                                    </button>
                                                </div>
                                            </template>
                                        </vSelect>
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Nama Customer</label>
                                        <p v-if="err?.['customer']" class="text-red-500 text-xs">{{ err?.['customer'][0] }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-4">
                                        <div>
                                            <div class="relative">
                                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tonase NR</label>
                                                <input ref="tonasepenjualan" @keydown.enter.prevent="hargapenjualan.focus()" @input="handleinputtonasenrpenjualan" v-model="form.tonase" type="text" name="brand" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                            <p v-if="err?.['tonase']" class="text-red-500 text-xs">{{ err?.['tonase'][0] }}</p>
                                        </div>
                                        <div>
                                            <div class="relative">
                                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Harga NR</label>
                                                <input ref="hargapenjualan" @keydown.enter.prevent="tonasegp.focus()" @input="handleinputharganrpenjualan" v-model="form.harga" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                            <p v-if="err?.['harga']" class="text-red-500 text-xs">{{ err?.['harga'][0] }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-4">
                                        <div>
                                            <div class="relative">
                                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tonase GP</label>
                                                <input ref="tonasegp" @keydown.enter.prevent="hargagp.focus()" @input="handleinputtonasegppenjualan" v-model="form.tonasegp" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                            <p v-if="err?.['tonasegp']" class="text-red-500 text-xs">{{ err?.['tonasegp'][0] }}</p>
                                        </div>
                                        <div>
                                            <div class="relative">
                                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Harga GP</label>
                                                <input ref="hargagp" @keydown.enter.prevent="matipenjualan.focus()" @input="handleinputhargagppenjualan" v-model="form.hargagp" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                            <p v-if="err?.['hargagp']" class="text-red-500 text-xs">{{ err?.['hargagp'][0] }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Potong Mati</label>
                                            <input ref="matipenjualan" @keydown.enter.prevent="kasbon.focus()" @input="handleinputmatipenjualan" v-model="form.mati" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        </div>
                                        <p v-if="err?.['mati']" class="text-red-500 text-xs">{{ err?.['mati'][0] }}</p>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Kasbon</label>
                                            <input ref="kasbon" @keydown.enter.prevent="bongkar.focus()" @input="handleinputkasbonpenjualan" v-model="form.kasbon" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        </div>
                                        <p v-if="err?.['kasbon']" class="text-red-500 text-xs">{{ err?.['kasbon'][0] }}</p>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Bongkar</label>
                                            <input ref="bongkar" @keydown.enter.prevent="create" @input="handleinputbongkarpenjualan" v-model="form.bongkar" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        </div>
                                        <p v-if="err?.['bongkar']" class="text-red-500 text-xs">{{ err?.['bongkar'][0] }}</p>
                                    </div>
                            </div>
                            <div v-else class="pb-4 border-b px-1">
                                <button type="button" @click="handleaddtitipan" class="px-3 py-2 mb-3 rounded-lg bg-sky-400 text-white hover:bg-sky-500 active:ring-1 active:ring-sky-300">Tambah titipan</button>
                                <div v-if="form.titip.length > 0" class="mt-2">
                                    <div v-for="(titip, index) in form.titip" :key="index" class=" my-4">
                                        <div class="grid grid-cols-2 gap-x-4">
                                            <div>
                                                <div class="relative">
                                                    <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Titip {{ index + 1 }}</label>
                                                </div>
                                                <input @input="e => handleinputtitip(e, index)" v-model="form.titip[index].nominal" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <p v-if="err?.[`titip.${[index]}.nominal`]" class="text-red-500 text-xs">{{ err?.[`titip.${[index]}.nominal`][0] == `titip.${[index]}.nominal wajib diisi` ? `nominal titipan ke ${index + 1} wajib diisi`  : `nominal titipan ke ${index + 1} berupa desimal`}}</p>
                                            </div>
                                            <div>
                                                <div class="relative">
                                                    <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tanggal</label>
                                                    <input type="date" v-model="form.titip[index].tanggal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                </div>
                                                <p v-if="err?.[`titip.${[index]}.tanggal`]" class="text-red-500 text-xs">{{ err?.[`titip.${[index]}.tanggal`][0] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                    <div class="grid grid-cols-2 gap-x-5">
                        <div>
                            <button type="submit" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 active::ring-4 active::outline-none active::ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:active::ring-primary-800">
                               {{ loading ? 'Mengedit' : 'Edit'}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <CustomerCreate v-if="openCustomerModal" @berhasil="handleberhasilcustomer" @close="handleclosecustomer" />
</template>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.25s ease-out;
}

.slide-up-enter-from {
  opacity: 0;
  transform: translateY(30px);
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}

.slide-side-enter-active,
.slide-side-leave-active {
  transition: all 0.5s ease-out;
}

.slide-side-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.slide-side-leave-to {
  opacity: 0;
  transform: translateX(-100%);
}

.slide-left-enter-active,
.slide-left-leave-active {
  transition: all 0.5s ease-out;
}

.slide-left-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.slide-left-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>