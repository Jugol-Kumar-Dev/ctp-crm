<template>
    <Head title="Quotation Management"/>

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
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="card-title">Quotations Information's </h4>
                                </div>
                                <div class="card-datatable table-responsive pt-0 px-2">
                                    <div class="d-flex align-items-center justify-content-between border-bottom">
                                        <div class="select-search-area d-flex align-items-center">
                                            <select class="form-select" v-model="perPage">
                                                <option :value="undefined">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                                <option value="500">500</option>
                                            </select>

                                            <Link href="quotations/create"
                                                  v-if="$page.props.auth.user.can.includes('quotation.create') || $page.props.auth.user.role.includes('Administrator')"
                                                  class="btn btn-primary ml-2 d-flex align-items-center">
                                                <vue-feather type="plus" size="15"/>
                                                <span>
                                                    Add Quotations
                                                </span>
                                            </Link>

                                            <Datepicker v-model="dateRange" :monthChangeOnScroll="false" range
                                                        multi-calendars
                                                        format="y-mm-dd"
                                                        placeholder="Select Date Range" autoApply
                                                        @update:model-value="handleDate"></Datepicker>

                                            <select class="form-select" style="width:200px;" v-model="employee"
                                                    v-if="$page.props.auth.user.role.includes('Administrator') || $page.props.auth.user.can.includes('quotation.index')">
                                                <option :value="undefined" disabled selected>Filter By Employee</option>
                                                <option :value="emp.id" v-for="emp in props.users" v-text="emp.name"/>
                                            </select>
                                            <a class="btn btn-sm btn-icon btn-primary" v-if="isReset"
                                               href="/admin/quotations">
                                                <vue-feather type="x-circle"></vue-feather>
                                            </a>

                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input v-model="search"
                                                           type="text"
                                                           class="form-control"
                                                           placeholder="Search Now"
                                                           aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="user-list-table table">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting bg-white py-1">#id</th>
                                            <th class="sorting bg-white py-1">Client Info</th>
                                            <th class="sorting bg-white py-1">Total Price</th>
                                            <th class="sorting bg-white py-1">Created by</th>
                                            <th class="sorting bg-white py-1">Created Date</th>
                                            <th class="sorting bg-white py-1"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="qut in quotations.data" :key="qut.id">
                                            <td>
                                                <a v-if="$page.props.auth.user.can.includes('quotation.show') ||
                                                $page.props.auth.user.role.includes('Administrator')"
                                                    :href="props.url+'/'+qut?.id" >{{ qut.quotation_id }}</a>

                                                <span v-else>#{{ moment(new Date()).format('YYYYMMD')+qut.id}}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
<!--                                                    <div class="avatar-wrapper">
                                                        <div class="avatar  me-1">
                                                            <img
                                                                src="#"
                                                                alt="{{ qut.client.username }}" height="32" width="32">
                                                        </div>
                                                    </div>-->
                                                    <div class="d-flex flex-column">
                                                        <div class="user_name text-truncate text-body">
                                                            <span class="fw-bolder">{{ qut.client.name }}</span>
                                                        </div>
                                                        <small class="emp_post text-muted">{{ qut.client.email }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="cursor-pointer" v-c-tooltip="'Total Price: '+qut.total_price+'\nDiscount: '+qut.discount">{{ qut.grand_total }}</span>
                                            </td>

                                            <td class="cursor-pointer">
                                                <span>{{ qut.user.name }}</span>
                                            </td>
                                            <td>
                                                {{ moment(qut.created_at)?.format('ll') }}
                                            </td>
                                            <td>
                                                <CDropdown
                                                    v-if="
                                                            $page.props.auth.user.can.includes('quotation.invoice') ||
                                                            $page.props.auth.user.can.includes('quotation.edit') ||
                                                            $page.props.auth.user.can.includes('quotation.show') ||
                                                            $page.props.auth.user.can.includes('quotation.delete')||
                                                            $page.props.auth.user.role.includes('Administrator')
                                                         ">


                                                <CDropdownToggle class="p-0">
                                                        <vue-feather type="more-vertical" />
                                                    </CDropdownToggle>
                                                    <CDropdownMenu >
                                                        <CDropdownItem :href="props.url+'/'+qut.id+'?download=true'"
                                                        v-if="$page.props.auth.user.can.includes('quotation.invoice') || $page.props.auth.user.role.includes('Administrator')">
                                                            <vue-feather type="download" size="15"/>
                                                            <span class="ms-1">Download</span>
                                                        </CDropdownItem>

                                                        <CDropdownItem :href="`/admin/edit/quotation/${qut.id}`"
                                                                       v-if="$page.props.auth.user.can.includes('quotation.edit') || $page.props.auth.user.role.includes('Administrator')">
                                                            <Icon title="pencil" />
                                                            <span class="ms-1">Edit</span>
                                                        </CDropdownItem>

                                                        <CDropdownItem :href="props.url+'/'+qut.id+'?type=show'"
                                                                       v-if="$page.props.auth.user.can.includes('quotation.show') || $page.props.auth.user.role.includes('Administrator')">

                                                        <Icon title="eye" />
                                                            <span class="ms-1">Show</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem @click="deleteItemModal(qut.id)"
                                                                       v-if="$page.props.auth.user.can.includes('quotation.delete') || $page.props.auth.user.role.includes('Administrator')">
                                                        <Icon title="trash" />
                                                            <span class="ms-1">Delete</span>
                                                        </CDropdownItem>
                                                    </CDropdownMenu>
                                                </CDropdown>
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                    <Pagination :links="quotations.links" :from="quotations.from" :to="quotations.to" :total="quotations.total" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Advanced Search -->
                <!--/ Multilingual -->
            </div>
        </div>
    </div>

    <Modal id="change-status" title="Change Quotation Status" v-vb-is:modal size="sm">
        <form @submit.prevent="addPayment">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <v-select v-model="updateForm.status"
                                  label="name"
                                  :options="status"
                                  placeholder="~~Select Status~~">
                        </v-select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="updateForm.processing" type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                    Change Status
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

</template>

<script>

import axios from "axios";
import moment from "moment";

export default {
    props: [
        'url',
    ],
    methods:{
        showQuotation(id){
            axios.get().then(function (data) {
                document.getElementById('showQuotation').$vb.modal.show();
                console.log(data);
            }).catch(function (err) {

            })
        }
    },

    setup(props){
        console.log(props.url);
    }
}

</script>


<script setup>
import Pagination from "@/components/Pagination.vue";
import Icon from "@/components/Icon.vue";
import Modal from "@/components/Modal.vue";
import {computed, ref, watch} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import {defineProps} from "@vue/runtime-core";
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
import {useDate} from "@/composables/useDate.js";
const range = useDate();



let props = defineProps({
    quotations: Object,
    users:Object|Array,
    filters: Object,
    notification:Object,
    url:String,
    change_status_url:null,
});
const status = [
    {"name":'New Quotation'}, {"name":'Sent'}, {"name":'Feedback'}, {"name":'Disqualified'}, {"name":'Converted To Invoice'}
]

let createForm = useForm({
    name:"",
    processing:Boolean,
})

let updateForm = useForm({
    quotId: null,
    status:null,
    processing:false,
})


let deleteItemModal = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(props.url+"/"+ id, { preserveState: true, replace: true, onSuccess: page => {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                },
                onError: errors => {
                    Swal.fire(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    )
                }})
        }
    })
}


let showQuotation = (id, page) => {
    router.get(props.url+"/"+id, {page:page},{
        preserveState: true,
        replace:true,
    });
}

let createInvoice = (id) =>router.get(props.url+"/invoice/"+id);

const changeStatus = (id) =>  {
    updateForm.quotId = id;
    document.getElementById('change-status').$vb.modal.show()
}
let addPayment = () => {
    router.post(props.change_status_url, updateForm, {
        onSuccess: () => {
            document.getElementById('change-status').$vb.modal.hide()
        }
    })
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
const employee = ref(props.filters.employee)

watch([search, perPage, searchByStatus, dateRange, employee], debounce(function ([val, val2, val3, val4, val5]) {
    router.get(props.url, { search: val, perPage: val2, byStatus: val3 , dateRange: val4, employee:val5}, { preserveState: true, replace: true });
}, 300));

const isReset = computed(() => !!props.filters?.perPage || props.filters?.byStatus || props.filters?.dateRange )

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
