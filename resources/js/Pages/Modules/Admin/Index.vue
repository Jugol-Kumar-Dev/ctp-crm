<template>
    <Head title="User Management"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
<!--            <div class="content-header row">-->
<!--                <div class="content-header-left col-md-9 col-12 mb-2">-->
<!--                    <div class="row breadcrumbs-top">-->
<!--                        <div class="col-12">-->
<!--                            <h2 class="content-header-title float-start mb-0">Administration</h2>-->
<!--                            <div class="breadcrumb-wrapper">-->
<!--                                <ol class="breadcrumb">-->
<!--                                    <li class="breadcrumb-item"><a href="/">Home</a></li>-->
<!--                                    <li class="breadcrumb-item active">Admin</li>-->
<!--                                </ol>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="content-body">

                <!-- Advanced Search -->
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Administration</h4>
                                    <button
                                        v-if="this.$page.props.auth.user.can.includes('user.create') || this.$page.props.auth.user.role == 'Administrator' "
                                        class="dt-button add-new btn btn-primary"
                                        @click="addDataModal"
                                    >
                                        Add User
                                    </button>                                </div>
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
                                    <table class="user-list-table table">
                                        <thead class="table-light">
                                        <tr class="">
                                            <th class="sorting">Name</th>
                                            <th class="sorting">Role</th>
                                            <th class="sorting">Active on</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="user in users.data" :key="user.id">
                                            <td>
                                                <div class="d-flex justify-content-left align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar  me-1">
                                                            <img :src="user.photo"
                                                                 alt="{{ user.username }}" height="32" width="32">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div class="user_name text-truncate text-body">
                                                            <span class="fw-bolder">{{ user.name }}</span>
                                                        </div>
                                                        <small class="emp_post text-muted">{{ user.phone ?? '' }}</small>
                                                        <small class="emp_post text-muted">{{ user.email }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span v-for="role in user.roles" class="badge bg-primary" style="margin-right: 3px">{{ role }} </span>
                                            </td>
                                            <td>{{ user.active_on }}</td>

                                            <td>
                                                <CDropdown
                                                    v-if="
                                                            this.$page.props.auth.user.can.includes('user.show') ||
                                                            this.$page.props.auth.user.can.includes('user.edit') ||
                                                            this.$page.props.auth.user.can.includes('user.delete') ||
                                                            this.$page.props.auth.user.can.includes('user.loginas') ||
                                                            this.$page.props.auth.user.role.includes('Administrator')
                                                         ">

                                                    <CDropdownToggle class="p-0">
                                                        <vue-feather type="more-vertical" />
                                                    </CDropdownToggle>
                                                    <CDropdownMenu >
                                                        <CDropdownItem :href="user.show_url"
                                                                       v-if="this.$page.props.auth.user.can.includes('user.show') || this.$page.props.auth.user.role.includes('Administrator')">
                                                            <Icon title="eye" />
                                                            <span class="ms-1">Show</span>
                                                        </CDropdownItem>

                                                        <CDropdownItem @click="editUser(user.show_url)"
                                                                       v-if="this.$page.props.auth.user.can.includes('user.edit') ||
                                                                       this.$page.props.auth.user.role.includes('Administrator')">
                                                            <Icon title="pencil" />
                                                            <span class="ms-1">Edit</span>
                                                        </CDropdownItem>

                                                        <CDropdownItem @click="deleteItemModal(props.main_url, user.id)"
                                                                       v-if="this.$page.props.auth.user.can.includes('user.delete') ||
                                                                       this.$page.props.auth.user.role.includes('Administrator')">
                                                            <Icon title="trash" />
                                                            <span class="ms-1">Delete</span>
                                                        </CDropdownItem>



                                                        <CDropdownItem
                                                            class="d-flex align-items-center"
                                                            @click="loginAs(user)" v-if="(this.$page.props.auth.user.role.includes('Administrator') || this.$page.props.auth.user.can.includes('user.loginas')) && this.$page.props.auth.user?.id !== user?.id && !user.roles.includes('Administrator')">
                                                            <vue-feather type="log-in" size="15"/>
                                                            <span class="ms-1">Login As</span>
                                                        </CDropdownItem>

                                                    </CDropdownMenu>
                                                </CDropdown>
                                            </td>



                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="users.links" :from="users.from" :to="users.to" :total="users.total" />
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



    <Modal id="addItemModal" title="Add New User" v-vb-is:modal size="lg">
        <form @submit.prevent="createUserForm">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Name:
                            <Required/>
                        </label>
                        <div class="">
                            <input v-model="createForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Email: <span class="text-danger">*</span></label>
                        <div class="">
                            <input v-model="createForm.email" type="email" placeholder="eg.example@creativetechpark.com"
                                   class="form-control">
                            <span v-if="errors.email" class="error text-sm text-danger">{{ errors.email }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Phone: <span class="text-danger">*</span></label>
                        <input v-model="createForm.phone" type="text" placeholder="+88017********" class="form-control">
                        <span v-if="errors.phone" class="error text-sm text-danger">{{ errors.phone }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Password: </label>
                        <input v-model="createForm.password" type="text" placeholder="********" class="form-control">
                        <span v-if="errors.password" class="error text-sm text-danger">{{errors.password}}</span>
                    </div>
                    <div class="col-md">
                        <label>Conform Password: </label>
                        <input v-model="createForm.password_confirmation" type="text" placeholder="********" class="form-control">
                        <span v-if="errors.password_confirmation" class="error text-sm text-danger">{{ errors.password_confirmation }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <Image  label="Profile Picture" v-model="createForm.photo"/>
                    </div>

                    <div class="col-md">
                        <label for="">Select Roles</label>
                        <v-select
                            multiple
                            v-model="createForm.roles_name"
                            :options="roles"
                            class="form-control select-padding"
                            placeholder="Assign user roles"
                            :reduce="role => role.id"
                            label="name">
                        </v-select>

                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Address: </label>
                        <textarea v-model="createForm.address" type="text" placeholder="Enter Full Address" rows="5" class="form-control"></textarea>
                        <span v-if="errors.name" class="error text-sm text-danger">{{ errors.address }}</span>
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


    <Modal id="editItemModal" title="Edit User" v-vb-is:modal size="lg">
        <form @submit.prevent="updateUser(editData.id)">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Name:
                            <Required/>
                        </label>
                        <div class="">
                            <input v-model="updateForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Email: <span class="text-danger">*</span></label>
                        <div class="">
                            <input v-model="updateForm.email" type="email" placeholder="eg.example@creativetechpark.com"
                                   class="form-control">
                            <span v-if="errors.email" class="error text-sm text-danger">{{ errors.email }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Phone: <span class="text-danger">*</span></label>
                        <input v-model="updateForm.phone" type="text" placeholder="+88017********" class="form-control">
                        <span v-if="errors.phone" class="error text-sm text-danger">{{ errors.phone }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <Image  label="Profile Picture" v-model="updateForm.photo"/>
                    </div>
                    <div class="col-md">
                        <v-select
                            multiple
                            v-model="updateForm.roles_name"
                            :options="roles"
                            class="form-control select-padding"
                            placeholder="Search Country Name"
                            :reduce="role => role.id"
                            label="name">
                        </v-select>

                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Address: </label>
                        <textarea v-model="updateForm.address" type="text" placeholder="Enter Full Address" rows="5" class="form-control"></textarea>
                        <span v-if="errors.name" class="error text-sm text-danger">{{ errors.address }}</span>
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
    import Pagination from "../../../components/Pagination"
    import Icon from '../../../components/Icon'
    import Modal from '../../../components/Modal'
    import Image from '../../../components/ImageUploader'
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {router} from "@inertiajs/vue3";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/vue3";
    import axios from "axios";
    import {useAction} from "../../../composables/useAction";
    const {deleteItem} = useAction();
    let props = defineProps({
        users: Object,
        filters: Object,
        //   can: Object,
        notification:Object,
        errors: Object,
        roles:Object,
        main_url:String,
    });

    let createForm = useForm({
        name: "",
        email: "",
        phone: "",
        address: "",
        photo:"",
        password:"",
        password_confirmation:"",
        roles_name:[],

        processing: Boolean,
    })


    let updateForm = useForm({
        name: "",
        email: "",
        phone: "",
        address: "",
        photo:"",
        password:"",
        password_confirmation:"",
        roles_name:[],

        processing: Boolean,
    })
    let addDataModal = () => {
        document.getElementById('addItemModal').$vb.modal.show()
    }
    let createUserForm = () => {
        router.post('users', createForm, {
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
    const editUser = (url) =>{
        axios.get(url+"?api=true").then((res) =>{
            editData.value = res.data
            updateForm.name  = res.data.name;
            updateForm.email = res.data.email;
            updateForm.phone = res.data.phone;
            updateForm.address = res.data.address;
            res.data.roles.map(item => updateForm.roles_name = item.id);
            document.getElementById('editItemModal').$vb.modal.show();
        }).catch((err) => console.log(err))
    }


    const updateUser = (id) => {
        router.post(`users/${id}`, updateForm, {
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
    let deleteItemModal = (url, id) => {
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
                router.delete(`${url}/${id}`, {
                preserveState: true,
                replace: true,
                onSuccess: page => {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                },
                onError: errors => {
                    console.log(errors)
                    Swal.fire(
                        'Oops...',
                        `${errors[0]}`,
                        'error'
                    )
                }})
            }
        })
    };

    const loginAs = (user)=>{
        console.log(user)
        if(confirm("Are You Sure? You Want To Login As "+user?.name)){
            router.post('/login-as', {
                userId:user?.id
            }, {
                onError:errors => {
                    Swal.fire(
                        'Oops...',
                        `${errors[0]}`,
                        'error'
                    )
                }
            })
        }
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
