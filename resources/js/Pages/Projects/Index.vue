<template>
    <Head title="Project Managment"/>

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
                                    <h4 class="card-title">Project Information's </h4>
<!--                                    <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Client</button>-->
                                    <button
                                        v-if="this.$page.props.auth.user.can.includes('project.create')|| this.$page.props.auth.user.role.includes('Administrator')"
                                        class="dt-button add-new btn btn-primary"
                                        @click="addDataModal"
                                    >
                                        Add Project
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
                                                    <option value="200">200</option>
                                                    <option value="500">500</option>
                                                </select> entries</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                                            <div
                                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                                <div class="select-search-area">
                                                    <label>Search:<input v-model="search" type="search"
                                                                         class="form-control"
                                                                         placeholder="Search Now"
                                                                         aria-controls="DataTables_Table_0"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="user-list-table table table-striped">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">Client</th>
                                            <th class="sorting">Project Name</th>
                                            <th class="sorting">Developers</th>
<!--                                            <th class="sorting">Project Status</th>-->
                                            <th class="sorting">Project Progress</th>
                                            <th class="sorting">Delivery Date</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="projects in projects.data" :key="projects.id">
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="d-flex flex-column">
                                                        <div class="user_name text-truncate text-body">
                                                            <span class="fw-bolder text-capitalize" v-if="projects.project.client">{{ projects.project.client.name }}</span>
                                                        </div>
                                                        <small class="emp_post text-muted" v-if="projects.project.client">{{ projects.project.client.phone }}</small>
                                                        <small class="emp_post text-muted" v-if="projects.project.client">{{ projects.project.client.email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a v-if="projects?.project?.url" :href="projects?.project?.url" target="_blank">{{ projects.project.name }}</a>
                                                <span v-else>{{ projects.project.name }}</span>
                                            </td>
                                            <th>
                                                <div class="avatar-group mt-50">
                                                    <div
                                                        v-c-tooltip="developer.name"
                                                         title=""
                                                         class="avatar pull-up"
                                                         data-bs-original-title="Elicia Rieske"
                                                         v-for="(developer, index) in projects.project.users" :key="developer.id">
                                                        <img :src="developer.photo" alt="Avatar" height="30" width="30">
                                                    </div>
                                                </div>
                                            </th>
<!--                                            <td>
                                                <span
                                                    class="badge"
                                                    :class="{
                                                    'badge-light-primary'   : projects.project.status === 'New Project',
                                                    'badge-light-warning'   : projects.project.status === 'Testing',
                                                    'badge-light-success'   : projects.project.status === 'Complete',
                                                    'badge-light-secandery' : projects.project.status === 'Revision',
                                                    'badge-light-danger'    : projects.project.status === 'Canceled',
                                                }">
                                                    {{ projects.project.status }}
                                                </span>
                                            </td>-->
                                            <td>
                                                <div class="progress" style="height: 7px;">
                                                    <div role="progressbar"
                                                         v-c-tooltip="`Project ${projects.project.status} ${projects.project.progress} %`"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100"
                                                         aria-valuenow="50"
                                                         class="progress-bar progress-bar-striped cursor-pointer"
                                                         :class="{
                                                                'bg-primary'   : projects.project.status === 'New Project',
                                                                'bg-warning'   : projects.project.status === 'Testing',
                                                                'bg-success'   : projects.project.status === 'Complete',
                                                                'bg-secondary' : projects.project.status === 'Revision',
                                                                'bg-danger'    : projects.project.status === 'Canceled',
                                                                'bg-indego'    : projects.project.status === 'Development',
                                                            }"
                                                         :style="{ width: `${projects.project.progress}%`}">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ projects.start_date + " - " + projects.end_date }} </td>
                                            <td>
                                                <CDropdown
                                                    v-if="this.$page.props.auth.user.can.includes('project.show')||
                                                    this.$page.props.auth.user.can.includes('project.edit')||
                                                    this.$page.props.auth.user.role.includes('Administrator')">
                                                    <CDropdownToggle>
                                                        <vue-feather type="more-vertical" />
                                                    </CDropdownToggle>
                                                    <CDropdownMenu>
                                                        <CDropdownItem  :href="projects.show_url"
                                                                        v-if="this.$page.props.auth.user.can.includes('project.show')||
                                                                        this.$page.props.auth.user.role.includes('Administrator')">

                                                        <Icon title="eye" />
                                                            <span class="ms-1">Show</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem @click="deleteItem(props.main_url, projects.id)"
                                                                       v-if="this.$page.props.auth.user.can.includes('project.delete')||
                                                                        this.$page.props.auth.user.role.includes('Administrator')">
                                                            <Icon title="trash" />
                                                            <span class="ms-1">Delete</span>
                                                        </CDropdownItem>
                                                    </CDropdownMenu>
                                                </CDropdown>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <Pagination :links="projects.links" :from="projects.from" :to="projects.to" :total="projects.total"/>
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

    <Modal id="addItemModal" title="Add New Project" v-vb-is:modal size="lg" v-if="this.$page.props.auth.user.can.includes('project.create') || this.$page.props.auth.user.role.includes('Administrator') ">
        <form @submit.prevent="createProject">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Project Name:
                            <Required/>
                        </label>
                        <div class="">
                            <input v-model="createForm.name" type="text" placeholder="project name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
<!--                    <div class="col-md">-->
<!--                        <label>Project Domain Name <small>(Optional)</small>:-->
<!--                        </label>-->
<!--                        <div class="">-->
<!--                            <input v-model="createForm.url" type="text" placeholder="project url" class="form-control">-->
<!--                            <InputFieldError :errors="errors.url"/>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="col-md">
                        <label>{{ isClient ? 'Invoice' : 'Client' }}: <span class="text-danger">*</span></label>
                        <a href="javascript:void(0)" @click="changeSelect">{{  isClient ? 'Use Clients' : 'Use Invoice' }}</a>
                        <div v-if="isClient">
                            <v-select v-model="createForm.invoiceId" :options="preparedInvoices"
                                      @update:modelValue="setClientId"
                                      class="form-control select-padding"
                                      :reduce="invoice => invoice.id" label="invoiceId"
                                      placeholder="Select Invoice">
                                <template v-slot:option="option">
                                    <li class="d-flex align-items-start py-1">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="me-1 d-flex flex-column">
                                                <strong class="mb-25">{{ 'Invoice Id: #_'+option.invoiceId }}</strong>
                                                <span>{{ option.clientName }}</span>
                                                <span>{{ option.clientEmail ?? ''}}</span>
                                                <span>{{ option.clientPhone ?? ''}}</span>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </v-select>
<!--                            <InputFieldError :errors="errors.invoiceId"/>-->
                        </div>
                        <div v-else class="">
                            <v-select v-model="createForm.clientId"
                                      :options="props.clients"
                                      class="form-control select-padding"
                                      :reduce="item => item.id" label="name"
                                      placeholder="Select Client">
                                <template v-slot:option="option">
                                    <li class="d-flex align-items-start py-1">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="me-1 d-flex flex-column">
                                                <span>{{ option.name }}</span>
                                                <span>{{ option.email ?? ''}}</span>
                                                <span>{{ option.phone ?? ''}}</span>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </v-select>
<!--                            <InputFieldError :errors="errors.invoiceId"/>-->
                        </div>
                        <InputFieldError :errors="errors.invoiceId || errors.clientId"/>

                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="createForm.date"
                                        :monthChangeOnScroll="false"
                                        :enable-time-picker="false"
                                        :format="'d-MM-Y'"
                                        placeholder="Select Date"
                                        autoApply></Datepicker>
                            <InputFieldError :errors="errors.date"/>
                        </div>
                    </div>

                    <div class="col-md">
                        <label>Start date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="createForm.start_date"
                                        :monthChangeOnScroll="false"
                                        :enable-time-picker="false"
                                        :format="'d-MM-Y'"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.start_date"/>
                        </div>
                    </div>


                    <div class="col-md">
                        <label>End date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="createForm.end_date"
                                        :monthChangeOnScroll="false"
                                        :enable-time-picker="false"
                                        :format="'d-MM-Y'"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.end_date"/>
                        </div>
                    </div>
                </div>



                <div class="row mb-1">
                    <div class="col-md-12">
                        <label>Project Description  :</label>
                        <div class="">
                            <textarea v-model="createForm.project_details" class="form-control" rows="6" placeholder="e.g Project description explain here help for developer..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label>Project Credential's : </label>
                        <div class="">
                            <textarea v-model="createForm.credintials" class="form-control" rows="6" placeholder="e.g Details explain about this project credentials..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Upload Files</label>
                        <ImageUploader v-model="createForm.files" label="Project Files" />
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Project Status: </label>

                        <v-select v-model="createForm.status"
                                  label="name"
                                  :options="status"
                                  class="form-control select-padding"
                                  placeholder="Project Status"
                                  :reduce="optoin"></v-select>

                    </div>

                    <div class="col-md">
                        <label>Assign Developers: </label>
                        <v-select
                            multiple
                            v-model="createForm.agents"
                            :options="users"
                            class="form-control select-padding"
                            placeholder="Assign Developers"
                            :reduce="user => user.id"
                            label="name">
                            <template v-slot:option="option">
                                <li class="d-flex align-items-start py-1">
                                    <div class="avatar me-75">
                                        <img :src="`${option.photo}`" alt="" width="38" height="38">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="me-1 d-flex flex-column">
                                            <strong class="mb-25">{{ option.name }}</strong>
                                            <span >{{ option.email }}</span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md-12 mt-2">
                        <label>Notes : </label>
                        <div class="">
                            <textarea v-model="createForm.note" class="form-control" placeholder="e.g Write an note about this project..." rows="6"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
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
    import Pagination from "../../components/Pagination"
    import Icon from '../../components/Icon'
    import Modal from '../../components/Modal'
    import InputFieldError from "../../components/InputFieldError";
    import TextEditor from "../../components/TextEditor";
    import ImageUploader from "../../components/ImageUploader"
    import {ref, watch, computed} from "vue";
    import debounce from "lodash/debounce";
    import {Inertia} from "@inertiajs/inertia";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/inertia-vue3";
    import axios from 'axios';
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
    import {useAction} from "../../composables/useAction";

    const {deleteItem} = useAction();

    const props = defineProps({
        projects: Object,
        filters: Object,
        errors: Object,
        clients:Object,
        invoice:Object|null,
        main_url:String|null,
        users:Object,
    });

    const isClient = ref(true);
    const changeSelect =()=> isClient.value = !isClient.value


    const createForm = useForm({
        name: null,
        url:null,
        note: null,
        status: null,
        date:null,
        start_date:null,
        end_date:null,
        agents:null,
        clientId:null,
        invoiceId:null,
        descriptions:null,
        credintials:null,
        project_details:null,
        files:null,
        processing: Boolean,
    })


    const status = [
        'New Project',
        'In Process',
        'Testing',
        'Revision',
        'Complete',
        'Canceled'
    ]

    const addDataModal = () => {
        document.getElementById('addItemModal').$vb.modal.show()
    }
    const createProject = () => {
        Inertia.post('projects', createForm, {
            preserveState: true,
            onStart: () => {createForm.processing = true},
            onFinish: () => {createForm.processing = false},
            onSuccess: () => {
                document.getElementById('addItemModal').$vb.modal.hide()
                createForm.reset()
                Swal.fire(
                    'Saved!',
                    'Your file has been Saved.',
                    'success'
                )
            },
            onError:()=>{

            }
        })
    }



    const preparedInvoices = computed(()=>{
        return props.invoice.map(item =>{
            return {
                id:item.id,
                invoiceId:item.invoice_id+''+item.id,
                clientId:item.client?.id,
                clientName:item.client?.name,
                clientEmail:item.client?.email ?? item.client?.secondary_email,
                clientPhone:item.client?.phone ?? item.client?.secondary_phone,
            }
        })
    })
    const setClientId =(event)=>createForm.clientId = preparedInvoices.value.filter(item => item.id === event)[0].clientId;


    const search = ref(props.filters.search);
    const perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        Inertia.get(props.main_url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>

<style scoped>
    .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners{
        min-height: 15rem !important;
    }
    .bg-indego{
        background: #02fa34;
    }
</style>
