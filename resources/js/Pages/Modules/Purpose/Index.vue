<template>
    <Head title="Purpose Management"/>

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
                                    <h4 class="card-title">Purposes Information's </h4>
                                    <button class="dt-button add-new btn btn-primary"
                                            v-if="$page.props.auth.user.can.includes('purpose.create')  || $page.props.auth.user.role.includes('Administrator')"
                                            tabindex="0" type="button"
                                            @click="addPurpose"
                                    >Add Purposes</button>
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
                                                    <label>Search:<input v-model="search" type="search" class="form-control" placeholder="Search Now"
                                                                         aria-controls="DataTables_Table_0"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="purpose-list-table table">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
                                            <th class="sorting">Name</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="purpose in purposes.data" :key="purpose.id">
                                            <td>{{ purpose.id }}</td>
                                            <td>{{ purpose.name }}</td>
                                            <td>{{ purpose.created_at }}</td>
                                            <td>
                                                <div class="demo-inline-spacing">
                                                    <vue-feather class="cursor-pointer text-info"
                                                                 v-if="$page.props.auth.user.can.includes('purpose.edit')  || $page.props.auth.user.role.includes('Administrator')"
                                                                 size="15" type="edit" @click="editItem(purpose.show_url)"/>
                                                    <vue-feather class="cursor-pointer text-danger" size="15"
                                                                 v-if="$page.props.auth.user.can.includes('purpose.delete')  || $page.props.auth.user.role.includes('Administrator')"
                                                                 type="trash-2" @click="deleteItem(props.main_url, purpose.id)"/>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="purposes.links" :from="purposes.from" :to="purposes.to" :total="purposes.total" />
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




    <Modal id="createPurpose" title="Add New Purpose" v-vb-is:modal :size="{defalut:'lg'}">
        <form @submit.prevent="createPurpose">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Purpose Name: <Required/></label>
                        <div class="">
                            <input v-model="createForm.name" type="text" placeholder="Purpose Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
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


    <Modal id="editData" title="Edit Purpose" v-vb-is:modal :size="{defalut:'lg'}">
        <form @submit.prevent="updateData(editData.id)">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Method Name: <Required/></label>
                        <div class="">
                            <input v-model="updateForm.name" type="text" placeholder="Method Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
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



</template>

<script setup>
    import Pagination from "@/components/Pagination.vue"
    import Icon from '@/components/Icon.vue'
    import Modal from '@/components/Modal.vue'
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {router} from "@inertiajs/vue3";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/vue3";
    import axios from "axios";
    import {useAction} from "@/composables/useAction.js";

    const {deleteItem} = useAction();

    let props = defineProps({
        purposes: Object,
        filters: Object,
        //   can: Object,
        notification:Object,
        errors:Object,
        platforms:Object,
        main_url: String,
    });

    const addPurpose = () => document.getElementById('createPurpose').$vb.modal.show()


    let editData = ref([]);

    let createForm = useForm({
        name:"",

        processing:Boolean,
    })

    let updateForm = useForm({
        name:"",
    })



    let createPurpose  = ( )=>{
        router.post('purposes', createForm, {
            preserveState: true,
            onStart: () =>{ createForm.processing = true},
            onFinish: () => {createForm.processing = false},
            onSuccess: ()=> {
                document.getElementById('createPurpose').$vb.modal.hide()
                createForm.reset()
                Swal.fire(
                    'Saved!',
                    'Your file has been Saved.',
                    'success'
                )
            },
        })
    }

    let editItem = (url) => {
        axios.get(url).then(res => {
            editData.value = res.data;
            updateForm.name = res.data.name;
            document.getElementById('editData').$vb.modal.show();
        }).catch(err => {
            console.log(err);
        });
    }

    let updateData = (id) => {
        router.put('purposes/' + id, updateForm, {
            preserveState: true,
            onStart: () => {
                createForm.processing = true
            },
            onFinish: () => {
                createForm.processing = false
            },
            onSuccess: () => {
                document.getElementById('editData').$vb.modal.hide()
                createForm.reset()
                Swal.fire(
                    'Saved!',
                    'Your file has been Updated.',
                    'success'
                )
            },
        })
    }

    let search = ref(props.filters.search);
    let perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.main_url, { search: val, perPage: val2 }, { preserveState: true, replace: true });
    }, 300));





</script>

<style lang="scss">
    /*@import "../../../../sass/base/plugins/tables/datatables";*/
</style>
