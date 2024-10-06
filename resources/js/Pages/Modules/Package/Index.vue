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
                                    <h4 class="card-title">Package Information's </h4>
                                    <button
                                        class="dt-button add-new btn btn-primary"
                                        @click="addDataModal"
                                    >
                                        Add Package
                                    </button>

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
                                                </select> entries</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                                            <div
                                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                                <div class="select-search-area">
                                                    <label>Search:<input v-model="search" type="search" class="form-control" placeholder=""
                                                                         aria-controls="DataTables_Table_0"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="package-list-table table">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
                                            <th class="sorting">Name</th>
                                            <th class="sorting">Price</th>
                                            <th class="sorting">Description</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="item in packages.data" :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.price }} </td>
                                            <td v-html="item.description"></td>
                                            <td>{{ item.created_at }}</td>
                                            <td>
                                                <div class="demo-inline-spacing">
                                                    <button type="button" @click="editItem(item.show_url)"
                                                            class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light">
                                                        <Icon title="eye"/>
                                                    </button>
                                                    <button @click="deleteItemModal(item.id)" type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light btn-danger">
                                                        <Icon title="trash" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="packages.links" :from="packages.from" :to="packages.to" :total="packages.total" />
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





    <Modal id="createPackage" title="Add New Package" v-vb-is:modal size="lg">
        <form @submit.prevent="createPackage">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Package Name: <Required/></label>
                        <div class="">
                            <input v-model="createForm.name" type="text" placeholder="Package Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Price: <Required/></label>
                        <div class="">
                            <input v-model="createForm.price" type="number" placeholder="Package Price" class="form-control">
                            <span v-if="errors.price" class="error text-sm text-danger">{{ errors.price }}</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Details: </label>
                        <textarea v-model="createForm.description" type="text" placeholder="address" rows="5" class="form-control"></textarea>
                        <span v-if="errors.description" class="error text-sm text-danger">{{ errors.description }}</span>
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

    <Modal id="editData" title="Edit Package" v-vb-is:modal size="lg">
        <form @submit.prevent="updateData(editData.id)">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Package Name: <Required/></label>
                        <div class="">
                            <input v-model="updateForm.name" type="text" placeholder="Package Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Price: <Required/></label>
                        <div class="">
                            <input v-model="updateForm.price" type="number" placeholder="Package Price" class="form-control">
                            <span v-if="errors.price" class="error text-sm text-danger">{{ errors.price }}</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Details: </label>
                        <textarea v-model="updateForm.description" type="text" placeholder="address" rows="5" class="form-control"></textarea>
                        <span v-if="errors.description" class="error text-sm text-danger">{{ errors.description }}</span>
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
<script>

</script>
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



    // defineProps({
    //     packages:Array,
    //     notification:Array,
    // });

    let props = defineProps({
        packages: Object,
        filters: Object,
        //   can: Object,
        notification:Object,
        errors:Object,
        main_url: String,
    });
    let addDataModal = () => {
        document.getElementById('createPackage').$vb.modal.show()
    }

    let createForm = useForm({
        name:"",
        price:"",
        description:"",

        processing:Boolean,
    })

    let updateForm = useForm({
        name:"",
        price:"",
        description:"",

        processing:Boolean,
    })

    let editData = ref([]);

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
                router.delete( 'designs/' + id, { preserveState: true, replace: true, onSuccess: page => {
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


    let createPackage = ( )=>{
        router.post('designs', createForm, {
            preserveState: true,
            onStart: () =>{ createForm.processing = true},
            onFinish: () => {createForm.processing = false},
            onSuccess: ()=> {
                document.getElementById('createPackage').$vb.modal.hide()
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
            updateForm.price = res.data.price;
            updateForm.description = res.data.description;

            document.getElementById('editData').$vb.modal.show();
        }).catch(err => {
            console.log(err);
        });
    }

    let updateData = (id) => {
        router.put('designs/' + id, updateForm, {
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
