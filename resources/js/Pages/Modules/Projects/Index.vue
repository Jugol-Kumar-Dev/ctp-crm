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
                                    <h4 class="card-title">Project Information's </h4>
<!--                                    <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Client</button>-->
                                    <button
                                        class="dt-button add-new btn btn-primary"
                                        @click="addDataModal"
                                    >
                                        Add Project
                                    </button>
                                </div>
                                <div class="card-datatable table-responsive pt-0">
                                    <div
                                        class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                                        <div
                                            class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
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
                                                    <label>Search:<input v-model="search" type="search"
                                                                         class="form-control" placeholder=""
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
                                            <th class="sorting">Delivery Date</th>
                                            <th class="sorting">Developers</th>
<!--                                            <th class="sorting">Project Status</th>-->
                                            <th class="sorting">Project Progress</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="projects in projects.data" :key="projects.id">
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="d-flex flex-column">
                                                        <div class="user_name text-truncate text-body">
                                                            <span class="fw-bolder" v-if="projects.project.client">{{ projects.project.client.name }}</span>
                                                        </div>
                                                        <small class="emp_post text-muted" v-if="projects.project.client">{{ projects.project.client.phone }}</small>
                                                        <small class="emp_post text-muted" v-if="projects.project.client">{{ projects.project.client.email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="https://jugolkumar.com">{{ projects.project.name }}</a>
                                            </td>

                                            <td>{{ projects.start_date + " - " + projects.end_date }} </td>
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
                                                         v-c-tooltip="`Project Complate ${projects.project.progress} %`"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100"
                                                         aria-valuenow="50"
                                                         class="progress-bar progress-bar-striped cursor-pointer"
                                                         :class="{
                                                                'bg-primary'   : projects.project.status === 'New Project',
                                                                'bg-warning'   : projects.project.status === 'Testing',
                                                                'bg-success'   : projects.project.status === 'Complete',
                                                                'bg-secandery' : projects.project.status === 'Revision',
                                                                'bg-danger'    : projects.project.status === 'Canceled',
                                                            }"
                                                         :style="{ width: `${projects.project.progress}%`}">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ projects.create_at }}</td>

                                            <td>
                                                <CDropdown>
                                                    <CDropdownToggle>
                                                        <vue-feather type="more-vertical" />
                                                    </CDropdownToggle>
                                                    <CDropdownMenu>
                                                        <CDropdownItem @click="editProject(projects.edit_url)">
                                                            <Icon title="pencil" />
                                                            <span class="ms-1">Edit</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem  :href="projects.show_url" >
                                                            <Icon title="eye" />
                                                            <span class="ms-1">Show</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem @click="deleteItemModal(projects.id)">
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


    <Modal id="addItemModal" title="Add New Project" v-vb-is:modal size="lg">
        <form @submit.prevent="createProject">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Project Name:
                            <Required/>
                        </label>
                        <div class="">
                            <input v-model="createForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Client: <span class="text-danger">*</span></label>
                        <div class="">
                            <v-select v-model="createForm.client_id" :options="clients"
                                      :reduce="client => client.id" label="name"
                                      placeholder="Select Client"></v-select>

                            <InputFieldError :errors="errors.client_id"/>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="createForm.date" :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
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
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.valid_until"/>
                        </div>
                    </div>


                    <div class="col-md">
                        <label>End date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="createForm.end_date"
                                        :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.valid_until"/>
                        </div>
                    </div>
                </div>



                <div class="row mb-1">
                    <div class="col-md-12">
                        <label>Project Description  :
                            <Required/>
                        </label>
                        <div class="">
                            <TextEditor v-model="createForm.credintials"></TextEditor>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label>Project Credential's : </label>
                        <div class="">
                            <TextEditor v-model="createForm.project_details"></TextEditor>
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
                                  @update:modelValue="subCategorySelected"
                                  label="name"
                                  :options="status"
                                  placeholder="~~Select Sub Category~~"
                                  :reduce="optoin"></v-select>

                    </div>

                    <div class="col-md">
                        <label>Assign Developers: </label>
                        <v-select
                            multiple
                            v-model="createForm.agents"
                            :options="users"
                            placeholder="Search Country Name"
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
                        <label>Nots : </label>
                        <div class="">
                            <TextEditor v-model="createForm.note"></TextEditor>
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


    <Modal id="editItemModal" title="Update Project" v-vb-is:modal size="lg">
        <form @submit.prevent="updateProject(editData.id)">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Project Name:
                            <Required/>
                        </label>
                        <div class="">
                            <input v-model="updateForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Client: <span class="text-danger">*</span></label>
                        <div class="">
                            <v-select v-model="updateForm.client_id" :options="clients"
                                      :reduce="client => client.id" label="name"
                                      placeholder="Select Client">
                                    <template v-slot:option="option">
                                        <li class="d-flex align-items-start py-1">
                                            <div class="avatar me-75">
                                                <img :src="`${option.photo}`" alt="" width="38" height="38">
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between w-100">
                                                <div class="me-1 d-flex flex-column">
                                                    <strong class="mb-25">{{ option.name }}</strong>
                                                    <span >{{ option.email }}</span>
                                                    <span >{{ option.phone }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                            </v-select>

                            <InputFieldError :errors="errors.client_id"/>
                        </div>
                    </div>
                </div>

                <div class="row mb-1">
                    <div class="col-md">
                        <label>Date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="updateForm.date" :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.date"/>
                        </div>
                    </div>

                    <div class="col-md">
                        <label>Start date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="updateForm.start_date"
                                        :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.valid_until"/>
                        </div>
                    </div>


                    <div class="col-md">
                        <label>End date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="updateForm.end_date"
                                        :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <InputFieldError :errors="errors.valid_until"/>
                        </div>
                    </div>
                </div>



                <div class="row mb-1">
                    <div class="col-md-12">
                        <label>Project Description  :
                            <Required/>
                        </label>
                        <div class="">
                            <TextEditor v-model="updateForm.credintials"></TextEditor>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label>Project Credential's : </label>
                        <div class="">
                            <TextEditor v-model="updateForm.project_details"></TextEditor>
                        </div>
                    </div>
                </div>


                <div class="row mb-1">
                    <div class="col-md">
                        <label>Upload Files</label>
                        <ImageUploader v-model="updateForm.files" label="Project Files" />
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Project Status: </label>

                        <v-select v-model="updateForm.status"
                                  label="name"
                                  :options="status"
                                  placeholder="~~Select Sub Category~~"
                                  :reduce="optoin"></v-select>

                    </div>

                    <div class="col-md">
                        <label>Assign Developers: </label>
                        <v-select
                            multiple
                            v-model="updateForm.agents"
                            :options="users"
                            placeholder="Search Country Name"
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
                        <label>Nots : </label>
                        <div class="">
                            <TextEditor v-model="updateForm.note"></TextEditor>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button :disabled="updateForm.processing" type="submit"
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
    import Pagination from "../../../components/Pagination"
    import Icon from '../../../components/Icon'
    import Modal from '../../../components/Modal'
    import InputFieldError from "../../../components/InputFieldError";
    import TextEditor from "../../../components/TextEditor";
    import ImageUploader from "../../../components/ImageUploader"
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {router} from "@inertiajs/vue3";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/vue3";
    import axios from 'axios';
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'


    const props = defineProps({
        projects: Object,
        filters: Object,
        errors: Object,
        clients:Object,
        main_url:"",

        users:Object,
    });




    const createForm = useForm({
        name: null,
        note: null,
        status: null,
        date:null,
        start_date:null,
        end_date:null,
        agents:null,
        client_id:null,
        descriptions:null,
        credintials:null,
        project_details:null,

        files:null,

        processing: Boolean,
    })

    const updateForm = useForm({
        name: null,
        note: null,
        status: null,
        date:null,
        start_date:null,
        end_date:null,
        agents:[],
        client_id:null,
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

    const deleteItemModal = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6418b1',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete('clients/' + id, {
                    preserveState: true, replace: true, onSuccess: page => {
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
                    }
                })
            }
        })
    };

    const addDataModal = () => {
        document.getElementById('addItemModal').$vb.modal.show()
    }
    const createProject = () => {
        router.post('projects', createForm, {
            preserveState: true,
            onStart: () => {
                createForm.processing = true
            },
            onFinish: () => {
                createForm.processing = false
            },
            onSuccess: () => {
                document.getElementById('addItemModal').$vb.modal.hide()
                createForm.reset()
                Swal.fire(
                    'Saved!',
                    'Your file has been Saved.',
                    'success'
                )
            },
        })
    }

    const editData = ref(null);
    const editProject = (url) => {
        axios.get(url).then(res => {
            editData.value = res.data;

            updateForm.name = res.data.name;
            updateForm.note = res.data.note;
            updateForm.status = res.data.status;
            updateForm.date = res.data.date;
            updateForm.start_date = res.data.start_date;
            updateForm.end_date = res.data.end_date;
            res.data.users.map(item => updateForm.agents.push(item));
            updateForm.client_id = res.data.client_id ?? null;
            updateForm.descriptions = res.data.descriptions;
            updateForm.credintials = res.data.credintials;
            updateForm.project_details =res.data.project_details;

            console.log(res);


            document.getElementById('editItemModal').$vb.modal.show();
        }).catch(err => {
            console.log(err);
        });
    }
    const updateProject = (id) => {
        router.post(props.main_url+"/"+ id, updateForm, {
            preserveState: true,
            onStart: () => {
                createForm.processing = true
            },
            onFinish: () => {
                createForm.processing = false
            },
            onSuccess: () => {
                document.getElementById('editItemModal').$vb.modal.hide()
                updateForm.reset()
                Swal.fire(
                    'Saved!',
                    'Your file has been Updated.',
                    'success'
                )
            },
        })
    }


    // const showProject = (url) =>{
    //     router.get(url, {
    //
    //     })
    // }

    const search = ref(props.filters.search);
    const perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.main_url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>

<style scoped>
    .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners{
        min-height: 15rem !important;
    }
</style>
