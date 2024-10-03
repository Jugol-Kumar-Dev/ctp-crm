<template>
    <Head title="Service Management"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Service Information's </h4>
                                    <div
                                        class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                        <div class="select-search-area">
                                            <label>Search:<input v-model="search" type="search"
                                                                 class="form-control"
                                                                 placeholder="Search Now"
                                                                 aria-controls="DataTables_Table_0"></label>
                                        </div>
                                    </div>
                                    <button class="dt-button add-new btn btn-primary"
                                            v-if="this.$page.props.auth.user.can.includes('services.create') ||
                                            this.$page.props.auth.user.role.includes('Administrator')"
                                    @click="addServiceModal">
                                        Add Service
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="row match-height">
                    <div>
                        <ul>
                            <li class="text-danger" v-for="error in formData.errors" v-text="error"></li>
                        </ul>
                    </div>
                    <div class="col-md-3" v-for="item in props.services.data" :key="item.id">
<!--                        <a :href="item.show_url">-->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">


                                        <a :href="item.show_url" v-if="this.$page.props.auth.user.can.includes('services.show') || this.$page.props.auth.user.role.includes('Administrator')">
                                            <h2 class="card-title">{{ item.name }}</h2>
                                            <small>Position:  {{ item.position }}</small>
                                        </a>

                                        <div v-else>
                                            <h2 class="card-title">{{ item.name }}</h2>
                                            <small>Position:  {{ item.position }}</small>
                                        </div>
                                        <CDropdown v-if="this.$page.props.auth.user.can.includes('services.delete') ||
                                        this.$page.props.auth.user.can.includes('services.show') ||
                                        this.$page.props.auth.user.can.includes('services.edit') ||
                                        this.$page.props.auth.user.role.includes('Administrator')">
                                            <CDropdownToggle class="p-0">
                                                <vue-feather type="more-vertical" />
                                            </CDropdownToggle>
                                            <CDropdownMenu>

                                                <CDropdownItem :href="item.show_url"
                                                               v-if="this.$page.props.auth.user.can.includes('services.show') || this.$page.props.auth.user.role.includes('Administrator')">
                                                    <vue-feather type="eye" size="15"/>
                                                    <span class="ms-1">Show</span>
                                                </CDropdownItem>

                                                <CDropdownItem @click="editService(item.edit_url)"
                                                               v-if="this.$page.props.auth.user.can.includes('services.edit')  || this.$page.props.auth.user.role.includes('Administrator')">
                                                    <vue-feather type="edit" size="15"/>
                                                    <span class="ms-1">Edit</span>
                                                </CDropdownItem>

                                                <CDropdownItem @click="deleteItem(props.main_url, item.id)"
                                                               v-if="this.$page.props.auth.user.can.includes('services.delete') || this.$page.props.auth.user.role.includes('Administrator') ">
                                                    <vue-feather type="trash-2" size="15"/>
                                                    <span class="ms-1">Delete</span>
                                                </CDropdownItem>
                                            </CDropdownMenu>
                                        </CDropdown>
                                    </div>
                                    <span class="badge bg-primary"  style="margin-right:5px;" v-for="plat in item.platforms">
                                        {{ plat.name }}
                                    </span>
                                </div>
                            </div>
<!--                        </a>-->
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <Pagination :links="services.links" :from="services.from" :to="services.to" :total="services.total"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Add Payment Sidebar -->
    <div class="modal modal-slide-in fade" id="addServices" aria-hidden="true" v-vb-is:modal>
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">{{ serviceId ? 'Edit This Service' : 'Add New Service' }}</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form @submit.prevent="saveService">
                        <div class="mb-1">
                            <label class="form-label" for="amount">Service Name</label>
                            <input class="form-control"
                                   id="amount"
                                   v-model="formData.serviceName"
                                   type="text" placeholder="e.g Web Development"/>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="orderLevel">Order Level</label>
                            <input class="form-control"
                                   id="orderLevel"
                                   v-model="formData.serviceOrder"
                                   type="number" placeholder="e.g Service position"/>
                        </div>

<!--                        <div class="mb-1">
                            <label class="form-label" for="amount">Service Platforms</label>
                            <v-select  label="name"
                                       :options="props.platforms"
                                       :reduce="item => item.id"
                                       multiple
                                       class="form-control select-padding"
                                       v-model="formData.platforms"
                                       placeholder="e.g Laravel, vue js, react js etc">
                            </v-select>
                        </div>-->


                        <div class="d-flex flex-wrap mb-0">
                            <button type="submit" class="btn btn-primary me-1" data-bs-dismiss="modal">Save</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Payment Sidebar -->

</template>
<script>
import Modal from "@/components/Modal.vue";



</script>
<script setup>

    import {useAction} from "../../composables/useAction";
    import {ref, watch} from "vue";
    import {useForm} from "@inertiajs/vue3";
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
    import {router} from "@inertiajs/vue3";
    import axios from "axios";
    import debounce from "lodash/debounce";
    import Pagination from "@/components/Pagination.vue";


    const {swalSuccess, deleteItem} = useAction()

    const props = defineProps({
        services: [],
        platforms:[],
        filters: Object,
        notification:Object,
        errors:Object,
        main_url: String,
    });


    const processing = ref(false);
    const formData = useForm({
        serviceId:null,
        serviceOrder:null,
        serviceName:null,
        platforms:[],
        errors:Object,
    })


    const serviceId = ref(null);
    const saveService =()=> {
        if (serviceId.value !== null){
            updateService();
        }else{
            addService()
        }
    }

    const addServiceModal =()=>{
        formData.reset();
        serviceId.value = null
        document.getElementById('addServices').$vb.modal.show()
    }
    const addService = ( )=>{
        formData.post(props.main_url,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: (data)=> {
                document.getElementById('addServices').$vb.modal.hide()
            },
        })
    }

    const updateService = ( )=>{
        formData.put(props.main_url+'/'+serviceId.value,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: (data)=> {
                document.getElementById('addServices').$vb.modal.hide()
            },
        })
    }


    const editDataId = ref(null)
    const editService = (url) => {
        axios.get(url).then((res)=>{
            serviceId.value = res.data.id;
            formData.serviceId = res.data.id;
            formData.serviceName = res.data.service_name;
            formData.serviceOrder = res.data.position;
            formData.platforms = JSON.parse(res.data.platforms)
            document.getElementById('addServices').$vb.modal.show()
        })

    }

    const search = ref(props.filters.search);
    const perPage = ref(props.filters.perPage);
    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.main_url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>

<style lang="scss">

</style>
