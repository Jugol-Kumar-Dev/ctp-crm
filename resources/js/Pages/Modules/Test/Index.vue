<template>

    <Head title="Category List" />
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Category List</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
<!--                <button class="dt-button add-new btn btn-primary"-->
<!--                        preserve-scroll-->
<!--                        tabindex="0"-->
<!--                        type="button"-->
<!--                        data-bs-toggle="modal"-->
<!--                        data-bs-target="#createNewTest">-->
<!--                    <span>Add New Category</span>-->
<!--                </button>-->
                <button class="dt-button add-new btn btn-primary" @click="openAddNewItemModal">
                    Add new item
                </button>

            </div>
        </div>
    </div>
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                    <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
                        <div class="select-search-area">
                            <label>Show <select class="form-select" v-model="perPage">
                                <option :value="undefined">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                        <div
                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                            <div class="select-search-area">
                                <label>Search:<input v-model="search" type="search" class="form-control"
                                                     placeholder="Type here for search"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="user-list-table table">
                    <thead class="table-light">
                    <tr class="">
                        <th class="sorting">Name</th>
                        <th class="sorting">Slug</th>
                        <th class="sorting">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="category in categories.data" :key="category.id">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td>
                            <div class="demo-inline-spacing">
                                <button type="button"
                                        @click="editItem(category.id)"
                                        class="btn btn-icon btn-icon rounded-circle bg-light-warning waves-effect waves-float waves-light">
                                    <Icon title="eye" />
                                </button>

                                <button class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                    <Icon title="trash" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <Pagination :links="categories.links" :from="categories.from" :to="categories.to"
                            :total="categories.total" />
            </div>
        </div>
        <!-- list and filter end -->


        <Modal id="createNewTest" title="edit New Category" v-vb-is:modal>
            <form @submit.prevent="createNewCategory">
                <div class="modal-body">
                    <input class="form-control" v-model="createForm.name" placeholder="Category Name" />
                </div>
                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                </div>
            </form>
        </Modal>

    </section>


<!--
    <Modal id="createNewTest" title="Edit Domains" v-vb-is:modal size="lg">
        <form @submit.prevent="updateData(editData.id)">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Domain Name: <Required/></label>
                        <div class="">
                            <input v-model="updateForm.name" type="text" placeholder="Domain Name" class="form-control">
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

-->




</template>


<script setup>

    import Pagination from "../../../components/Pagination"
    import Icon from '../../../components/Icon'
    import Modal from '../../../components/Modal'
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {router} from "@inertiajs/vue3";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/vue3";
    import axios from "axios";

    let props = defineProps({
        categories: Object,
        filters: Object,
        url: String,
    });

    let createForm = useForm({
        name: '',
    });

    let updateForm = useForm({
        name: ''
    })

    let createNewCategory = () => {
        createForm.post('/test/create', {
            onSuccess: () => {
                document.getElementById('createNewTest').$vb.modal.hide()
                createForm.reset()
            }
        });
    }

    let editData = ref([])


    let editItem = (id) => {
        axios.get('test/edit/'+id).then(res =>{
            document.getElementById('createNewCategory').$vb.modal.show()
            editData.value = res.data;
            updateForm.name = res.data.name
        }).catch(err => {
            console.log(err)
        })
    }

    let updateData = (id) => {
        router.put('/test/update/' + id, updateForm, {
            onSuccess: () => {
                document.getElementById('editData').$vb.modal.hide()
                updateForm.reset()
            },
        })
    }

    let openAddNewItemModal = () =>{
        document.getElementById('createNewTest').$vb.modal.show()
    }



    let search = ref(props.filters.search);
    let perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.url, { search: val, perPage: val2 }, { preserveState: true, replace: true });
    }, 300));




</script>
