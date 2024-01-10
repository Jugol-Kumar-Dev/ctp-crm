<template>
    <Head title="Show Project"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <section class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 mb-2">
                        <!-- Page header -->
                        <div class="d-lg-flex align-items-center justify-content-between">
                            <div class="mb-2 mb-lg-0">
                                <a :href="info.name" target="_blank">
                                    <h1 class="mb-0 h2 fw-bold text-capitalize">{{ info.name }}</h1>
                                </a>
                            </div>
                            <div class="d-flex align-items-center">
                                <!-- avatar group -->

                                <div class="avatar-group">
                                    <div v-c-tooltip="developer.name"
                                        title=""
                                        class="avatar pull-up"
                                        data-bs-original-title="Elicia Rieske"
                                        v-for="(developer, index) in info.users" :key="developer.id">
                                        <img :src="developer.photo" alt="Avatar" height="30" width="30">
                                    </div>
                                </div>
                                <div class="pull-up add-new-user ms-2"  v-c-tooltip="'Assign New User'" data-bs-toggle="modal"
                                     data-bs-target="#assignedDeveloper">
                                    +
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- row  -->
                <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#overview"
                                    type="button" role="tab"
                                    aria-controls="overview"
                                    aria-selected="true">Overview</button>
                        </li>

                        <li class="nav-item" role="presentation" v-if="
                            this.$page.props.auth.user.can.includes('project.clients') ||
                            this.$page.props.auth.user.role.includes('Administrator')">
                            <button class="nav-link"
                                    id="client-tab" data-bs-toggle="tab"
                                    data-bs-target="#client" type="button"
                                    role="tab" aria-controls="client"
                                    aria-selected="false">Client</button>
                        </li>
                        <li class="nav-item" role="presentation" v-if="props.info.invoice">
                            <button class="nav-link"
                                    v-if="this.$page.props.auth.user.can.includes('project.budget') || this.$page.props.auth.user.role.includes('Administrator')"
                                    id="billing-tab" data-bs-toggle="tab"
                                    data-bs-target="#billing" type="button"
                                    role="tab" aria-controls="billing"
                                    aria-selected="false">Billing History</button>
                        </li>
                        <li class="nav-item" role="presentation" v-if="props.info.invoice">
                            <button class="nav-link"
                                    id="invoice-tab" data-bs-toggle="tab"
                                    data-bs-target="#invoice" type="button"
                                    role="tab" aria-controls="invoice"
                                    aria-selected="false">Invoice</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="mambers-tab" data-bs-toggle="tab"
                                    data-bs-target="#mambers" type="button"
                                    role="tab" aria-controls="mambers"
                                    aria-selected="false">Team</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="progress-tab" data-bs-toggle="tab"
                                    data-bs-target="#progress" type="button"
                                    role="tab" aria-controls="progress"
                                    aria-selected="false">Progressment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="operation-tab" data-bs-toggle="tab"
                                    data-bs-target="#operation" type="button"
                                    role="tab" aria-controls="operation"
                                    aria-selected="false">Operation</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                            <Overview :info="props.info"/>
                        </div>
                        <div class="tab-pane fade" id="client" role="tabpanel" aria-labelledby="client-tab">
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <div class="card mb-4">
                                        <!-- Card body -->
                                        <div class="card-body">
                                            <div class="text-center">
                                                <img :src="props.info.client?.photo ?? '/images/avatar.png'" height="150" class="rounded-circle avatar-xl mb-3" alt="">
                                                <h4 class="mb-0">{{ props.info.client?.name ?? '---'}}</h4>
                                                <h5 class="mb-0">{{ props.info.client?.company }}</h5>
                                                <p class="mb-0">{{ props.info.client?.email ?? '' }}</p>
                                                <p class="mb-0">{{ props.info.client?.secondary_email ?? '' }}</p>
                                                <p class="mb-0">{{ props.info.client?.phone ?? '' }}</p>
                                                <p class="mb-0">{{ props.info.client?.secondary_phone ?? '' }}</p>
                                                <p class="mb-0">{{ props.info.client?.address }}</p>
                                                <a :href="`/admin/clients/${ props.info.client?.id}`" class="btn btn-primary mt-3">Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                            <Billing :info="props.info"/>
                        </div>
                        <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="invoice-tab" v-if="props.info.invoice">
                            <section class="invoice-edit-wrapper">
                                <div class="row invoice-edit">
                                    <InvoiceContent :pref="props.pref" :invoice="props.info.invoice" :isShowSidebar="false"/>
                                </div>
                            </section>
                        </div>
                        <div class="tab-pane fade" id="mambers" role="tabpanel" aria-labelledby="mambers-tab">
                            <Mambers :info="props.info" :urls="props.urls"/>
                        </div>
                        <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                            <Progressment/>
                        </div>
                        <div class="tab-pane fade" id="operation" role="tabpanel" aria-labelledby="operation-tab">
                            <Action :info="props.info" :updateUrl="props.urls.update_status"/>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>



    <div class="modal modal-slide-in fade" id="assignedDeveloper" aria-hidden="true">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Assigned Developer</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form @submit.prevent="assignDevelopers">
                        <div class="mt-2">
                            <label>Select Developers</label>
                            <v-select :options="props.users"
                                      v-model="formData.users"
                                      label="name"
                                      :reduce="user => user.id"
                                      class="form-control select-padding"
                                      multiple
                                      placeholder="e.g Select User">
                                <template v-slot:option="option">
                                    <li class="d-flex align-items-start py-1">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="me-1 d-flex flex-column" >
                                                <h6 class="mb-25">{{ option.name }}</h6>
                                                <span>{{ option.email }}</span>
                                                <span>{{ option.phone }}</span>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </v-select>
                        </div>

                        <div class="d-flex flex-wrap mb-0 mt-5">
                            <button type="submit" class="btn btn-primary me-1" data-bs-dismiss="modal">Send</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import moment from "moment";
import {computed, ref, onMounted} from "vue"
import ProgressChart from "../../components/ProgressChart.vue";
import Show from "../Invoice/Show.vue";
import {useAction} from "../../composables/useAction";
import InvoiceContent from "../../components/modules/InvoiceContent.vue";
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'

import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import Overview from "./Partials/Overview.vue";
import Billing from "./Partials/Billing.vue";
import Mambers from "./Partials/Mambers.vue";
import Progressment from "./Partials/Progressment.vue";
import Action from "./Partials/Action.vue";

let props = defineProps({
    info: Object,
    dates:null,
    users:Array|[]|null,
    pref:Array|[]|Object|null,
    urls:Array|[]|Object|null,
});


const formData = useForm({
    projectId:props.info.id,
    users:props.info.users,
});

const assignDevelopers = ()=> {
    formData.post(props.urls.assign_url ,{
        preserveState: true,
        onSuccess: ()=> { $toast.success('User Assigned Successfully Done...') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}


const removeUser = (url)=> {
    Inertia.get(url ,{
        preserveState: true,
        onSuccess: ()=> { $toast.success('User Removed Successfully Done...') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}





</script>

<style lang="sass">
@import "../../../sass/base/pages/app-invoice.scss"
</style>

<style lang="css">

.newlineStringStyle {
    white-space: pre-wrap;
    font-size: 11px;
}
.vs__dropdown-toggle{
    border: 1px solid;
}
.add-new-user{
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px 11px;
    border: 2px dashed gray;
    border-radius: 50%;
    background: none;
    transition: 0.3s all ease;
    cursor:pointer;
}
.add-new-user:hover{
    border-color: var(--bs-primary);
}

.newlineStringStyle {
    white-space: pre-wrap;
}
.add-client-note{
    text-align: justify;
    background: #f2f2ff;
    padding: 12px;
    border-radius: 10px;
    color: black;
}
</style>

