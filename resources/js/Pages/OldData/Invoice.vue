<template>
    <Head title="Old Invoice Management"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Old Quotation Information's </h4>
                                </div>
                                <div class="card-datatable table-responsive pt-0">
                                    <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                                        <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start align-items-center gap-2">
                                            <div class="select-search-area">
                                                <label>Show <select class="form-select" v-model="perPage">
                                                    <option :value="undefined">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                    <option value="200">200</option>
                                                    <option value="500">500</option>
                                                </select> entries</label>
                                            </div>

                                            <div v-if="!isCustom">
                                                <select  v-model="dateRange" @update:model-value="changeDateRange" class="select2 form-select select w-100 ms-1" id="select2-basic">
                                                    <option selected disabled :value="undefined">Filter By Date</option>
                                                    <option :value="null">All</option>
                                                    <option v-for="(type, index) in range.ranges" :value="type">
                                                        {{ index }}
                                                    </option>
                                                    <option value="custom">Custom Range</option>
                                                </select>
                                            </div>
                                            <div v-else>
                                                <Datepicker v-model="dateRange"
                                                            :monthChangeOnScroll="false"
                                                            range multi-calendars
                                                            :enable-time-picker="false"
                                                            :format="'dd-MM-Y'"
                                                            placeholder="Select Date Range" autoApply
                                                            @update:model-value="handleDate" ></Datepicker>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                                            <div
                                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                                <div class="select-search-area">
                                                    <label>Search:<input v-model="search" type="search"
                                                                         class="form-control"
                                                                         placeholder="Search Now"
                                                                         aria-controls="DataTables_Table_0"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="user-list-table table table-striped">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">Id</th>
                                            <th class="sorting">Subject</th>
                                            <th class="sorting" width="15%">Price</th>
                                            <th class="sorting" width="15%">Discount</th>
                                            <th class="sorting">Client Name</th>
                                            <th class="sorting">Client Phone</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(invoice, i) in oldInvoice.data" :key="invoice.invoice.id">
                                                <td>{{ invoice.invoice.id }}</td>
                                                <td>{{ invoice.invoice.quotation?.subject }}</td>
                                                <td> <span v-if="invoice.invoice.grand_total">{{ invoice.invoice.grand_total }} Tk</span> <span v-else> --- </span></td>
                                                <td> <span v-if="invoice.invoice.discount">{{ invoice.invoice.discount }} Tk</span> <span v-else> --- </span></td>
                                                <td>{{invoice.invoice?.quotation?.client?.name }}</td>
                                                <td>
                                                    {{ invoice.invoice?.quotation?.client?.phone }}
                                                </td>
                                                <td>{{ moment(invoice.invoice?.date).format('MM-DD-YY') }}</td>
                                                <td>
                                                    <a :href="`old-invoice/${invoice.invoice?.id}`" >
                                                        <vue-feather type="eye"/>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <Pagination :links="oldInvoice.links" :from="oldInvoice.from" :to="oldInvoice.to" :total="oldInvoice.total"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</template>



<script setup>
import Pagination from "@/components/Pagination.vue";
import Icon from "@/components/Icon.vue";
import Modal from "@/components/Modal.vue";
import InputFieldError from "@/components/InputFieldError.vue";


import TextEditor from "@/components/TextEditor.vue";


import ImageUploader from "@/components/ImageUploader.vue";
import {ref, watch, computed} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import axios from 'axios';
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
import {useAction} from "../../composables/useAction";
import moment from "moment";
import {useDate} from "@/composables/useDate.js";
const range = useDate();
const props = defineProps({
    oldInvoice: Object,
    filters: Object,
    main_url:String,
});


const dateRange = ref(props.filters.dateRange)
const isCustom =ref(false);
const changeDateRange = (event) => {
    if(event=== 'custom'){
        isCustom.value = true;
        dateRange.value = '';
    }
};


let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);
watch([search, perPage, dateRange], debounce(function ([val, val2, val3]) {
    router.get(props.main_url, { search: val, perPage: val2, dateRange: val3}, { preserveState: true, replace: true });
}, 300));


</script>

<style scoped>
.ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners{
    min-height: 15rem !important;
}
.bg-indego{
    background: #fdb568;
}
</style>
