<template>
    <Head title="Payment Method Management"/>

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
                                    <h4 class="card-title">Methods Information's </h4>
                                    <button class="dt-button add-new btn btn-primary"
                                            v-if="this.$page.props.auth.user.can.includes('method.create') || this.$page.props.auth.user.role.includes('Administrator')"
                                            @click="createDataModal">Add Method</button>
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
                                    <table class="user-list-table table">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
                                            <th class="sorting">Name</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="method in moethods.data" :key="method.id">
                                            <td>{{ method.id }}</td>
                                            <td>{{ method.name }}</td>
                                            <td>{{ method.created_at }}</td>
                                            <td>
                                                <div class="demo-inline-spacing">
                                                    <button @click="deleteItem(props.main_url, method.id)"
                                                            v-if="this.$page.props.auth.user.can.includes('method.create') || this.$page.props.auth.user.role.includes('Administrator')"
                                                            type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light btn-danger">
                                                        <Icon title="trash" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="moethods.links" :from="moethods.from" :to="moethods.to" :total="moethods.total" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>


    <Modal id="createData" title="Add Method" v-vb-is:modal size="md">
        <form @submit.prevent="createData">
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
                <button type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
            </div>
        </form>
    </Modal>


</template>
<script>

</script>
<script setup>
    import Pagination from "../../../components/Pagination"
    import Icon from '../../../components/Icon'
    import Modal from '../../../components/Modal'
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {Inertia} from "@inertiajs/inertia";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/inertia-vue3";
    import axios from "axios";
    import {useAction} from "../../../composables/useAction";
    const {deleteItem} = useAction()

    let props = defineProps({
        moethods: Object,
        filters: Object,
        notification:Object,
        errors:Object,
        platforms:Object,
        main_url: String,
    });


    let updateForm = useForm({
        name:null,
    })


    const createDataModal = () => document.getElementById('createData').$vb.modal.show()
    let createData = () => {
        updateForm.post(props.main_url,{
            preserveState: true,
            onSuccess: () => {
                document.getElementById('createData').$vb.modal.hide()
                updateForm.reset()
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
        Inertia.get(props.main_url, { search: val, perPage: val2 }, { preserveState: true, replace: true });
    }, 300));





</script>

<style lang="scss">
    /*@import "../../../../sass/base/plugins/tables/datatables";*/
</style>
