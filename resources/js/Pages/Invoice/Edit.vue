<template>
    <app-layout title="Edit Invoice">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Invoice
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="mt-10 sm:mt-0">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="mt-5 md:mt-0 md:col-span-3">
                                <div>
                                    <div class="shadow overflow-hidden sm:rounded-md">
                                        <div class="px-4 py-5 bg-white sm:p-6">
                                            <div class="grid grid-cols-12 gap-6">
                                                <div class="col-span-12 md:col-span-6">
                                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number<span style="color:red">*</span></label>
                                                    <custom-input type="text" id="invoice_number" v-model="form.invoice_number" :errorMessage="form.errors.invoice_number" />
                                                </div>

                                                <div class="col-span-12 md:col-span-6">
                                                    <label for="date" class="block text-sm font-medium text-gray-700">Date<span style="color:red">*</span></label>
                                                    <Datepicker id="date" :class="changeDateClassIfError(form.errors.date)" v-model="form.date" :hideInputIcon="true" :format="'MMMM dd, yyyy'" :enableTimePicker="false" :is24="false" class="mt-1"/>
                                                    <jet-input-error :message="form.errors.date" class="mt-2" />
                                                </div>

                                                <div class="col-span-12 md:col-span-12">
                                                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name<span style="color:red">*</span></label>
                                                    <custom-input type="text" id="customer_name" v-model="form.customer_name" :errorMessage="form.errors.customer_name" />
                                                </div>

                                                <div class="col-span-12 relative flex py-5 items-center">
                                                    <div class="flex-grow border-t border-gray-400"></div>
                                                    <span class="flex-shrink mx-4 text-gray-700">Products</span>
                                                    <div class="flex-grow border-t border-gray-400"></div>
                                                </div>

                                                <!-- Products -->
                                                <div class="col-span-3">
                                                    <label for="product-name-0" class="block text-sm font-medium text-gray-700">Name<span style="color:red">*</span></label>
                                                </div>

                                                <div class="col-span-3">
                                                    <label for="product-quantity-0" class="block text-sm font-medium text-gray-700">Quantity<span style="color:red">*</span></label>
                                                </div>

                                                <div class="col-span-3">
                                                    <label for="product-price-0" class="block text-sm font-medium text-gray-700">Price<span style="color:red">*</span></label>
                                                </div>

                                                <div class="col-span-3">
                                                </div>

                                                <div class="col-span-12">
                                                    <div class="grid grid-cols-12 gap-6 mb-4" v-for="(ids,k) in form.product_name" :key="k">
                                                        <div class="col-span-3">
                                                            <custom-input type="text" id="product-name-0" v-model="form.product_name[k]" :errorMessage="form.errors['product_name.' + k]" />
                                                        </div>

                                                        <div class="col-span-3">
                                                            <custom-input type="text" id="product-name-0" v-model="form.product_quantity[k]" :errorMessage="form.errors['product_quantity.' + k]" />
                                                        </div>
                                                        
                                                        <div class="col-span-3">
                                                            <custom-input type="text" id="product-name-0" v-model="form.product_price[k]" :errorMessage="form.errors['product_price.' + k]" />
                                                        </div>
                                                        

                                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded col-span-3" @click="remove(k)" v-show="k || ( !k && form.product_name.length > 1)">
                                                            Remove
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="col-span-4">
                                                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="add()">
                                                            Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" @click="updateInvoice">Update Invoice</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import CustomInput from '@/Components/InputValidation.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import { Link } from "@inertiajs/inertia-vue3"
    import moment from 'moment'

    import Datepicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'

    export default {
        components: {
            AppLayout,
            CustomInput,
            Datepicker,
            JetCheckbox,
            JetInput,
            JetInputError,
            Link,
        },
        props: ['invoice','errors'],
        data() {
            return {
                form: this.$inertia.form({
                    invoice_number: this.invoice.data.invoice_number,
                    date: this.invoice.data.date,
                    customer_name: this.invoice.data.customer_name,
                    product_name: this.invoice.data.product_name,
                    product_quantity: this.invoice.data.product_quantity,
                    product_price: this.invoice.data.product_price
                }),
            }
        },
        methods: {
            updateInvoice() {
                this.form.clearErrors()
                this.form.transform((data) => ({
                    ...data,
                    birthday: moment(data.birthday).format('DD-MM-YYYY'),
                })).put(route('invoices.update',{invoice: this.invoice.data.id}))
            },
            add() {
                this.form.product_name.push('')
                this.form.product_quantity.push('')
                this.form.product_price.push('')
            },
            remove(index) {
                this.form.product_name.splice(index, 1)
                this.form.product_quantity.splice(index, 1)
                this.form.product_price.splice(index, 1)
            },
            changeDateClassIfError(errorMessage) {
                return {
                    'block': true,
                    'w-full': true,
                    'focus:outline-none': true,
                    'sm:text-sm': true,
                    'rounded-md': true,
                    'shadow-sm': true,
                    //Normal State
                    'focus:ring-indigo-500': errorMessage == undefined,
                    'focus:border-indigo-500': errorMessage == undefined,
                    'border-gray-300': errorMessage == undefined,
                    //Error State
                    'text-red-900': errorMessage,
                    'placeholder-red-300': errorMessage,
                    'border-red-300': errorMessage,
                    'focus:ring-red-500': errorMessage,
                    'focus:border-red-500': errorMessage,
                }
            },
        },
    }

</script>