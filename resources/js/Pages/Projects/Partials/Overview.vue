<template>
    <div class="row">
        <div class="col-md-12 col-xl-8 col-12">
            <div class="row">
                <div class="col-12 mb-2">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Project Summary
                                <span @click="editDetails"
                                      v-if="this.$page.props.auth.user.can.includes('project.edit')||
                                            this.$page.props.auth.user.role.includes('Administrator')"
                                      class="cursor-pointer" v-c-tooltip="'Edit Project Details and Timeline.'">
                                    <vue-feather type="edit" size="15"/>
                                </span>
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="newlineStringStyle">{{ info.description }}</p>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <vue-feather type="calendar"/>
                                            <div class="ms-2">
                                                <h5 class="mb-0 text-body">Start Date</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0 fw-semibold">{{ moment(info.start).format('D MMM  YYYY') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <vue-feather type="calendar"/>
                                            <div class="ms-2">
                                                <h5 class="mb-0 text-body">End Date</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0 fw-semibold">{{ moment(info.end).format('D MMM  YYYY') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item  px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <vue-feather type="clock"/>
                                            <div class="ms-2">
                                                <h5 class="mb-0 text-body">Estimate
                                                    Time</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0 fw-semibold">{{ estimateTimes }}Days</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 pb-0" v-if="props.info.invoice && this.$page.props.auth.user.can.includes('project.budget')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <vue-feather type="dollar-sign"/>
                                            <div class="ms-2">
                                                <h5 class="mb-0 text-body">Cost</h5>
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <p class="text-dark mb-0 fw-semibold">{{ info.invoice.grand_total }} Tk</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="
                            this.$page.props.auth.user.can.includes('project.credentials') ||
                            this.$page.props.auth.user.role.includes('Administrator') ">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Project Credintials</h4>
                            <span class="cursor-pointer" @click="viewCredintials">
                                <vue-feather :type="showCredintials ? 'eye' : 'eye-off'"/>
                            </span>
                        </div>
                        <div class="card-body" v-if="showCredintials">
                            <p class="newlineStringStyle">{{ info.credential }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-2" v-if="props.info.invoice && this.$page.props.auth.user.can.includes('project.budget') || this.$page.props.auth.user.role.includes('Administrator')">
                    <!-- card -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Budget </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- col -->
                                <div class="col-lg-4 col-md-12 col-12 bg-">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="h1 fw-bold mb-0">{{ props.info.invoice?.grand_total ?? 0}}</h2>
                                            <p class="mb-0">Total Budget</p>
                                        </div>
                                        <span style="padding: 1rem 1.1rem" class="bg-light-warning rounded-circle d-flex align-items-center justify-content-center">Tk</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-12 bg-">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="h1 fw-bold mb-0">{{ props.info.invoice?.pay ?? 0}}</h2>
                                            <p class="mb-0">Total Pay</p>
                                        </div>
                                        <span style="padding: 1rem 1.1rem" class="bg-light-success fw-bold rounded-circle d-flex align-items-center justify-content-center">Tk</span>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-12 bg-">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="h1 fw-bold mb-0">{{ props.info.invoice?.due ?? 0}}</h2>
                                            <p class="mb-0">Due Payment</p>
                                        </div>
                                        <span style="padding: 1rem 1.1rem" class="bg-light-danger fw-bold rounded-circle d-flex align-items-center justify-content-center">Tk</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- row -->
                    </div>
                </div>
                <div class="col-md-12 mb-2" v-if="props.info.invoice && this.$page.props.auth.user.can.includes('project.budget')">
                    <!-- card -->
                    <div class="card">
                        <!-- card body -->

                        <div class="card-header">
                            <h4 class="mb-0">Transaction History</h4>
                        </div>
                        <!-- table -->
                        <div class="table-responsive overflow-y-hidden">
                            <table class=" table table-striped table-borderless mb-0 text-nowrap table-hover table-centered">
                                <thead>
                                <tr>
                                    <th class="bg-white">Payment Id</th>
                                    <th class="bg-white">Taken By</th>
                                    <th class="bg-white">Transaction Date</th>
                                    <th class="bg-white">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in props.info.invoice?.transactions" :key="item.id">
                                    <td>{{ 'Tran_#'+item.transaction_id }}</td>
                                    <td>{{ item.received_by?.name }}</td>
                                    <td>{{ moment(item.payment_date).format('ll') }}</td>
                                    <td>{{ item.amount }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xl-4 col-12">
            <!-- card  -->
            <div class="card mb-4 bg-primary border-primary">
                <!-- card body  -->
                <div class="card-body">

                    <h4 class="card-title text-white">Launch Date </h4>
                    <!-- dropdown  -->
                    <div class="d-flex justify-content-between align-items-center mt-8">
                        <div>
                            <h1 class="display-4 text-white mb-1">{{ estimateTimes }} Days</h1>
                            <p class="mb-0 text-white">{{ launchDate }}</p>
                        </div>
                        <div>
                            <i class="fe fe-flag fs-1 text-light-primary"></i>
                        </div>
                    </div>

                </div>

            </div>
            <!-- card  -->
            <div class="card mb-4">
                <!-- card body  -->
                <div class="card-body">
                    <h4 class=" card-title ">Overall Progress </h4>
                    <ProgressChart :progress="props.info.progress" :status="props.info.status"/>
                </div>
            </div>


            <div class="card" v-if="
                            this.$page.props.auth.user.can.includes('project.attachment') ||
                            this.$page.props.auth.user.role.includes('Administrator') ">
                <!-- Card header -->
                <div class="card-header card-header-height d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Project Attachment
                        </h4>
                    </div>
                    <div class="cursor-pointer d-flex gap-1">
                        <a :href="`/admin/project/download-attachment?path=${props.info.files}`">
                            <vue-feather type="download"/>
                        </a>
                        <vue-feather type="edit" @click="editAttatchment"/>
                    </div>
                </div>
                <!-- Card body -->
                <div class="card-body" style="min-height:50vh;" v-if="props.info.files">
                    <iframe :src="`/storage/${props.info.files}`" @error="fileError" alt="" id="attatchmentFile"
                            class="w-100 h-100 object-fit-contain" style="min-height:50vh;"></iframe>
                </div>
            </div>
        </div>
    </div>



    <Modal id="editDetails" title="Update Details & Credentials" v-vb-is:modal size="lg">
        <form @submit.prevent="updateDetails">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="formData.date" :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>Start date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="formData.start_date"
                                        :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                        </div>
                    </div>
                    <div class="col-md">
                        <label>End date :
                            <Required/>
                        </label>
                        <div class="">
                            <Datepicker v-model="formData.end_date"
                                        :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                        </div>
                    </div>
                </div>



                <div class="row mb-1">
                    <div class="col-md-12">
                        <label>Project Description  :
                            <Required/>
                        </label>
                        <div class="">
                            <textarea v-model="formData.project_details" class="form-control" rows="6" placeholder="e.g Project description explain here help for developer..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2" v-if="this.$page.props.auth.user.can.includes('project.credentials')">
                        <label>Project Credential's : </label>
                        <div class="">
                            <textarea v-model="formData.credintials" class="form-control" rows="6" placeholder="e.g Details explain about this project credentials..."></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button :disabled="formData.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

    <Modal id="editAttachment" title="Update Attachment" v-vb-is:modal size="lg">
        <form @submit.prevent="updatAttachment">
            <div class="modal-body">
                <ImageUploader v-model="updateFile.files" label="Project Files" />
            </div>


            <div class="modal-footer">
                <button :disabled="formData.processing" type="submit"
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

import {computed, onMounted, ref} from "vue";
import moment from "moment";
import ProgressChart from "../../../components/ProgressChart.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Modal from '../../../components/Modal.vue';
import {Inertia} from "@inertiajs/inertia";
import Swal from "sweetalert2";
import ImageUploader from "../../../components/ImageUploader.vue";


const props = defineProps({
        info:{
            type:Object,
            required:true,
        }
    })

    const estimateTimes = computed(()=>{
        var a = moment(props.info.start);
        var b = moment(props.info.end);
        return b.diff(a, 'days') // 1
    })

    const launchDate  = computed(()=> moment(props.info.start).add(1,'days').format('LL , dddd'))

    const showCredintials = ref(false)
    const viewCredintials =()=> showCredintials.value = !showCredintials.value;

    const formData = useForm({
        date:props.info.date,
        start_date:props.info.start,
        end_date:props.info.end,
        project_details:props.info.description,
        credintials:props.info.credential,
    })

    const editDetails = () =>{
        document.getElementById('editDetails').$vb.modal.show()
    }

    const updateDetails = () =>{
        formData.post('/admin/project/update-details/'+props.info.id,{
            onSuccess:()=>{
                Swal.fire(
                    'Updated!',
                    'Project Details And Credentials Has been Updated.',
                    'success'
                )
                document.getElementById('editDetails').$vb.modal.hide()
            }
        })
    }


    const editAttatchment=()=> document.getElementById('editAttachment').$vb.modal.show()
    const updateFile = useForm({
        files:null,
    })
    const updatAttachment=()=>{
        updateFile.post('/admin/project/update-attachment/'+props.info.id,{
            onSuccess:()=>{
                Swal.fire(
                    'Updated!',
                    'Project Attachment Has been Updated.',
                    'success'
                )
                document.getElementById('editAttachment').$vb.modal.hide()
            }
        })
    }

    const downloadAttachment = (path) =>{
        Inertia.get('/admin/project/download-attachment/', {
            path:path
        }, {
            onSuccess:()=>{
                Swal.fire(
                    'Updated!',
                    'Project Attachment Has been Updated.',
                    'success'
                )
                document.getElementById('editAttachment').$vb.modal.hide()
            }
        })
    }

</script>

<style scoped>
.bg-primary{
    background:var(--bs-primary) !important;
}
</style>
