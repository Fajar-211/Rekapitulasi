<script setup>
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { onMounted } from 'vue';

const emit = defineEmits(['berhasil', 'close']);
const isloaddriver = ref(false);
//ref nama tag html
const nama = ref(null)
const telp = ref(null)

function reset(){
    form.value.nama = '';
    form.value.telp = '';
    nama.value = null;
    telp.value = null;
    err.value = {};
}
const form = ref({
    nama: '',
    telp: ''
})

async function create() {
    try{
        isloaddriver.value = true;
        const response = await axios.post('/api/master/driver', form.value);
        reset()
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Berhasil membuat driver',
            showConfirmButton: false,
            timer: 2000
        })
        emit('berhasil', response.data.driver);
        console.log('new driver : ', response.data.driver);
    }catch(error){
        console.log(error);
        err.value = error.response.data?.errors ?? {};
        Swal.fire({
            title: error.response.status,
            text: error.response.data?.message,
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        })
    }finally{
        isloaddriver.value = false;
    }
}
const err = ref({});
function handleclosedriver(){
    reset();
    emit('close');
}

async function focusnama(){
    await nextTick()
    nama.value?.focus();
}
onMounted(()=>{
    focusnama();
});
</script>

<template>
    <div class="overflow-y-auto bg-slate-900/50 h-screen overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 max-h-full flex inset-0">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Driver</h3>
                    <button type="button" @click="handleclosedriver" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-target="createProductModal" data-modal-toggle="createProductModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="create">
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                            <input ref="nama" @keydown.enter.prevent="telp.focus()" v-model="form.nama" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama">
                            <p v-if="err?.nama" class="text-xs text-red-400">{{ err?.nama[0] }}</p>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telp</label>
                            <input ref="telp" @keydown.enter.prevent="create" v-model="form.telp" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Telp">
                            <p v-if="err?.telp" class="text-xs text-red-400">{{ err?.telp[0] }}</p>
                        </div>
                    </div>
                    <button type="submit" :disabled="isloaddriver" :class="isloaddriver ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ isloaddriver ? 'Menambah' : 'Tambah driver baru' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>