<template>
    <Head title="Expanse Managment"/>

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
                                    <h4 class="card-title">Expanse Information's </h4>
                                    <button class="dt-button add-new btn btn-primary"
                                            v-if="$page.props.auth.user.can.includes('expanse.create') || $page.props.auth.user.role.includes('Administrator')"
                                            tabindex="0" type="button" @click="addExpanseModal"
                                    >Add Expanse</button>
                                </div>
                                <div class="card-datatable table-responsive pt-0">
                                    <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                                        <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
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
                                        </div>
                                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                                            <div
                                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                                <div class="select-search-area">
                                                    <label>Search:<input v-model="search" type="search" class="form-control"
                                                                         placeholder="Search Now"
                                                                         aria-controls="DataTables_Table_0"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="user-list-table table table-striped">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
<!--                                            <th class="sorting">Subject</th>-->
                                            <th class="sorting">Purpose</th>
                                            <th class="sorting">Method</th>
                                            <th class="sorting">Amount</th>
                                            <th class="sorting">User</th>
                                            <th class="sorting">Exp Date</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="exp in expanses.data" :key="exp.id">
                                            <td>
                                                <a  v-if="$page.props.auth.user.can.includes('expanse.show') ||
                                                $page.props.auth.user.role.includes('Administrator')"
                                                    href="javascript:void(0)" @click="editItem(exp.show_url, true)">
                                                    #EXP_{{ exp.id }}
                                                </a>
                                                <span v-else>
                                                    #EXP_{{ exp.id }}
                                                </span>

                                            </td>
<!--                                            <td>{{ exp.subject }}</td>-->
                                            <td>{{ exp.purpse.name }}</td>
                                            <td>{{ exp.method.name }}</td>
                                            <td>{{ exp.amount+" Tk" }}</td>
                                            <td>
                                                <a class="text-capitalize" :href="`/admin/users/${exp.user.id}`">
                                                    {{ exp.user.name }}
                                                </a>
                                            </td>
                                            <td>{{ exp.date }}</td>
                                            <td>{{ exp.created_at }}</td>
                                            <td>
                                                <button type="button"  @click="editItem(exp.show_url)"
                                                        v-if="$page.props.auth.user.can.includes('expanse.edit') || $page.props.auth.user.role.includes('Administrator') "
                                                        class="btn btn-icon btn-icon rounded-circle bg-light-warning waves-effect waves-float waves-light">
                                                    <Icon title="pencil"/>
                                                </button>

                                                <button @click="deleteItemModal(exp.id)" type="button"
                                                        v-if="$page.props.auth.user.can.includes('expanse.delete') || $page.props.auth.user.role.includes('Administrator') "
                                                        class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                                    <Icon title="trash"/>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="expanses.links" :from="expanses.from" :to="expanses.to" :total="expanses.total" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <Modal id="createData" title="Add New Expanse" v-vb-is:modal size="md">
        <form @submit.prevent="createData">
            <div class="modal-body">
                <div class="row mb-1 flex-column">
                    <div class="col mb-1">
                        <label>Purpose <Required/></label>
                        <v-select v-model="createForm.purpose_id"
                                  :options="purposes"
                                  class="form-control select-padding"
                                  :reduce="item => item.id"
                                  label="name"
                                  placeholder="Select Expanse Purpose"></v-select>
                        <span v-if="errors.purpose_id" class="error text-sm text-danger">{{ errors.purpose_id }}</span>
                    </div>

<!--                    <div class="col mb-1">
                        <label>Expanse Subject <Required/></label>
                        <input v-model="createForm.subject" type="text" placeholder="e.g expanse subject" class="form-control">
                        <span v-if="errors.subject" class="error text-sm text-danger">{{ errors.subject }}</span>
                    </div>-->

                    <div class="col mb-1">
                        <label>Expanse Amount <Required/></label>
                        <input v-model="createForm.amount" type="text" placeholder="e.g 00.00 Tk" class="form-control">
                        <span v-if="errors.amount" class="error text-sm text-danger">{{ errors.amount }}</span>
                    </div>
                    <div class="col mb-1">
                        <label>Payment Method <Required/></label>
                        <v-select v-model="createForm.method_id"
                                  :options="methods"
                                  class="form-control select-padding"
                                  :reduce="item => item.id"
                                  label="name"
                                  placeholder="Select Payment Method"></v-select>
                        <span v-if="errors.method_id" class="error text-sm text-danger">{{ errors.method_id }}</span>
                    </div>
                    <div class="col-md mb-1">
                        <label>Expanse Date <Required/></label>
                        <Datepicker v-model="createForm.expanse_date"
                                    :monthChangeOnScroll="false"
                                    :enable-time-picker="false"
                                    :format="'dd-MM-Y'"
                                    placeholder="Select Date" autoApply></Datepicker>
                        <span v-if="errors.expanse_date" class="error text-sm text-danger">{{ errors.expanse_date }}</span>
                    </div>
                    <div class="col mb-1">
                        <ImageUploader v-model="createForm.document" label="Expanse Document" type="text" class="form-control"/>
                        <span v-if="errors.document" class="error text-sm text-danger">{{ errors.document }}</span>
                    </div>
                    <div class="col-md-12">
                        <Textarea v-model="createForm.details" label="Expanse Note" placeholder="e.g explain here about more details in this expanse."></Textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
            </div>
        </form>
    </Modal>


    <Modal id="updateData" title="Update Expanse" v-vb-is:modal size="md">
        <form @submit.prevent="updateData(editData.id)">
            <div class="modal-body">
                <div class="row mb-1 flex-column">
                    <div class="col mb-1">
                        <label>Purpose <Required/></label>
                        <v-select v-model="updateForm.purpose_id"
                                  class="form-control select-padding"
                                  :options="purposes"
                                  :reduce="item => item.id"
                                  label="name" placeholder="Select Expanse Purpose"></v-select>
                        <span v-if="errors.purpose_id" class="error text-sm text-danger">{{ errors }}</span>
                    </div>

<!--                    <div class="col mb-1">-->
<!--                        <label>Expanse Subject <Required/></label>-->
<!--                        <input v-model="updateForm.subject" type="text" placeholder="e.g expanse subject" class="form-control">-->
<!--                        <span v-if="errors.subject" class="error text-sm text-danger">{{ errors.subject }}</span>-->
<!--                    </div>-->
                    <div class="col mb-1">
                        <label>Expanse Amount <Required/></label>
                        <input v-model="updateForm.amount" type="text" placeholder="e.g 00.00 Tk" class="form-control">
                        <span v-if="errors.amount" class="error text-sm text-danger">{{ errors.amount }}</span>
                    </div>
                    <div class="col mb-1">
                        <label>Payment Method<Required/></label>
                        <v-select v-model="updateForm.method_id"
                                  :options="methods"
                                  class="form-control select-padding"
                                  :reduce="item => item.id"
                                  label="name"
                                  placeholder="Select Payment Method"></v-select>
                        <span v-if="errors.method_id" class="error text-sm text-danger">{{ errors.method_id }}</span>
                    </div>
                    <div class="col-md mb-1">
                        <label>Expanse Date <Required/></label>
                        <Datepicker v-model="updateForm.expanse_date"
                                    :monthChangeOnScroll="false"
                                    :enable-time-picker="false"
                                    :format="'dd-MM-Y'"
                                    placeholder="Select Date" autoApply></Datepicker>
                        <span v-if="errors.expanse_date" class="error text-sm text-danger">{{ errors.expanse_date }}</span>
                    </div>
                    <div class="col mb-1">
                        <ImageUploader v-model="updateForm.document" label="Expanse Document" type="text" class="form-control"/>
                        <span v-if="errors.document" class="error text-sm text-danger">{{ errors.document }}</span>
                        <a v-if="editData.document" :href="`/storage/${editData.document}`"
                           class="error text-sm text-danger d-flex gap-1 align-items-center">
                            <span>Existing File</span>
                            <vue-feather type="external-link" size="12"/>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <Textarea v-model="updateForm.details" label="Expanse Note" placeholder="e.g explain here about more details in this expanse."></Textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Save</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
            </div>
        </form>
    </Modal>


    <Modal id="showExpanse" title="Show Expanse" v-vb-is:modal size="md">
        <div class="modal-body">
            <h3>Purpose: {{ editData.purpse?.name }}</h3>
            <h5>Amount: {{ editData.amount }}</h5>
            <h5>Exp Date: {{ moment(editData.date).format('D-MM-y')}}</h5>
            <div class="flex flex-column mb-3" style="display:flex; flex-direction: column;" v-if="editData.document">
                <label for="image">Document</label>
                <img id="image" :src="`/storage/${editData.document}`" alt="" style="width: 100%;
    height: 100%;
    max-height: 250px;
    object-fit: contain;">
            </div>
            <small v-if="editData.details">Notes: {{ editData?.details }}</small>
        </div>
    </Modal>

</template>
<script>

</script>
<script setup>
import Pagination from "@/components/Pagination.vue"
import Icon from '@/components/Icon.vue'
import Modal from '@/components/Modal.vue'
import ImageUploader from "@/components/ImageUploader.vue"
import Textarea from "@/components/Textarea.vue";
import moment from "moment";

import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import axios from "axios";

let props = defineProps({
    purposes:[]|null,
    methods:[]|null,
    expanses: Object,
    filters: Object,
    notification:Object,
    errors:Object,
    platforms:Object,
    main_url: String,
});


let createForm = useForm({
    purpose_id:null,
    method_id:null,
    subject:null,
    amount:null,
    expanse_date:null,
    document:null,
    details:null,

    processing:Boolean,
})

const addExpanseModal = () => document.getElementById('createData').$vb.modal.show();

let createData = () => {
    router.post(props.main_url, createForm,{
        preserveState: true,
        onStart: () => {
            createForm.processing = true
        },
        onFinish: () => {
            createForm.processing = false
        },
        onSuccess: () => {
            document.getElementById('createData').$vb.modal.hide()
            createForm.reset()
            Swal.fire(
                'Saved!',
                'Your file has been Added.',
                'success'
            )
        },
        onError: (error) =>{
            console.log(error);
        }
    })
}



let updateForm = useForm({
    purpose_id:null,
    method_id:null,
    subject:null,
    amount:null,
    expanse_date:null,
    document:null,
    details:null,

    processing:Boolean,
})
let editData = ref([]);

const editItem = (url, isShow=false) =>{
    axios.get(url+"/?data=true").then((res)=>{
        console.log(res.data.date)
        editData.value = res.data;
        updateForm.purpose_id = res.data.purpse;
        updateForm.method_id = res.data.method;
        updateForm.subject = res.data.subject;
        updateForm.amount = res.data.amount;
        updateForm.expanse_date = res.data.date;
        updateForm.details = res.data.details;

        if(isShow){
            document.getElementById('showExpanse').$vb.modal.show()
        }else{
            document.getElementById('updateData').$vb.modal.show()
        }
    }).catch((err) =>{
        console.log(err);
    });
}

const updateData = (id) =>{
    updateForm.post(`update-expance/${id}`,{
        preserveState: true,
        onStart: () => {
            createForm.processing = true
        },
        onFinish: () => {
            createForm.processing = false
        },
        onSuccess: () => {
            document.getElementById('updateData').$vb.modal.hide()
            createForm.reset()
            Swal.fire(
                'Saved!',
                'Your file has been Updated.',
                'success'
            )
        },
    })
}


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
            router.delete( 'expense/' + id, { preserveState: true, replace: true, onSuccess: page => {
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
};

let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage], debounce(function ([val, val2]) {
    router.get(props.main_url, { search: val, perPage: val2 }, { preserveState: true, replace: true });
}, 300));





</script>

<style lang="scss">
/*@import "../../../../sass/base/plugins/tables/datatables";*/
</style>
