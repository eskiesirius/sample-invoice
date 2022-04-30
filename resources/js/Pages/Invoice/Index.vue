<template>
    <app-layout title="Invoices">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Invoice's List
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="mb-4 mt-4">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-4">
                                    <Link class="px-6 py-2 mb-2 text-green-100 bg-green-500 rounded"
                                        :href="route('invoices.create')">
                                        Add New Invoice
                                    </Link>
                                </div>
                                <div class="md:col-start-9 col-span-12 md:col-span-5">
                                    <input type="text" class="block w-full pr-10 focus:outline-none sm:text-sm rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" v-model="search" placeholder="Search Invoice Number" aria-label="Search" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 text-gray-500">
                            <div class="flex flex-col">
                                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Invoice Number
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Invoice Date
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Customer Name
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Total Amount
                                                        </th>
                                                        <th scope="col" class="relative px-6 py-3">
                                                            <span class="sr-only">Edit</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="invoice in invoices.data" :key="invoice.id">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ invoice.invoice_number }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ invoice.date }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ invoice.customer_name }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ invoice.total_amount }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <Link :href="route('invoices.show',{ invoice })" class="text-indigo-600 hover:text-indigo-900">Show</Link>
                                                            <Link :href="route('invoices.edit',{ invoice })" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</Link>
                                                            <a href="#" @click="openModal(invoice)" class="text-indigo-600 hover:text-indigo-900 ml-4">Delete</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <Pagination :dataSet="invoices" class="mb-3" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <jet-confirmation-modal :show="deleteModal" @close="deleteModal = false">
                <template #title>
                    Delete Invoice
                </template>

                <template #content>
                    Are you sure you want to delete your invoice? Once the invoice is deleted, all of its resources and data will be permanently deleted.
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="deleteModal = false">
                        Cancel
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteInvoice">
                        Delete Invoice
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import { Link } from "@inertiajs/inertia-vue3"
    import Pagination from '@/Components/Pagination.vue'
    import { ArrowDownIcon, ArrowUpIcon } from '@heroicons/vue/solid'

    export default {
        components: {
            AppLayout,
            ArrowDownIcon,
            ArrowUpIcon,
            JetConfirmationModal,
            JetDangerButton,
            JetInput,
            JetSecondaryButton,
            Link,
            Pagination
        },
        props: ['invoices','filters'],
        data() {
            return {
                selectedInvoice : null,
                deleteModal: false,
                search: this.filters.search,
            }
        },
        watch: {
            search: _.debounce(function (value) {
                this.$inertia.get(route('invoices.index'), { search: value },{
                    preserveState: true,
                    replace: true
                })
            },500)
        },
        methods: {
            openModal(invoice) {
                this.deleteModal = true
                this.selectedInvoice = invoice
            },
            deleteInvoice() {
                this.$inertia.post(route('invoices.destroy',{ invoice: this.selectedInvoice }),{
                    _method: 'delete',
                },{
                    preserveScroll: true,
                    onSuccess: () => this.deleteModal = false
                })
            },
        },
        computed: {

        }
    }

</script>
