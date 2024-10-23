<template>
    <Head>
        <title>Activity Log</title>
    </Head>
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
                                    <div class="d-flex flex-column w-100">
                                        <div class="d-flex align-items-center">
                                            <h4 class="card-title me-1">Activity Log's</h4>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h3 class="mt-3" v-if="dateRange">Date Range <strong>{{ moment(dateRange[0])?.format('l') }}</strong> To <strong>{{ moment(dateRange[1])?.format('l') }}</strong></h3>
                                            </div>
                                            <button class="btn btn-danger float-right" v-if="isReset" @click="clearData">Clear Activity Logs</button>
                                        </div>
                                    </div>
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


                                            <select class="form-select" v-model="employee" style="width:100%;" v-if="$page.props.auth.user.role.includes('Administrator') || $page.props.auth.user.can.includes('transaction.index')">
                                                <option :value="undefined" disabled selected>Filter By Employee</option>
                                                <option :value="emp.id" v-for="emp in props.users" v-text="emp.name"/>
                                            </select>

                                            <select class="form-select" v-model="logName" style="width:100%;" v-if="$page.props.auth.user.role.includes('Administrator') || $page.props.auth.user.can.includes('transaction.index')">
                                                <option :value="undefined" disabled selected>Filter By Name</option>
                                                <option :value="log?.log_name" v-for="log in props.log_names" v-text="log?.log_name"/>
                                            </select>

                                            <select class="form-select" v-model="filterEvents" style="width:100%;" v-if="$page.props.auth.user.role.includes('Administrator') || $page.props.auth.user.can.includes('transaction.index')">
                                                <option :value="undefined" disabled selected>Filter By Action</option>
                                                <option :value="emp?.event" v-for="emp in props.action" v-text="emp?.event"/>
                                            </select>

                                            <Datepicker v-model="dateRange"
                                                        :monthChangeOnScroll="false"
                                                        range multi-calendars
                                                        :enable-time-picker="false"
                                                        :format="'dd-MM-Y'"
                                                        placeholder="Select Date Range" autoApply
                                                        @update:model-value="handleDate" ></Datepicker>

                                            <a class="btn btn-sm btn-icon btn-primary" v-if="isReset"
                                               href="/admin/activity-logs">
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
                                            <th class="sorting">Log Name</th>
                                            <th class="sorting">Event</th>
                                            <th class="sorting">Description</th>
                                            <th class="sorting">Propertis</th>
<!--                                            <th class="sorting">Action Module</th>-->
                                            <th class="sorting">User</th>
                                            <th class="sorting">Created At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="log in logs.data" :key="log?.id">
                                                <td>{{ log?.id }}</td>
                                                <td>{{ log?.log_name }}</td>
                                                <td><span class="text-capitalize badge" :class="{
                                                    'bg-light-danger' : log?.event === 'deleted',
                                                    'bg-light-primary' : log?.event === 'created',
                                                    'bg-light-warning' : log?.event === 'updated',
                                                    'bg-light-success' : log?.event !== 'created' || log?.event === 'deleted' || log?.event === 'updated',
                                                }" style="font-weight: bold;">{{ log?.event }}</span></td>
                                                <td>{{ log?.description }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" @click="showDetails(log)">Show Details</a>
                                                </td>
<!--                                                <td>{{ log?.subject_type }}</td>-->
                                                <td>{{ log?.causer?.name }}</td>
                                                <td>{{ moment(log?.created_at).format('lll') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="logs.links" :from="logs.from" :to="logs.to" :total="logs.total" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>



    <Modal id="updateData" title="Log Details" v-vb-is:modal size="lg">
        <div class="modal-body">
            <pre id="json-display"></pre>
        </div>
    </Modal>
</template>
<script setup>
import Pagination from "@/components/Pagination.vue"
import moment from 'moment';
import {ref, watch, computed} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import axios from "axios";
import {useDate} from "@/composables/useDate.js";
const range = useDate();
const formatted  = useDate();
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
import Modal from "../components/Modal.vue";
import Swal from "sweetalert2";


const props = defineProps({
    logs: []|null,
    users:[]|null,
    log_names:[]|null,
    action:[]|null,
    filters: Object,
    main_url: String,
});

const tranDetails = () => document.getElementById("showData").$vb.modal.show()

const logData = ref(null);
const showDetails = (data) =>{
    logData.value = JSON.stringify(data);
    let editor = new JsonEditor('#json-display');
    editor.load(data);
    document.getElementById('updateData').$vb.modal.show()
}

const dateRange = ref(props.filters.dateRange)
const logName = ref(props.filters.logName)
const filterEvents = ref(props.filters.filterEvents)
const employee = ref(props.filters.employee)
const isCustom =ref(false);
const changeDateRange = (event) => {
    if(event === 'custom'){
        isCustom.value = true;
        dateRange.value = '';
    }
};


const handleDate = (event) => isCustom.value = event !== null;

let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage, logName, dateRange, employee, filterEvents], debounce(function ([val, val2, val3, val4, val5, val6]) {
    router.get(props.main_url, { search: val, perPage: val2, logName: val3 , dateRange: val4, employee:val5, filterEvents:val6}, { preserveState: true, replace: true });
}, 300), {deep:true});


const isReset = computed(() => {
    return !!props.filters?.perPage || props.filters?.logName || props.filters?.filterEvents || props.filters?.dateRange || props.filters?.employee;
})

const clearData = () =>{

    let url = null;
    if(props.filters.length === undefined){
        url = window.location.href+"&isDataClear=true";
    }else{
        url = window.location.href+"?isDataClear=true";
    }

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
            router.get(url, {
                preserveState: true, replace: true, onSuccess: page => {
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
                }
            })
        }
    })
}

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

