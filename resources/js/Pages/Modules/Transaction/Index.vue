<template>
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
                                    <div class="d-flex align-items-center">
                                        <h4 class="card-title me-1">Transactions Information's</h4>
                                        <span class="text-info cursor-pointer" @click="tranDetails" v-c-tooltip="'Click Me Me Get More Details'"> <vue-feather type="info" size="15"/></span>
                                    </div>
                                    <div>
                                        <CDropdown>
                                            <CDropdownToggle class="p-0">
                                                <button class="btn bg-light-secondary d-flex align-items-center">
                                                    <vue-feather type="external-link" size="15"/>
                                                    <span class="px-1">Export</span>
                                                    <vue-feather type="chevron-down" size="15"/>
                                                </button>
                                            </CDropdownToggle>
                                            <CDropdownMenu>
                                                <CDropdownItem @click="exportPDF">
<!--                                                    <vue-feather type="download" size="15"/>-->
                                                    <span class="ms-1">PDF</span>
                                                </CDropdownItem>
<!--                                                <CDropdownItem >
                                                    &lt;!&ndash;                                                    <vue-feather type="download" size="15"/>&ndash;&gt;
                                                    <span class="ms-1">EXCEL</span>
                                                </CDropdownItem>-->
                                            </CDropdownMenu>
                                        </CDropdown>
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
                                            </select>

                                            <div class="ml-2">
                                                <select v-model="searchByStatus" class="select2 form-select select w-100">
                                                    <option selected disabled :value="undefined">Filter By Type</option>
                                                    <option :value="null">All</option>
                                                    <option value="in" >Credited</option>
                                                    <option value="out" >Deviated</option>
                                                </select>
                                            </div>

                                            <Datepicker v-model="dateRange"
                                                        :monthChangeOnScroll="false"
                                                        range multi-calendars
                                                        :enable-time-picker="false"
                                                        :format="'dd-MM-Y'"
                                                        placeholder="Select Date Range" autoApply
                                                        @update:model-value="handleDate" ></Datepicker>
                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input v-model="search"
                                                           type="search"
                                                           class="form-control"
                                                           placeholder="What You Find ?"
                                                           aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="user-list-table table table-striped">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
                                            <th class="sorting">Subject</th>
                                            <th class="sorting">Method</th>
                                            <th class="sorting">Amount</th>
                                            <th class="sorting">Payment Type</th>
                                            <th class="sorting">User</th>
                                            <th class="sorting">Created At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="tra in transactions.data" :key="tra.id">
                                            <td>
                                                <a href="#">
                                                    #Tran-{{ tra.tran.u_id+tra.tran.id }}
                                                </a>
                                            </td>
                                            <td>
                                                <a :href="`/admin/quotations/${tra.tran.transaction_model_id}?type=show_invoice`" v-if="tra.tran.transaction_model === 'App\\Models\\Invoice'">{{ `${tra.tran.transaction_model}::find(${tra.tran.transaction_model_id})` }}</a>
                                                <a :href="`/admin/invoices/${tra.tran.transaction_model_id}`" v-else-if="tra.tran.transaction_model === 'App\\Models\\CustomInvoice'">{{ `${tra.tran.transaction_model}::find(${tra.tran.transaction_model_id})` }}</a>
                                                <a class="text-primary" @click="editItem(`/admin/expense/${tra.tran.transaction_model_id}`)" v-else-if="tra.tran.transaction_model === 'App\\Models\\Expanse'">{{ `${tra.tran.transaction_model}::find(${tra.tran.transaction_model_id})` }}</a>
                                            </td>
                                            <td>
                                                {{ tra.tran.method.name }}
                                            </td>
                                            <td>
                                                {{ tra.tran.total_pay }} Tk
                                            </td>
                                            <td class="cursor-pointer" v-if="tra.tran.type ==='in'"  v-c-tooltip="`Cash In  ${tra.tran.total_pay} Tk \n Rechived By ${tra.tran.user.name}`">
                                                <span class="text-bold text-success font-bold d-flex align-items-center">
                                                    <vue-feather type="trending-up"/>
                                                    <span class="ms-1">Credited</span>
                                                </span>
                                            </td>
                                            <td class="cursor-pointer" v-else v-c-tooltip="`Cash Out  ${tra.tran.total_pay} Tk \n Expanse By ${tra.tran.user.name}`">
                                                <span class="text-danger text-bold font-bold d-flex align-items-center">
                                                    <vue-feather type="trending-down"/>
                                                    <span class="ms-1">Debited</span>
                                                </span>
                                            </td>
                                            <td>
                                                {{ tra.tran.user.name }}
                                            </td>
                                            <td>
                                                {{  moment(tra.tran.created_at).format('lll') }}
                                            </td>
                                        </tr>
                                        </tbody>
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

    <Modal id="updateData" title="Expanse Details" v-vb-is:modal size="lg">
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
    </Modal>

</template>
<script>

</script>
<script setup>
import Pagination from "../../../components/Pagination"
import Icon from '../../../components/Icon'
import Modal from '../../../components/Modal'
import ImageUploader from "../../../components/ImageUploader"
import Textarea from "../../../components/Textarea";
import moment from 'moment';
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import axios from "axios";
import {useDate} from "../../../composables/useDate";
const range = useDate();
const formatted  = useDate();
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'


const props = defineProps({
    transactions: []|null,
    credited:null,
    debited:null,
    filters: Object,
    main_url: String,
});

const tranDetails = () => document.getElementById("showData").$vb.modal.show()

const url = location.search;
const exportPDF =() =>{
    if (url){
        window.location.href = window.location.href+"&export_pdf=true";
    }else{
        window.location.href = window.location.href+"?export_pdf=true";
    }
}

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
watch([search, perPage, searchByStatus, dateRange], debounce(function ([val, val2, val3, val4]) {
    router.get(props.main_url, { search: val, perPage: val2, byStatus: val3 , dateRange: val4}, { preserveState: true, replace: true });
}, 300));





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

