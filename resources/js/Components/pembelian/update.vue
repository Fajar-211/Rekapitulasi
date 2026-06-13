<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import vSelect from "vue-select"
import VendorCreate from "./vendorCreate.vue";
import Drivercreate from "./drivercreate.vue";
import Pembayaran from "./pembayaran.vue";
import CustomerCreate from "./customerCreate.vue";
import { nextTick } from 'vue';
import { computed } from "vue";
import { formatuanginput, clear, formattonaseinput } from "../composable/Format";
import Swal from "sweetalert2";

const props = defineProps(['vendors', 'drivers', 'statuses', 'payments', 'data']);
const emit = defineEmits(['close', 'succes']);
const isloadcreate = ref(false);
//data Vselect
const vendors = ref([]);
const drivers = ref([]);
const statuses = ref([]);
const payments = ref([]);

//reactive komponen tambahan jika data Vselect tidak ada
const openVendorModal = ref(false);
const openDriverModal = ref(false);
const openPaymentModal = ref(false);

const form = ref({
    pembelian: {
        id: '',
        tanggal: new Date().toISOString().split('T')[0],
        vendor: '',
        driver: '',
        size: '',
        tonase: '',
        harga: '',
        status: '',
        pembayaran: '',
        mati: '',
        kompensasi: ''
    }
})
const err = ref({});
function reset(){
    form.value.pembelian.vendor = '';
    form.value.pembelian.driver = '';
    form.value.pembelian.size = null;
    form.value.pembelian.tonase = null;
    form.value.pembelian.harga = null;
    form.value.pembelian.status = '';
    form.value.pembelian.pembayaran = '';
    form.value.pembelian.mati = null;
    form.value.pembelian.kompensasi = null;

    err.value = {};

    vendor.value = null;
    driver.value = null;
    size.value = null;
    tonasepembelian.value = null;
    hargapembelian.value = null;
    statuspembelian.value = null;
    pembayaran.value = null;
    matipembelian.value = null;
    kompensasi.value = null;
}
//ref penamaan tag html
    //ref pembelian
    const vendor = ref(null);
    const driver = ref(null);
    const size = ref(null);
    const tonasepembelian = ref(null);
    const hargapembelian = ref(null);
    const statuspembelian = ref(null);
    const pembayaran = ref(null);
    const matipembelian = ref(null);
    const kompensasi = ref(null);

//post API
async function create() {
    try{
        isloadcreate.value = true;
        const response = await axios.patch(`/api/transaksi/pembelian/${form.value.pembelian.id}`, {
            tanggal: form.value.pembelian.tanggal,
            vendor: form.value.pembelian.vendor.id,
            driver: form.value.pembelian.driver.id,
            size: clear(form.value.pembelian.size),
            tonase: clear(form.value.pembelian.tonase),
            harga: clear(form.value.pembelian.harga),
            status: form.value.pembelian.status.id,
            pembayaran: form.value.pembelian.pembayaran.id,
            mati: clear(form.value.pembelian.mati),
            kompensasi: clear(form.value.pembelian.kompensasi)
        });
        reset();
        focusvendor()
        console.log(response.data.message);
    }catch(error){
        err.value = error?.response?.data?.errors
        console.log(err.value);
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

//close modal create
function closemodal(){
    reset();
    emit("close");
}

//jalankan fokus komponen form (Vselect)
async function focusvendor() {
    await nextTick()
    vendor.value?.$el.querySelector('input')?.focus();   
}
async function focusdriver() {
    await nextTick()
    driver.value?.$el.querySelector('input')?.focus();   
}
async function focusstatuspembelian() {
    await nextTick()
    statuspembelian.value?.$el.querySelector('input')?.focus();   
}
async function focuspembayaran() {
    await nextTick()
    pembayaran.value?.$el.querySelector('input')?.focus();   
}

//pencarian dan trigger pada vue
const vendorSearch = ref('');
const triggerVendorSearch = ref(false);
const driverSearch = ref('');
const triggerdriverSearch = ref(false);
const statusSearch = ref('');
const triggerStatusSearch = ref(false);
const pembayaranSearch = ref('');
const triggerPembayaranSearch = ref(false);

//handle enter Vselect
    //Vseelct vendor
    function handleEnterVendor() {
        const results = vendors.value.filter(v =>
            v.nama.toLowerCase().includes(vendorSearch.value.toLowerCase())
        )
        if (results.length > 0) {
            form.value.pembelian.vendor = results[0] // ✅ object
            triggerVendorSearch.value = false
            vendorSearch.value = ''
            focusdriver();
        } else {
            triggerVendorSearch.value = true
        }
    }
    //Vseelct driver
    function handleEnterDriver() {
        const results = drivers.value.filter(v =>
            v.nama.toLowerCase().includes(driverSearch.value.toLowerCase())
        )
        if (results.length > 0) {
            form.value.pembelian.driver = results[0] // ✅ object
            triggerdriverSearch.value = false
            driverSearch.value = ''
            size.value?.focus();
        } else {
            triggerdriverSearch.value = true
        }
    }
    //Vseelct status
    function handleEnterStatus() {
        const results = statuses.value.filter(v =>
            v.status.toLowerCase().includes(statusSearch.value.toLowerCase())
        )
        if (results.length > 0) {
            form.value.pembelian.status = results[0] // ✅ object
            triggerStatusSearch.value = false
            statusSearch.value = ''
            focuspembayaran();
        } else {
            triggerStatusSearch.value = true
        }
    }
    //Vseelct pembayaran
    function handleEnterPembayaran() {
        const results = payments.value.filter(v =>
            v.methode.toLowerCase().includes(pembayaranSearch.value.toLowerCase())
        )
        if (results.length > 0) {
            form.value.pembelian.pembayaran = results[0] // ✅ object
            triggerPembayaranSearch.value = false
            pembayaranSearch.value = ''
            matipembelian.value?.focus();
        } else {
            triggerPembayaranSearch.value = true
        }
    }

//lain lain terkait Vselect
    //vendor
    function dropdownVendor() {
        return triggerVendorSearch.value
    }
    const finalVendors = computed(() => {
        if (!triggerVendorSearch.value) return []

        return vendors.value.filter(v =>
            v.nama.toLowerCase().includes(vendorSearch.value.toLowerCase())
        )
    })
    //driver
    function dropdownDriver() {
        return triggerdriverSearch.value
    }
    const finalDrivers = computed(() => {
        if (!triggerdriverSearch.value) return []

        return drivers.value.filter(v =>
            v.nama.toLowerCase().includes(driverSearch.value.toLowerCase())
        )
    })
    //status pembelian
    function dropdownStatus() {
        return triggerStatusSearch.value
    }
    const finalStatuses = computed(() => {
        if (!triggerStatusSearch.value) return []

        return statuses.value.filter(v =>
            v.status.toLowerCase().includes(statusSearch.value.toLowerCase())
        )
    })
    //cara pembayaran
    function dropdownPembayaran() {
        return triggerPembayaranSearch.value
    }
    const finalPembayaran = computed(() => {
        if (!triggerPembayaranSearch.value) return []

        return payments.value.filter(v =>
            v.methode.toLowerCase().includes(pembayaranSearch.value.toLowerCase())
        )
    })

//emit perkomponen
    //vendor
    function handleberhasilvendor(newvendor){
        vendors.value.push(newvendor);
        focusvendor();
        openVendorModal.value = false;
    }
    function handleclosevendor(){
        focusvendor();
        openVendorModal.value = false;
    }
    //driver
    function handleberhasildriver(newdriver){
        drivers.value.push(newdriver);
        focusdriver();
        openDriverModal.value = false;
    }
    function handleclosedriver(){
        focusdriver();
        openDriverModal.value = false;
    }
    //pembayaran
    function handleberhasilmethode(newmethode){
        payments.value.push(newmethode);
        focuspembayaran();
        openPaymentModal.value = false;
    }
    function handleclosemethode(){
        focuspembayaran();
        openPaymentModal.value = false;
    }

//handle input
    const handleinputhargapembelian = (e)=>{
        form.value.pembelian.harga = formatuanginput(e.target.value)
    }
    const handleinputsize = (e)=>{
        form.value.pembelian.size = formattonaseinput(e.target.value)
    }
    const handleinputonasepembelian = (e)=>{
        form.value.pembelian.tonase = formattonaseinput(e.target.value)
    }
    const handleinputklaimmatipembelian = (e)=>{
        form.value.pembelian.mati = formattonaseinput(e.target.value)
    }
    const handleinputkompensasipembelian = (e)=>{
        form.value.pembelian.kompensasi = formatuanginput(e.target.value)
    }

onMounted(()=>{
    focusvendor();
    vendors.value = props.vendors;
    drivers.value = props.drivers;
    statuses.value = props.statuses;
    payments.value = props.payments;
    form.value.pembelian.id = props.data.id;
    form.value.pembelian.driver = drivers.value.find(d => d.id == props.data.driver.id);
    form.value.pembelian.vendor = vendors.value.find(v => v.id == props.data.vendor.id);
    form.value.pembelian.size = props.data.size;
    form.value.pembelian.tonase = props.data.tonase;
    form.value.pembelian.harga = props.data.harga;
    form.value.pembelian.status = statuses.value.find(s => s.id == props.data.status.id);
    form.value.pembelian.pembayaran = payments.value.find(p => p.id == props.data.payment.id);
    form.value.pembelian.mati = props.data.mati;
    form.value.pembelian.kompensasi = props.data.kompensasi;
});
</script>

<template>
    <div class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <div class="pb-2 rounded-t border-b">
                    <div class="flex justify-between items-center sm:mb-2 dark:border-gray-600">
                        <div class="overflow-hidden max-h-[1.5em]">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pembelian</h3>
                        </div>
                        <button type="button" @click="closemodal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="create">
                    <div class="flex py-4 overflow-hidden">
                        <div class="grid gap-5 grid-cols-1 min-w-full px-1">
                            <div>
                                <div class="relative">
                                    <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tanggal</label>
                                    <input ref="tanggal" @keydown.enter.prevent="focusvendor" v-model="form.pembelian.tanggal" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <p v-if="err?.['pembelian.tanggal']" class="text-red-500 text-xs">{{ err?.['pembelian.tanggal'][0] }}</p>
                            </div>
                            <div class="relative">
                                <vSelect
                                    ref="vendor"
                                    v-model="form.pembelian.vendor"
                                    :options="finalVendors"
                                    label="nama"
                                    :filterable="false"
                                    :open-on-focus="false"
                                    :dropdownShouldOpen="dropdownVendor"
                                    @search="val => {
                                        vendorSearch = val
                                        triggerVendorSearch = false
                                    }"
                                    @keydown.enter.prevent="handleEnterVendor"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                >
                                    <template #no-options>
                                        <div class="text-sm p-2">
                                            <button
                                                v-if="triggerVendorSearch && vendorSearch"
                                                type="button"
                                                class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                                @click="openVendorModal = true"
                                            >
                                                + Tambah Vendor
                                            </button>
                                        </div>
                                    </template>
                                </vSelect>
                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Nama Vendor</label>
                                <p v-if="err?.vendor" class="text-red-500 text-xs">{{ err?.vendor?.[0] }}</p>
                            </div>
                            <div class="relative">
                                <vSelect
                                    ref="driver"
                                    v-model="form.pembelian.driver"
                                    :options="finalDrivers"
                                    label="nama"
                                    :filterable="false"
                                    :open-on-focus="false"
                                    :dropdownShouldOpen="dropdownDriver"
                                    @search="val => {
                                        driverSearch = val
                                        triggerdriverSearch = false
                                    }"
                                    @keydown.enter.prevent="handleEnterDriver"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                >
                                    <template #no-options>
                                        <div class="text-sm p-2">
                                            <button
                                                v-if="triggerdriverSearch && driverSearch"
                                                type="button"
                                                class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                                @click="openDriverModal = true"
                                            >
                                                + Tambah Driver
                                            </button>
                                        </div>
                                    </template>
                                </vSelect>
                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Nama Driver</label>
                                <p v-if="err?.driver" class="text-red-500 text-xs">{{ err?.driver?.[0] }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-x-3">
                                <div>
                                    <div class="relative">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Size</label>
                                        <input ref="size" @keydown.enter.prevent="tonasepembelian.focus" v-model="form.pembelian.size" type="text" @input="handleinputsize"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rp. 0">
                                    </div>
                                    <p v-if="err?.size" class="text-red-500 text-xs">{{ err?.size?.[0] }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-x-3">
                                <div>
                                    <div class="relative">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Tonase</label>
                                        <input ref="tonasepembelian" @keydown.enter.prevent="hargapembelian.focus" @input="handleinputonasepembelian" v-model="form.pembelian.tonase" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rp. 0">
                                    </div>
                                    <p v-if="err?.tonase" class="text-red-500 text-xs">{{ err?.tonase?.[0] }}</p>
                                </div>
                                <div>
                                    <div class="relative">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Harga</label>
                                        <input ref="hargapembelian" @keydown.enter.prevent="focusstatuspembelian" @input="handleinputhargapembelian" v-model="form.pembelian.harga" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rp. 0">
                                    </div>
                                    <p v-if="err?.harga" class="text-xs text-red-400">{{ err?.harga?.[0] }}</p>
                                </div>
                            </div>
                            <div class="relative">
                                <vSelect
                                    ref="statuspembelian"
                                    v-model="form.pembelian.status"
                                    :options="finalStatuses"
                                    label="status"
                                    :filterable="false"
                                    :open-on-focus="false"
                                    :dropdownShouldOpen="dropdownStatus"
                                    @search="val => {
                                        statusSearch = val
                                        triggerStatusSearch = false
                                    }"
                                    @keydown.enter.prevent="handleEnterStatus"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                >
                                    <template #no-options>
                                        <div class="text-sm p-2">
                                            <button
                                                v-if="triggerStatusSearch && statusSearch"
                                                class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                            >
                                                Status Tidak ditemukan
                                            </button>
                                        </div>
                                    </template>
                                </vSelect>
                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Status</label>
                                <p v-if="err?.status" class="text-red-500 text-xs">{{ err?.status?.[0] }}</p>
                            </div>
                            <div class="relative">
                                <vSelect
                                    ref="pembayaran"
                                    v-model="form.pembelian.pembayaran"
                                    :options="finalPembayaran"
                                    label="methode"
                                    :filterable="false"
                                    :open-on-focus="false"
                                    :dropdownShouldOpen="dropdownPembayaran"
                                    @search="val => {
                                        pembayaranSearch = val
                                        triggerPembayaranSearch = false
                                    }"
                                    @keydown.enter.prevent="handleEnterPembayaran"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                >
                                    <template #no-options>
                                        <div class="text-sm p-2">
                                            <button
                                                v-if="triggerPembayaranSearch && pembayaranSearch"
                                                type="button"
                                                class="bg-blue-500 w-full rounded-lg py-2 text-white hover:bg-blue-600"
                                                @click="openPaymentModal = true"
                                            >
                                                + Tambah Cara Pembyaran
                                            </button>
                                        </div>
                                    </template>
                                </vSelect>
                                <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Cara Bayar</label>
                                <p v-if="err?.pembayaran" class="text-red-500 text-xs">{{ err?.pembayaran?.[0] }}</p>
                            </div>
                            <div class="grid grid-cols-2">
                                <div>
                                    <div class="relative">
                                        <input ref="matipembelian" @keydown.enter.prevent="kompensasi.focus" @input="handleinputklaimmatipembelian" v-model="form.pembelian.mati" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0 kg">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Klaim mati</label>
                                    </div>
                                    <p v-if="err?.mati" class="text-red-500 text-xs">{{ err?.mati?.[0] }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div>
                                    <div class="relative">
                                        <input ref="kompensasi" @keydown.enter.prevent="create" @input="handleinputkompensasipembelian" v-model="form.pembelian.kompensasi" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rp. 0">
                                        <label class="absolute left-3 -top-2.5 bg-white px-1 text-sm text-slate-700 peer-placeholder-shown:top-3">Kompensasi</label>
                                    </div>
                                    <p v-if="err?.kompensasi" class="text-red-500 text-xs">{{ err?.kompensasi?.[0] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-5">
                        <div>
                            <button type="submit" :disabled="isloadcreate" :class="isloadcreate ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                &#43; {{ isloadcreate ? 'Mengedit' : 'Edit' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <VendorCreate v-if="openVendorModal" @berhasil="handleberhasilvendor" @close="handleclosevendor" />
    <Drivercreate v-if="openDriverModal" @berhasil="handleberhasildriver" @close="handleclosedriver" />
    <Pembayaran v-if="openPaymentModal" @berhasil="handleberhasilmethode" @close="handleclosemethode" />
    <CustomerCreate v-if="openCustomerModal" @berhasil="handleberhasilcustomer" @close="handleclosecustomer" />
</template>

