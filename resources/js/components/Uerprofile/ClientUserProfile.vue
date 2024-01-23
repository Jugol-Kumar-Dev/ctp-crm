<template>
    <div>

        <!-- BEGIN: Content-->
        <div class="app-content content ">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper container-xxl p-0">
                <div class="content-header row">
                </div>
                <div class="content-body">

                    <div class="row match-height">
                        <div class="col-md-4">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between flex-column">
                                        <div class="d-flex justify-content-start">
                                            <span class="b-avatar badge-light-danger rounded">
                                                <img :src="props.user.photo ?? $page.props.auth.MAIN_URL+props.image" class="rounded me-2"  style="width: 140px;height: 140px;"  alt="avatar">
                                            </span>
                                            <div class="d-flex flex-column ml-1">
                                                <h4 class="mb-0 text-capitalize"> {{ props.user.name }} </h4>
                                                <p class="card-text">{{ props.user.email }}</p>
                                                <p class="badge badge-light-purple text-capitalize"> {{ props.user.status }} </p>

<!--                                                <p v-if="props.user?.follow_up" v-c-tooltip="'follow up date'">{{ moment(props.user?.follow_up)  }}</p>-->
                                                <div class="d-flex align-items-center gap-1">
                                                    <button class="btn-sm btn btn-primary w-100" v-if="
                                                this.$page.props.auth.user.role == 'Administrator' ||
                                                this.$page.props.auth.user.can.includes('client.edit')" @click="editClient" >Edit Me</button>
<!--                                                    <button v-if="props.user?.note" class="btn btn-info btn-sm" @click="showClientNote" v-c-tooltip="'click for show client note.'">-->
<!--                                                        <vue-feather type="info"/>-->
<!--                                                    </button>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                                    <div class="col-md-4 col-12">
                                                                <h2>Assigned Users
                                                                    <vue-feather type="edit" class="cursor-pointer" @click="editClient"/>
                                                                </h2>
                                                                <div class="row">

                                                                </div>
                                                            </div>-->
                        <div class="col-md-4">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <table class="mt-2 mt-xl-0 w-100">
                                        <tr>
                                            <th class="pb-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="me-75 feather feather-user">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <span class="font-weight-bold">Username</span>
                                            </th>
                                            <td class="pb-50">{{ props.user.name }} </td>
                                        </tr>
                                        <tr>
                                            <th class="pb-50 d-flex align-items-center">
                                                <vue-feather type="mail" size="14" class="me-75"/>
                                                <span class="font-weight-bold">Email</span>
                                            </th>
                                            <td class="pb-50">{{ props.user.email }} </td>
                                        </tr>
                                        <tr>
                                            <th class="pb-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="me-75 feather feather-check">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                                <span class="font-weight-bold">Status</span>
                                            </th>
                                            <td class="pb-50 text-capitalize"> {{ props.user.status }} </td>
                                        </tr>
                                        <tr>
                                            <th class="pb-50">
                                                <vue-feather type="clock" size="15" />
                                                <span class="font-weight-bold ms-1">Joining</span>
                                            </th>
                                            <td class="pb-50"> {{ formatted(props.user.created_at) }} </td>
                                        </tr>
                                        <tr>
                                            <th class="pb-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="me-75 feather feather-flag">
                                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                                </svg>
                                                <span class="font-weight-bold">Address</span>
                                            </th>
                                            <td class="pb-50"> {{ props.user.address }} </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="me-75 feather feather-phone">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                </svg>
                                                <span class="font-weight-bold">Contact</span>
                                            </th>
                                            <td> {{ props.user.phone }} </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex gap-2 flex-column">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex align-items-center">
                                                <!-- avatar group -->
                                                <div class="avatar-group flex-wrap">
                                                    <div v-c-tooltip="developer.name"
                                                         title=""
                                                         class="avatar pull-up"
                                                         data-bs-original-title="Elicia Rieske"
                                                         v-for="(developer, index) in user.users" :key="developer.id">
                                                        <img :src="developer.photo" alt="Avatar" height="30" width="30">
                                                    </div>
                                                </div>
                                                <div class="pull-up add-new-user ms-2"  v-c-tooltip="'Assign New User'" @click="assignEmployee">
                                                    +
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div v-if="props.user.follow_up ||  props.user.follow_up_message">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h3>Follow up </h3>
                                                    <vue-feather type="edit" size="20" class="cursor-pointer" @click="editFollowUp"/>
                                                </div>
                                                <p v-if="props.user.follow_up">Follow Date: {{ moment(props.user.follow_up)?.format('ll')}}</p>
                                                <small>{{ props.user.follow_up_message?.slice(0, 60) }}</small>
                                                <a v-if="props.user.follow_up_message?.length > 60" href="javascript:void(0)" >Read More...</a>
                                            </div>
                                            <button v-else class="btn bg-light-gray d-flex align-items-center gap-2" @click="editFollowUp">
                                                <vue-feather type="plus"/>
                                                <p class="m-0 p-0">Set Follow Up</p>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-12 col-lg-7 col-md-7 order-0 order-md-1">
                        <!-- User Pills -->
                        <ul class="nav nav-pills mb-2">
                            <li class="nav-item" v-for="component in components" :key="component">
                                <button class="nav-link"
                                        :class="{'active': active === component}"
                                        @click="active =`${component}`">
                                    <i data-feather="bookmark" class="font-medium-3 me-50"></i>
                                    <span class="fw-bold text-capitalize">{{ component }}</span>
                                </button>
                            </li>
                        </ul>

                        <!--/ User Pills -->
                        <Component :is="active"
                                   :transactions="user.transactions"
                                   :project="user.projects"
                                   :quotations="user.quotations"
                                   :invoices="user.invoices"
                        />

                    </div>
                </div>
            </div>
        </div>
    </div>


    <Modal id="editClient" title="Show Client" v-vb-is:modal :size="followUp ? 'sm' : 'lg'">
        <form @submit.prevent="updateClientForm(editData.id)">
            <div class="modal-body">
                <div class="row mb-1" :class="{ 'd-none' : followUp }">
                    <div class="col-md">
                        <label>Name:
                            <Required/>
                        </label>
                        <div class=null>
                            <input v-model="updateForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Email: <span class="text-danger">*</span></label>
                        <div class=null>
                            <input v-model="updateForm.email" type="email" placeholder="eg.example@creativetechpark.com"
                                   class="form-control">
                            <span v-if="errors.email" class="error text-sm text-danger">{{ errors.email }}</span>
                        </div>
                    </div>
                </div >
                <div class="row mb-1" :class="{ 'd-none' : followUp }">
                    <div class="col-md">
                        <label>Secondary Email: </label>
                        <input v-model="updateForm.secondary_email" type="email" placeholder="second.eg@ctpbd.com"
                               class="form-control">
                        <span v-if="errors.secondary_email" class="error text-sm text-danger">{{
                                errors.secondary_email
                            }}</span>
                    </div>
                    <div class="col-md">
                        <label>Phone: <span class="text-danger">*</span></label>
                        <input v-model="updateForm.phone" type="text" placeholder="+88017********" class="form-control">
                        <span v-if="errors.phone" class="error text-sm text-danger">{{ errors.phone }}</span>
                    </div>
                </div>
                <div class="row mb-1" :class="{ 'd-none' : followUp }">
                    <div class="col-md">
                        <label>Secondary Phone: </label>
                        <input v-model="updateForm.secondary_phone" type="text" placeholder="+88017********"
                               class="form-control">
                        <span v-if="errors.secondary_phone" class="error text-sm text-danger">{{
                                errors.secondary_phone
                            }}</span>
                    </div>
                    <div class="col-md">
                        <label>Company: </label>
                        <input v-model="updateForm.company" type="text" placeholder="Enter Company Name"
                               class="form-control">
                        <span v-if="errors.company" class="error text-sm text-danger">{{ errors.company }}</span>
                    </div>
                </div>
                <div class="row mb-1" :class="{ 'd-none' : followUp }">
                    <div class="col-md">
                        <label>Address: </label>
                        <textarea v-model="updateForm.address" type="text" placeholder="Enter Full Address" rows="5"
                                  class="form-control"></textarea>
                        <span v-if="errors.name" class="error text-sm text-danger">{{ errors.address }}</span>
                    </div>
                    <div class="col-md">
                        <label>Nots: </label>
                        <textarea v-model="updateForm.note" type="text" placeholder="Enter note messages" rows="5"
                                  class="form-control"></textarea>
                        <span v-if="errors.note" class="error text-sm text-danger">{{ errors.note }}</span>
                    </div>
                </div>
                <div class="row mb-1" :class="{'d-none' : !followUp}">
                    <div class="col-md">
                        <label>Follow Up Date:
                            <Required/>
                        </label>
                        <div class="single-datepiker">
                            <Datepicker v-model="updateForm.followDate" :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <span v-if="errors.followDate" class="error text-sm text-danger">{{ errors.followDate }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Client Status: </label>
                        <v-select v-model="updateForm.status"
                                  label="name"
                                  @update:modelValue="changeStatus"
                                  class="form-control select-padding"
                                  :options="status"
                                  :reduce="option => option.name"
                                  placeholder="Select Status"></v-select>
                    </div>

                    <div class="col-md" :class="{ 'd-none' : followUp }">
                        <label>Assign Agent: </label>

                        <v-select
                            multiple
                            v-model="updateForm.agents"
                            :options="props.users"
                            :reduce="user => user.id"
                            class="form-control select-padding"
                            placeholder="Select Assign Employee"
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
            </div>

            <div class="modal-footer">
                <button type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Update
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>


    <Modal id="editFollowUp" title="Set Follow Up" v-vb-is:modal size="sm">
        <form @submit.prevent="setupFollow">
            <div class="modal-body">
                <div class="col-md">
                    <label>Follow Up Date:
                        <Required/>
                    </label>
                    <div class="single-datepiker">
                        <Datepicker v-model="followUpData.followDate"
                                    :monthChangeOnScroll="false"
                                    :enable-time-picker="false"
                                    :format="'d-MM-Y'"
                                    placeholder="Select Date" autoApply></Datepicker>
                        <span v-if="errors.followDate" class="error text-sm text-danger">{{ errors.followDate }}</span>
                    </div>
                </div>
                <div class="col-md mt-2">
                    <label>Follow Up Message:</label>
                    <textarea class="form-control" v-model="followUpData.message" rows="5" placeholder="Follow up message..."></textarea>
                    <span v-if="errors.followDate" class="error text-sm text-danger">{{ errors.followDate }}</span>
                </div>
            </div>


            <div class="modal-footer">
                <button type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Set Follow
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>



    <Modal id="showUserNote" title="Show Client" v-vb-is:modal :size="followUp ? 'sm' : 'lg'">
        <p class="p-3">{{ props.user?.note ?? 'no have note in this user' }}</p>
    </Modal>


    <div class="modal modal-slide-in fade" id="assignEmployee" v-vb-is:modal>
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Assigned Developer</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form @submit.prevent="assignEmployes">
                        <div class="mt-2">
                            <label>Select Employee</label>
                            <v-select
                                multiple
                                v-model="updateForm.agents"
                                :options="props.users"
                                :reduce="user => user.id"
                                class="form-control select-padding"
                                placeholder="Select Assign Employee"
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

                        <div class="d-flex flex-wrap mb-0 mt-5">
                            <button type="submit" class="btn btn-primary me-1">Assigned</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import Account from './Account'
import Billing from './Billing'
import Quotation from './Quotation'
import Invoice from './Invoice'
import Project from './Project'
import Domain from './Domain'
import Hosting from './Hosting'
import Modal from '../Modal.vue'
import moment from "moment";

import {useDate} from '../../composables/useDate.js'
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import Swal from "sweetalert2";
import axios from "axios";
import {useForm} from "@inertiajs/inertia-vue3";
let {formatted} = useDate();
let props = defineProps({
    user:[]|Object|null,
    users:[]|Object|null,
    image:String,
    showUrl:String,
    errors:Object,
})

let status = [
    {"name":'New Lead'}, {"name":'Contacted'}, {"name":'Proposal Sent'},
    {"name":'Quote Sent'}, {"name":'Qualified'}, {"name":'Disqualified'},   {"name":'Converted to Customer'},
]

// {"name":'Follow Up'},


const followUp = ref(false);
const changeStatus = (event) =>{
    followUp.value = event === 'Follow Up';
}

let updateForm = useForm({
    name: null,
    email: null,
    secondary_email: null,
    phone: null,
    secondary_phone: null,
    company: null,
    address: null,
    note: null,
    status: null,
    agents: null,
    isClient:true,
})

let editData = ref([]);

let editClient = (url) => {
    axios.get(props.showUrl+"?edit=true").then(res => {
        editData.value = res.data;
        updateForm.name = res.data.name;
        updateForm.email = res.data.email;
        updateForm.secondary_email = res.data.secondary_email;
        updateForm.phone = res.data.phone;
        updateForm.secondary_phone = res.data.secondary_phone;
        updateForm.company = res.data.company;
        updateForm.address = res.data.address;
        updateForm.note = res.data.note;
        updateForm.status = res.data.status;
        updateForm.agents = res.data.users;

        document.getElementById('editClient').$vb.modal.show();
    }).catch(err => {
        console.log(err);
    });
}



let updateClientForm = (id) => {
    Inertia.put(props.showUrl, updateForm, {
        preserveState: true,
        onSuccess: () => {
            document.getElementById('editClient').$vb.modal.hide()
            Swal.fire(
                'Saved!',
                'Updated Successfully Done...',
                'success'
            )
        },
    })
}


const showClientNote = () =>{
    document.getElementById('showUserNote').$vb.modal.show()
}

const editFollowUp = () => document.getElementById('editFollowUp').$vb.modal.show()

const followUpData = useForm({
    clientId:props.user.id,
    followDate:props.user?.follow_up,
    message:props.user?.follow_up_message,
})
const setupFollow = () =>{
    followUpData.post('/admin/clients/set-follow-up',{
        onSuccess:()=> {
            document.getElementById('editFollowUp').$vb.modal.hide()
            $toast.success('Follow Up Set Done...')
        }
    })
}
const assignEmployee = () => {
    updateForm.agents = props.user.users;
    document.getElementById('assignEmployee').$vb.modal.show()
}

let assignEmployes = (id) => {
    Inertia.put(props.showUrl+'?type=assignEmployee', updateForm, {
        preserveState: true,
        onSuccess: () => {
            document.getElementById('assignEmployee').$vb.modal.hide()
            $toast.success('Employee Assigned Done...')
        },
    })
}
</script>

<script>
export default {
    components:{
        Billing, Quotation, Invoice, Project //Domain, Hosting
    },
    data(){
        return {
            active: 'billing',
            username:'',
            components: ['billing', 'project', 'quotation', 'invoice']
        }
    },
    methods:{
        handelevent(text){
            this.username = text;
        },
        handelMyEmit(data){
            this.username = data.target.value;
        }
    }
}
</script>

<script setup>
</script>



<style scoped>
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
</style>
