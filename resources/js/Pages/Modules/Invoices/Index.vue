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
                                    <h4 class="card-title">Invoices Information's </h4>
                                    <a class="dt-button add-new btn btn-primary" href="invoices/create">Add Invoices</a>
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
                                    <table class="user-list-table table">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">#id</th>
                                            <th class="sorting">Name</th>
                                            <th class="sorting">Creator</th>
                                            <th class="sorting">Subject</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="invoice in invoices.data" :key="invoice.id">
                                            <td>{{ invoice.invoice_id+''+invoice.id }}</td>
                                            <td>{{ invoice.name }}</td>
                                            <td>{{ invoice.creator }}</td>
                                            <td>{{ invoice.invoice.subject }} </td>
                                            <td>{{ invoice.created_at }}</td>
                                            <td>

                                                <CDropdown>
                                                    <CDropdownToggle>
                                                        <vue-feather type="more-vertical" />
                                                    </CDropdownToggle>
                                                    <CDropdownMenu>
                                                        <CDropdownItem :href="invoice.edit_url" v-if="$page.props.auth.user.can.includes('invoice.edit') || $page.props.auth.user.role.includes('Administrator') ">
                                                            <Icon title="pencil" />
                                                            <span class="ms-1">Edit</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem :href="invoice.invice_url" v-if="$page.props.auth.user.can.includes('invoice.show') || $page.props.auth.user.role.includes('Administrator') ">
                                                            <Icon title="eye" />
                                                            <span class="ms-1">Show</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem @click="deleteItemModal(invoice.delete_url)" v-if="$page.props.auth.user.can.includes('invoice.delete') || $page.props.auth.user.role.includes('Administrator') ">
                                                            <Icon title="trash" />
                                                            <span class="ms-1">Delete</span>
                                                        </CDropdownItem>
                                                    </CDropdownMenu>
                                                </CDropdown>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="invoices.links" :from="invoices.from" :to="invoices.to" :total="invoices.total" />
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
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'



    // defineProps({
    //     users:Array,
    //     notification:Array,
    // });

    let props = defineProps({
        invoices: Object,
        filters: Object,
        notification:Object,
        main_url: '',

    });

    let createForm = useForm({
        name:"",
        processing:Boolean,
    })


    let deleteItemModal = (url) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7d30d6',
            cancelButtonColor: '#ea5455',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(url, { preserveState: true, replace: true, onSuccess: page => {
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

    let editITem = (id) =>{
        router.get('invoices/'+id)
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
