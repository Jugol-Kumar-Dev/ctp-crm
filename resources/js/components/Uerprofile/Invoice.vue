<template>

    <!-- Project table -->
    <div class="card">
        <h4 class="card-header">{{ this.$options.name }}</h4>
        <div class="table-responsive">
            <table class="user-list-table table">
                <thead class="table-light">
                <tr class="">
                    <th class="sorting">#id</th>
                    <th class="sorting">Creator</th>
                    <th class="sorting">Type</th>
                    <th class="sorting">Total Amount</th>
                    <th class="sorting">Due Amount</th>
                    <th class="sorting">Created At</th>
                    <th class="sorting">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="invoice in invoices" :key="invoice.id">
                    <td>#{{ invoice.invoice_id+''+invoice.id }}</td>
                    <td>{{ invoice.user?.name }}</td>
                    <td>{{ invoice.invoice_type === 'custom' ? 'Custom' : 'Quotation'}}</td>
                    <td  class="cursor-pointer"
                         v-c-tooltip="`Total Amount: ${invoice.total_amount} \n
                                                        Given Discount: ${invoice.discount ?? 0}
                                                        Grand Total: ${invoice.grand_total ?? 0}
                                                        Total Pay: ${invoice.pay ?? 0}
                                                        Total Due: ${invoice.due ?? 0}`">
                                                <span>
                                                    {{ invoice.grand_total }}
                                                </span>
                    </td>
                    <td>{{ invoice.due ?? '---'}} </td>
                    <td>{{ invoice.created_at }}</td>
                    <td>
                        <a v-if="$page.props.auth.user.role == 'Administrator' || $page.props.auth.user.can.includes('invoice.show')" :href="`/admin/invoices/${invoice.id}`">
                            <vue-feather class="text-info" type="eye" size="15"/>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import {useDate} from '@/composables/useDate.js'
    defineProps({
        invoices: Object
    })


let {formatted} = useDate();

</script>

<style scoped>

</style>
