<template>

    <!-- Project table -->
    <div class="card">
        <h4 class="card-header">{{ this.$options.name }}</h4>
        <div class="table-responsive">
            <table class="user-list-table table">
                <thead class="table-light">
                <tr class="">
                    <th class="sorting bg-white py-1">#id</th>
                    <th class="sorting bg-white py-1">Subject</th>
                    <th class="sorting bg-white py-1">Total Price</th>
                    <th class="sorting bg-white py-1">Created by</th>
                    <th class="sorting bg-white py-1">Created Date</th>
                    <th class="sorting bg-white py-1"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="qut in quotations" :key="qut.id">
                    <td>
                        <a :href="qut.show_url" target="_blank">#{{ moment(new Date()).format('YYYYMMD')+qut.id}}</a>
                    </td>
                    <td>{{ qut.subject }}</td>
                    <td>
                        <span class="cursor-pointer" v-c-tooltip="'Total Price: '+qut.total_price+'\nDiscount: '+qut.discount">{{ qut.grand_total }}</span>
                    </td>

                    <td class="cursor-pointer">
                        <span>{{ qut.user.name }}</span>
                    </td>
                    <td>
                        {{ moment(qut.created_at).format('lll') }}
                    </td>
                    <td>
                        <a v-if="this.$page.props.auth.user.role == 'Administrator' || this.$page.props.auth.user.can.includes('quotation.show')" :href="`/admin/quotations/${qut.id}`">
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
import Icon from '../Icon'
import moment from "moment";
import {useDate} from '../../composables/useDate.js'
    defineProps({
        quotations: Object
    })

    let {formatted} = useDate();

</script>

<style scoped>

</style>
