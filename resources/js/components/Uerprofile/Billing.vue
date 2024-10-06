<template>
    <div>
<!--
        <div class="row mt-5">
            <div class="col">
                <div class="card-title">
                    <h2>Billing Information</h2>
                </div>
                <div class="mt-2 row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{ subTotal }} Tk</h3>
                                    <span>Total Amount</span>
                                </div>
                                <div class="avatar bg-light-primary p-50">
                                    <span class="avatar-content">
                                        <vue-feather type="dollar-sign"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{ payTotal }} Tk</h3>
                                    <span>Total Pay</span>
                                </div>
                                <div class="avatar bg-light-success p-50">
                                    <span class="avatar-content">
                                        <vue-feather type="dollar-sign"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{ dueTotal }} Tk</h3>
                                    <span>Total Due</span>
                                </div>
                                <div class="avatar bg-light-warning p-50">
                                    <span class="avatar-content">
                                        <vue-feather type="dollar-sign"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bolder mb-75">{{ doscountTotal }} Tk</h3>
                                    <span>Total Discount</span>
                                </div>
                                <div class="avatar bg-light-info p-50">
                                    <span class="avatar-content">
                                        <vue-feather type="dollar-sign"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
-->

        <!-- Project table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="py-1">Payment Id</th>
                        <th class="py-1">Taken By</th>
                        <th class="py-1">Transaction Date</th>
                        <th class="py-1">Amount</th>
                        <th class="py-1">Pay Total</th>
                        <th class="py-1">Total Due</th>
                        <th class="py-1">Payment Method</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in props.transactions" :key="item.id">
                        <td>{{ 'Tran_#'+item.transaction_id }}</td>
                        <td>{{ item.received_by?.name }}</td>
                        <td>{{ moment(item.payment_date).format('ll') }}</td>
                        <td>{{ item.amount }}</td>
                        <td>{{ item.pay }}</td>
                        <td>{{ item.due }}</td>
                        <td>{{ item.method?.name }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /Project table -->
    </div>
</template>
<script setup>
import {useDate} from '@/composables/useDate.js'
import moment from "moment";
import {computed, ref} from 'vue'
    const props = defineProps({
        transactions:[]|Object|null
    })

let {formatted} = useDate();






</script>

<style scoped>




</style>
