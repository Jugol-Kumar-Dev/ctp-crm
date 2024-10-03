<template>
    <Head title="Show Service"/>

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
                                    <h4 class="card-title">{{ service?.service_name }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-md-12">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">Packages</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
                                        role="tab" aria-controls="nav-profile" aria-selected="false">Features</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <h2>Packages</h2>
                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input
                                                        v-model="filterWordPackage"
                                                        type="search"
                                                        class="form-control"
                                                        placeholder="Search Now"
                                                        aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>
                                        <button v-if="this.$page.props.auth.user.can.includes('packages.create') || this.$page.props.auth.user.role.includes('Administrator')"
                                                                                           @click="addDataModal" class="btn btn-primary">Add New</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4" v-for="pac in filterPackage">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="text-capitalize">{{ pac.name }}</h5>
                                                    <CDropdown
                                                        v-if="this.$page.props.auth.user.can.includes('packages.delete') || this.$page.props.auth.user.can.includes('packages.edit') || this.$page.props.auth.user.role.includes('Administrator')">
                                                        <CDropdownToggle class="p-0">
                                                            <vue-feather type="more-vertical" />
                                                        </CDropdownToggle>
                                                        <CDropdownMenu>
                                                            <CDropdownItem
                                                                @click="editPackage(pac.id)"
                                                                v-if="this.$page.props.auth.user.can.includes('packages.edit') || this.$page.props.auth.user.role.includes('Administrator')"
                                                            >
                                                                <vue-feather type="edit" size="15"/>
                                                                <span class="ms-1">Edit</span>
                                                            </CDropdownItem>

                                                            <CDropdownItem
                                                                v-if="this.$page.props.auth.user.can.includes('packages.delete') || this.$page.props.auth.user.role.includes('Administrator')"
                                                                @click="deleteItem(`${props.main_url}/delete-package`, pac.id)">
                                                                <vue-feather type="trash-2" size="15"/>
                                                                <span class="ms-1">Delete</span>
                                                            </CDropdownItem>
                                                        </CDropdownMenu>
                                                    </CDropdown>
                                                </div>
                                                <p class="text-capitalize">Price: {{ pac.price }}</p>
                                                <p class="package-description" v-html="pac.descriptions"></p>
                                                <small>Position: {{ pac.position }}</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <h2>Features</h2>


                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input
                                                        v-model="filterWordFeatured"
                                                        type="search"
                                                        class="form-control"
                                                        placeholder="Search Now"
                                                        aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>

                                        <button @click="addNewFeture" class="btn btn-primary" v-if="this.$page.props.auth.user.can.includes('features.create') || this.$page.props.auth.user.role.includes('Administrator')">Add New</button>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-responsive pt-0 px-2">
                                            <table class="user-list-table table table-striped">
                                                <thead class="table-light">
                                                <tr class=null>
                                                    <th class="sorting">Id</th>
                                                    <th class="sorting">Name</th>
                                                    <th class="sorting">Price</th>
                                                    <th class="sorting">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="feature in filterFeatured" :key="feature.id">
                                                    <td>{{ feature?.id }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <strong>{{ feature?.name }}</strong>
                                                            <small>Position: {{ feature?.position }}</small>
                                                        </div>
                                                    </td>
                                                    <td>{{ feature?.price }}</td>
                                                    <td>
                                                        <CDropdown  v-if="this.$page.props.auth.user.can.includes('features.edit') ||
                                                        this.$page.props.auth.user.can.includes('features.delete') ||
                                                        this.$page.props.auth.user.role.includes('Administrator')">
                                                            <CDropdownToggle>
                                                                <vue-feather type="more-vertical" />
                                                            </CDropdownToggle>
                                                            <CDropdownMenu>
                                                                <CDropdownItem  @click="editFeatured(feature.id)"   v-if="this.$page.props.auth.user.can.includes('features.edit') || this.$page.props.auth.user.role.includes('Administrator')">
                                                                    <Icon title="pencil" />
                                                                    <span class="ms-1">Edit</span>
                                                                </CDropdownItem>

                                                                <CDropdownItem @click="deleteItem(`${props.main_url}/delete-feature`, feature?.id)" type="button"
                                                                               v-if="this.$page.props.auth.user.can.includes('features.delete') || this.$page.props.auth.user.role.includes('Administrator') ">
                                                                    <Icon title="trash" />
                                                                    <span class="ms-1">Delete</span>
                                                                </CDropdownItem>
                                                            </CDropdownMenu>
                                                        </CDropdown>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
<!--                                            <Pagination :links="service?.features.links" :from="service?.features.from" :to="service?.features.to" :total="service?.features.total"/>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <Modal id="createPackage" :title="editPackageData ? 'Edit Package' : 'Add New Package'" v-vb-is:modal size="lg">
        <form @submit.prevent="addPackage">
            <div class="modal-body">
                <div class="mb-1">
                    <label class="form-label">Name</label>
                    <input v-model="formData.name" type="text" class="form-control" placeholder="e.g Package name">
                    <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                </div>

                <div class="mb-1">
                    <label class="form-label">Price</label>
                    <input v-model="formData.price" type="number" class="form-control" placeholder="e.g Package price">
                    <span v-if="errors.price" class="error text-sm text-danger">{{ errors.price }}</span>
                </div>
                <div class="">
                    <label class="form-label">Details</label>
                    <textarea v-model="formData.descriptions" class="form-control" rows="5" placeholder="e.g What you think about this package"></textarea>
                    <span v-if="errors.descriptions" class="error text-sm text-danger">{{ errors.descriptions }}</span>
                </div>
                <div class="mb-1">
                    <label class="form-label">Position</label>
                    <input v-model="formData.position" type="number" class="form-control" placeholder="e.g Order Position">
                    <span v-if="errors.position" class="error text-sm text-danger">{{ errors.position }}</span>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>


    <Modal id="createFeature" :title="editFeaturedData ? 'Edit Feature' : 'Add New Feature'" v-vb-is:modal size="lg">
        <form @submit.prevent="addFetaure">
            <div class="modal-body">
                <div class="mb-1">
                    <label class="form-label">Name</label>
                    <input v-model="featureForm.name" type="text" class="form-control" placeholder="e.g Package name">
                    <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>

                </div>
                <div class="mb-1">
                    <label class="form-label">Price</label>
                    <input v-model="featureForm.price" type="number" class="form-control" placeholder="e.g Package price">
                    <span v-if="errors.price" class="error text-sm text-danger">{{ errors.price }}</span>
                </div>
                <div class="mb-1">
                    <label class="form-label">Position</label>
                    <input v-model="featureForm.position" type="text" class="form-control" placeholder="e.g Featured Position">
                    <span v-if="errors.position" class="error text-sm text-danger">{{ errors.position }}</span>

                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>



</template>

<script setup>
import Modal from "@/components/Modal.vue";



import {useAction} from "../../composables/useAction";
import {computed, ref, watch} from "vue";
import {useForm} from "@inertiajs/vue3";
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
import {router} from "@inertiajs/vue3";
import axios from "axios";
import debounce from "lodash/debounce";
import Pagination from "@/components/Pagination.vue";
const {swalSuccess, deleteItem} = useAction()

const props = defineProps({
    service: Object | {} | null,
    main_url:String|null,
    save_packages:String|null,
    save_feature:String|null,
    errors: Object,
})
const  processing = ref(false)

const formData = useForm({
    serviceId:props.service?.id,
    name:null,
    price:null,
    position:null,
    descriptions:null,
})
const editPackageData = ref(null);
let addDataModal = () => {
    reset()
    document.getElementById('createPackage').$vb.modal.show()
}
const editPackage = (id) =>{
    axios.get(`${props.main_url}/edit-package/${id}`).then((res)=>{
        editPackageData.value = res.data
        formData.name = res.data.name
        formData.price = res.data.price;
        formData.position = res.data.position;
        formData.descriptions = res.data.descriptions
        document.getElementById('createPackage').$vb.modal.show()
    })
}
const addPackage = ()=>{
    if(editPackageData.value == null){
        formData.post(props.save_packages,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> {
                document.getElementById('createPackage').$vb.modal.hide()
                formData.reset()
                $toast.success("Package Saved.")
            },
        })
    }else{
        formData.put(props.main_url+'/update-package/'+editPackageData.value.id,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> {
                document.getElementById('createPackage').$vb.modal.hide()
                formData.reset()
                $toast.success("Package Updated.")
            },
        })
    }
}



const editFeaturedData = ref(null)
const featureForm = useForm({
    serviceId:props.service?.id,
    name:null,
    position:null,
    price:null,
})
let addNewFeture = () => {
    document.getElementById('createFeature').$vb.modal.show()
}
const editFeatured = (id) =>{
    axios.get(`${props.main_url}/edit-feature/${id}`).then((res)=>{
        editFeaturedData.value = res.data
        featureForm.name = res.data.name
        featureForm.position = res.data.position
        featureForm.price = res.data.price
        document.getElementById('createFeature').$vb.modal.show()
    })
}
const addFetaure = ()=>{
    if(editFeaturedData.value === null){
        featureForm.post(props.save_feature,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> {
                document.getElementById('createFeature').$vb.modal.hide()
                featureForm.reset()
                reset()
                $toast.success("Feature Saved.")
            },
        })
    }else {
        featureForm.put(props.main_url + '/update-feature/' + editFeaturedData.value.id, {
            preserveState: true,
            onStart: () => {
                processing.value = true
            },
            onFinish: () => {
                processing.value = false
            },
            onSuccess: () => {
                document.getElementById('createFeature').$vb.modal.hide()
                featureForm.reset()
                reset()
                $toast.success("Featured Updated.")
            },
        })
    }
}



const reset =()=>{
    formData.name = null
    formData.price = null

    featureForm.name = null
    featureForm.price = null

    formData.descriptions = null
    editPackageData.value = null
    editFeaturedData.value = null
}


/*
*
* this is for featured sections
*
* */

const filterWordFeatured = ref(null)
const filterFeatured = computed(()=>{
    if(filterWordFeatured.value){
        return props.service?.features?.filter(item => {
            const allProperties = Object.values(item).join(' ').toLowerCase();
            return allProperties.includes(filterWordFeatured.value?.toLowerCase());
        })
    }else{
        return props.service?.features;
    }
})



const filterWordPackage = ref(null)
const filterPackage = computed(()=>{
    if(filterWordPackage.value){
        return props.service?.packages?.filter(item => {
            const allProperties = Object.values(item).join(' ').toLowerCase();
            return allProperties.includes(filterWordPackage.value?.toLowerCase());
        })
    }else{
        return props.service?.packages;
    }
})



</script>

<style lang="scss">
.package-description{
    white-space: break-spaces;
}
</style>
