<template>
    <div class="row match-height">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <label class="mb-2">Change Project Status</label>
                    <v-select v-model="formData.status"
                              label="name"
                              class="form-control select-padding"
                              :options="status"
                              placeholder="~~Select Sub Category~~">
                    </v-select>

                    <div class="mt-1">
                        <label class="form-label">Example range</label>
                        <div>
                            <input type="range" @input="progress" class="form-range customRange" :value="reange" min="1" max="100">
                            <span>{{ reange }} %</span>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm mt-1" @click="changeStatus">Update</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class=" card-title ">Overall Progress </h4>
                    <ProgressChart :progress="props.info.progress" :height="250" :status="props.info.status"/>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Upload Backup File Url</h2>
                    <div class="d-flex align-items-center mb-1 ms-5" v-for="(variant, index) in backupData.files">
                        <input type="text"
                               class="form-control mb-1 rounded-start ms-1"
                               placeholder="e.g backup file location." v-model="backupData.files[index]" >
                        <button
                            v-if="index === backupData.files.length - 1 "
                            class="btn btn-primary btn-sm float-end mb-1 ms-1"
                            type="button"
                            @click="addRow()">
                            <vue-feather type="plus" size="20"/>
                        </button>
                        <button
                            v-else
                            class="btn btn-danger btn-sm float-end mb-1 ms-1"
                            @click="deleteRow(index)"
                            data-repeater-delete
                            type="button">
                            <vue-feather type="trash" size="20"/>
                        </button>
                    </div>
                    <div class="d-flex flex-wrap mb-0">
                        <button @click="uploadBackup" class="btn btn-primary me-1">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>


import {useForm} from "@inertiajs/vue3";
import {ref} from "vue"
import Swal from "sweetalert2";
import ProgressChart from "@/components/ProgressChart.vue";
import {useJson} from "@/composables/useJson.js";

const props = defineProps({
    info:{
        type:Object,
        required:true
    },
    updateUrl:{
        type:String,
        required:true
    },
    errors:Object,
})

const {status} = useJson()

const formData = useForm({
    projectId:props.info.id,
    status:props.info.status,
    progressData:props.info.progress,
})
const reange = ref(props.info.progress);
const progress = (event) =>{
    formData.progressData = event.target.value;
    reange.value = event.target.value;
}
const processing = ref(false)
const changeStatus = () =>{
    formData.post(props.updateUrl, {
        preserveState: true,
        onStart: () => {processing.value = true},
        onFinish: () => {processing.value = false},
        onSuccess: ()=> { $toast.success('Project Status Updated...') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}



const backupData = useForm({
    files: props?.info?.backup ? JSON.parse(props?.info?.backup ?? "") : [null]
})
const errors = ref({});
const uploadBackup = () =>{
    backupData.post('/admin/project/update-project-backup/'+props.info.id, {
        preserveState: true,
        onStart: () => {processing.value = true},
        onFinish: () => {processing.value = false},
        onSuccess: ()=> { $toast.success('Saved project backup') },
        onError: (error)=> {
            errors.value = error;
            $toast.error('Have An Error. Please Try Again.')

        },
    })
}
const addRow = () => {
    backupData.files.push(null)
}

const deleteRow = (index) => backupData.files.splice(index, 1)

</script>

<style scoped>

</style>
