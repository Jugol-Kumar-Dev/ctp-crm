<template>
    <Head title="Due Report Management"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <!-- Advanced Search -->
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <h4 class="card-title me-1">Due Report</h4>
                                        </div>
                                        <h3 class="mt-3" v-if="dateRange">Date Range <strong>{{ moment(dateRange[0])?.format('l') }}</strong> To <strong>{{ moment(dateRange[1])?.format('l') }}</strong></h3>
                                    </div>
                                </div>
                                <div class="card-datatable table-responsive pt-0 px-2">
                                    <div class="d-flex align-items-center justify-content-between border-bottom">
                                        <div class="select-search-area d-flex align-items-center">
                                            <select class="form-select" v-model="perPage">
                                                <option :value="undefined">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                                <option value="500">500</option>
                                            </select>


                                            <Datepicker v-model="dateRange"
                                                        :monthChangeOnScroll="false"
                                                        range multi-calendars
                                                        :enable-time-picker="false"
                                                        :format="'d-MM-Y'"
                                                        placeholder="Select Date Range" autoApply
                                                        @update:model-value="handleDate" ></Datepicker>

                                            <select class="form-select" v-model="employee" style="width:100%;" v-if="!this.$page.props.auth.user.can.includes('duetrx.own')">
                                                <option :value="undefined" disabled selected>Filter By Employee</option>
                                                <option :value="emp.id" v-for="emp in props.employees" v-text="emp.name"/>
                                            </select>

                                            <a class="btn btn-sm btn-icon btn-primary" v-if="isReset"
                                               href="/admin/due-report">
                                                <vue-feather type="x-circle"></vue-feather>
                                            </a>

                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input v-model="search"
                                                           type="search"
                                                           class="form-control"
                                                           placeholder="Search Now"
                                                           aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="user-list-table table table-striped">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
                                            <th class="sorting">Grand Total</th>
                                            <th class="sorting">Pay</th>
                                            <th class="sorting">Due</th>
                                            <th class="sorting">User</th>
                                            <th class="sorting">Customer</th>
                                            <th class="sorting">Created At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="trx in transactions.data" :key="trx.id">
                                            <td>
                                                <Link :href="`/admin/invoices/${trx.id}`">
                                                    #_{{ trx?.invoice_id }}
                                                </Link>
                                            </td>
                                            <td>
                                                {{ trx?.grand_total }}
                                            </td>
                                            <td>
                                                {{ trx?.pay }}
                                            </td>
                                            <td>
                                                {{ trx?.due }}
                                            </td>
                                            <td>
                                                <Link :href="`/admin/users/${trx?.user?.id}`">
                                                  {{ trx?.user?.name }}
                                                </Link>
                                            </td>
                                            <td>
                                                <Link :href="`/admin/clients/${trx?.client?.id}`">
                                                  {{ trx?.client?.name }}
                                                </Link>
                                            </td>
                                            <td>
                                                {{  moment(trx.created_at).format('D-MM-y') }}
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="bg-primary text-white" colspan="1">Total=</th>
                                                <th class="bg-primary text-white">{{ grandTotal }}</th>
                                                <th class="bg-primary text-white">{{ payTotal }}</th>
                                                <th class="bg-primary text-white">{{ dueTotal }}</th>
                                                <th class="bg-primary text-white" colspan="3"></th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <Pagination :links="transactions.links" :from="transactions.from" :to="transactions.to" :total="transactions.total" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>



    <Modal id="showData" title="Total Transactions" v-vb-is:modal size="sm">
        <div class="modal-body">
            <div class="row mb-1">
                <div class="col-md">
                    <h3>Total Credited : {{ credited }} Tk</h3>
                    <h3>Total Debited : {{ debited }} Tk</h3>
                </div>
            </div>
        </div>
    </Modal>

<!--    <Modal id="updateData" title="Expanse Details" v-vb-is:modal size="lg">
        <div class="modal-body">
                <div class="row mb-1 flex-column">
                    <div class="col mb-1">
                        <label>Purpose</label>
                        <input class="form-control disabled readonly" disabled :value="editData?.purpse.name" label="name"/>
                    </div>

                    <div class="col mb-1">
                        <label>Expanse Subject</label>
                        <input :value="editData?.subject" disabled type="text" class="form-control disabled readonly" />
                    </div>
                    <div class="col mb-1">
                        <label>Expanse Amount </label>
                        <input :value="editData?.amount" disabled type="text" class="form-control disabled readonly" />
                    </div>
                    <div class="col mb-1">
                        <label>Payment Method</label>
                        <input class="form-control disabled readonly" disabled  :value="editData?.method?.name" label="name"/>
                    </div>
                    <div class="col-md mb-1">
                        <label>Expanse Date</label>
                        <input :value="moment(editData?.date).format('ll')" disabled class="form-control disabled readonly" />
                    </div>
                    <div class="col-md-12">
                        <label>Expanse Note</label>
                        <p v-text="editData?.details"></p>
                    </div>
                    <div class="col mb-1" v-if="editData?.document">
                        <img :src="`${this.$page.props?.auth?.MAIN_URL}/storage/${editData?.document}`" frameborder="0" class="w-100 h-100"/>
                    </div>
                </div>
            </div>
    </Modal>-->

</template>
<script>

</script>
<script setup>
import Pagination from "../../components/Pagination"
import Icon from '../../components/Icon'
import Modal from '../../components/Modal'
import ImageUploader from "../../components/ImageUploader"
import Textarea from "../../components/Textarea";
import moment from 'moment';
import {ref, watch, computed} from "vue";
import debounce from "lodash/debounce";
import {Inertia} from "@inertiajs/inertia";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/inertia-vue3";
import axios from "axios";
import {useDate} from "../../composables/useDate";
const range = useDate();
const formatted  = useDate();
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'


const props = defineProps({
    transactions: []|null,
    credited:null,
    debited:null,
    employees:[]|null,
    filters: Object,
    main_url: String,

    grandTotal:null,
    payTotal:null,
    dueTotal:null
});

const tranDetails = () => document.getElementById("showData").$vb.modal.show()

const editData = ref(null);
const editItem = (url) =>{
    console.log(url);
    axios.get(url+"/?data=true").then((res)=>{
        editData.value = res.data;
        document.getElementById('updateData').$vb.modal.show()
    }).catch((err) =>{
        console.log(err);
    });
}

const dateRange = ref(props.filters.dateRange)
const employee = ref(props.filters.employee)
const isCustom =ref(false);
const changeDateRange = (event) => {
    if(event=== 'custom'){
        isCustom.value = true;
        dateRange.value = '';
    }
};


const handleDate = (event) => isCustom.value = event !== null;
const searchByStatus = ref(props.filters.byStatus)

let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage, searchByStatus, dateRange, employee], debounce(function ([val, val2, val3, val4, val5]) {
    Inertia.get(props.main_url, { search: val, perPage: val2, byStatus: val3 , dateRange: val4, employee:val5}, { preserveState: true, replace: true });
}, 300));

const exportPDF =() =>{
    if(props.filters.length === undefined){
        window.location.replace(window.location.href+"&export_pdf=true")
    }else{
        window.location.replace(window.location.href+"?export_pdf=true")
    }
}

const isReset = computed(() => {
    return !!props.filters?.perPage || props.filters?.byStatus || props.filters?.dateRange || props.filters?.employee;
})

const totalDebided = computed(()=>{
    return props.transactions.map(item =>{
        console.log(item)
    })
})


</script>

<style>
.dp__input_wrap svg{
    margin-left: 11px;
}
.dp__input_icon_pad {
    padding: 8px 35px !important;
    border-radius: 5px !important;
}
</style>

