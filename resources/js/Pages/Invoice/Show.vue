<template>
    <app-layout title="Show Invoice">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Show Invoice
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
                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12 md:col-span-6">
                                                    <span class="block text-3xl font-bold text-gray-700">Invoice #{{invoice_number}}</span>
                                                </div>

                                                <div class="col-span-12 md:col-span-6 md:text-right">
                                                    <span class="block text-3xl font-bold text-gray-700">Date: {{date}}</span>
                                                </div>

                                                <div class="col-span-12 md:col-span-12">
                                                    <span class="text-xl font-semibold text-gray-700">Customer Name: </span>
                                                    <span class="text-xl font-bold text-gray-700">{{customer_name}}</span>
                                                </div>

                                                <div class="col-span-12 relative flex py-5 items-center">
                                                    <div class="flex-grow border-t border-gray-400"></div>
                                                    <span class="flex-shrink mx-4 font-bold text-gray-700">Products</span>
                                                    <div class="flex-grow border-t border-gray-400"></div>
                                                </div>  

                                                <!-- Products -->
                                                <div class="col-span-3">
                                                    <label for="product-name-0" class="block text-xl font-bold text-gray-700">Name</label>
                                                </div>

                                                <div class="col-span-3">
                                                    <label for="product-quantity-0" class="block text-xl font-bold text-gray-700">Quantity</label>
                                                </div>

                                                <div class="col-span-3">
                                                    <label for="product-price-0" class="block text-xl font-bold text-gray-700">Price</label>
                                                </div>

                                                <div class="col-span-3">
                                                </div>

                                                <div class="col-span-12">
                                                    <div class="grid grid-cols-12 gap-6 mb-4" v-for="(name,k) in product_name" :key="k">
                                                        <div class="col-span-3">
                                                            <span class="block text-sm font-medium text-gray-700">{{name}}</span>
                                                        </div>

                                                        <div class="col-span-3">
                                                            <span class="block text-sm font-medium text-gray-700">{{product_quantity[k]}}</span>
                                                        </div>
                                                        
                                                        <div class="col-span-3">
                                                            <span class="block text-sm font-medium text-gray-700">₱{{product_price[k]}}</span>
                                                        </div>
                                                        
                                                        <div class="col-span-3">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-end w-full">
                                                <span class="text-lg font-semibold text-gray-700">Total Amount: </span> <span class="text-lg font-bold text-gray-700">₱{{total_amount}}</span>
                                            </div>
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
    import { Link } from "@inertiajs/inertia-vue3"

    export default {
        components: {
            AppLayout,
            Link,
        },
        props: ['invoice'],
        data() {
            return {
                invoice_number: this.invoice.data.invoice_number,
                date: this.invoice.data.date,
                customer_name: this.invoice.data.customer_name,
                product_name: this.invoice.data.product_name,
                product_quantity: this.invoice.data.product_quantity,
                product_price: this.invoice.data.product_price,
                total_amount: this.invoice.data.total_amount
            }
        },
        methods: {
        },
    }

</script>