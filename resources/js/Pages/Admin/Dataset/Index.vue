<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    datasets: Object,
    stats: Object,
    status: String,
});

const form = useForm({
    file: null,
});

const fileInput = ref(null);

const submitImport = () => {
    form.post(route('dataset.import'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            if (fileInput.value) fileInput.value.value = null;
        },
    });
};

const toggleTraining = (dataset) => {
    router.patch(route('dataset.split', dataset.id), {
        is_training: !dataset.is_training,
    }, { preserveScroll: true });
};

const deleteDataset = (dataset) => {
    if (confirm('Yakin ingin menghapus data ini?')) {
        router.delete(route('dataset.destroy', dataset.id), { preserveScroll: true });
    }
};

const confirmingTruncate = ref(false);

const truncateDataset = () => {
    router.delete(route('dataset.truncate'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    confirmingTruncate.value = false;
};
</script>

<template>
    <Head title="Manajemen Dataset" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                <span class="text-[#00AEEF]">Admin</span> / Manajemen Dataset
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <!-- Flash Message -->
                <div v-if="$page.props.flash?.status || status" class="mb-4 rounded-md bg-green-50 p-4 border border-[#00A651]">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-[#00A651]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ $page.props.flash?.status || status }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Stats Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#00AEEF] p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Data Medis</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.total }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#00A651] p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Data Latih (Training)</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.training }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-yellow-400 p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Data Uji (Testing)</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stats.testing }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Import Dataset (CSV)</h3>
                        <form @submit.prevent="submitImport" class="flex items-center gap-4">
                            <div>
                                <input 
                                    type="file" 
                                    ref="fileInput"
                                    @input="form.file = $event.target.files[0]"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#00AEEF] file:bg-opacity-10 file:text-[#00AEEF] hover:file:bg-opacity-20"
                                    accept=".csv,.txt"
                                />
                                <InputError class="mt-2" :message="form.errors.file" />
                            </div>
                            <PrimaryButton :class="{ 'opacity-25': form.processing, 'bg-[#00AEEF] hover:bg-blue-600': true }" :disabled="form.processing">
                                Upload & Proses
                            </PrimaryButton>
                        </form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex justify-between items-center border-b">
                        <h3 class="text-lg font-medium text-gray-900">Data Riwayat Pasien</h3>
                        <DangerButton @click="confirmingTruncate = true" class="text-xs" v-if="stats.total > 0">
                            Kosongkan Data
                        </DangerButton>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">T. Darah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kolesterol</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gula Darah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risiko Jantung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Data</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="dataset in datasets.data" :key="dataset.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dataset.age }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dataset.gender }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dataset.blood_pressure }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dataset.cholesterol }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dataset.blood_sugar }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span v-if="dataset.risk_result" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Beresiko
                                        </span>
                                        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-[#00A651]">
                                            Tidak Beresiko
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button @click="toggleTraining(dataset)" class="px-2 py-1 text-xs rounded border transition-colors" :class="dataset.is_training ? 'bg-blue-50 border-[#00AEEF] text-[#00AEEF]' : 'bg-gray-50 border-gray-300 text-gray-600'">
                                            {{ dataset.is_training ? 'Data Latih' : 'Data Uji' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="deleteDataset(dataset)" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="datasets.data.length === 0">
                                    <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Belum ada dataset. Silakan import melalui form di atas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links (Simple) -->
                    <div class="p-4 border-t flex justify-between items-center" v-if="datasets.links && datasets.data.length > 0">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ datasets.from }} sampai {{ datasets.to }} dari {{ datasets.total }} data
                        </div>
                        <div class="flex gap-2">
                            <Link v-for="(link, k) in datasets.links" :key="k"
                                :href="link.url || ''"
                                v-html="link.label"
                                class="px-3 py-1 border rounded text-sm"
                                :class="{ 'bg-[#00AEEF] text-white border-[#00AEEF]': link.active, 'text-gray-500 bg-white hover:bg-gray-50': !link.active, 'opacity-50 cursor-not-allowed': !link.url }"
                                preserve-scroll
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Truncate Modal -->
        <Modal :show="confirmingTruncate" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus seluruh dataset?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Tindakan ini tidak dapat dibatalkan. Semua data riwayat pasien yang telah Anda import akan hilang secara permanen.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Batal </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        @click="truncateDataset"
                    >
                        Ya, Hapus Semua Data
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
