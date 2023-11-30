<template>
    <Head title="Edit Notes"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="row match-height">
                                <form class="row" @submit.prevent="createNote">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h2 class="card-title">Edit This Note's</h2>
                                                <hr>
                                                <div class="d-flex align-items-center">
                                                    <h4 class="me-2">Category: </h4> <span>{{ note?.note_category?.title }}</span>
                                                </div>

                                                <div class="d-flex align-items-end">
                                                    <h4 class="me-2">Alredy Assigned Users: </h4>
                                                    <div class="avatar-group mt-50">
                                                        <div class="avatar pull-up "
                                                             v-for="(user, index) in note.users"
                                                             :key="user.id" v-c-tooltip="user.name">
                                                            <img :src="user.photo" alt="Avatar" height="30" width="30">
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row mb-1">
                                                    <label>Title <Required/> </label>
                                                    <div class=null>
                                                        <input v-model="createForm.title" type="text" placeholder="e.g Single Note Title" class="form-control">
                                                        <span v-if="errors.title" class="error text-sm text-danger">{{ errors.title }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label>Note Category <Required/></label>
                                                    <v-select v-model="createForm.category"
                                                              label="title"
                                                              :options="categories"
                                                              :reduce="cat => cat.id"
                                                              placeholder="~~Select Sub Category~~"></v-select>
                                                    <span v-if="errors.category" class="error text-sm text-danger">{{ errors.category }}</span>
                                                </div>

                                                <div class="row mb-1">
                                                    <div class="col-md">
                                                        <label>Assign Agent: </label>
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
                                                        <span v-if="errors.agents" class="error text-sm text-danger">{{ errors.agents }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <label class="form-check-label mb-50"
                                                           for="customSwitch10">Primary</label>
                                                    <div class="form-check form-switch form-check-primary">
                                                        <input type="checkbox" class="form-check-input"
                                                               v-model="createForm.status" id="customSwitch10" checked/>
                                                        <label class="form-check-label" for="customSwitch10">
                                                            <span class="switch-icon-left"><i
                                                                data-feather="check"></i></span>
                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mb-1">
                                                    <label>Name: <Required/> </label>
                                                    <div class=null>
                                                        <TextEditor v-model="createForm.notes" rows="30"></TextEditor>
                                                        <span v-if="errors.notes" class="error text-sm text-danger">{{ errors.notes }}</span>
                                                    </div>
                                                </div>


                                                <button :disabled="createForm.processing" type="submit"
                                                        class="btn btn-primary waves-effect waves-float waves-light me-2">Submit</button>
                                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                        aria-label="Close">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>


</template>



<script setup>
import Pagination from "../../components/Pagination"
import Icon from '../../components/Icon'
import Modal from '../../components/Modal'
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {Inertia} from "@inertiajs/inertia";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
import TextEditor from "../../components/TextEditor";


let props = defineProps({
    note: Object | null,
    users:Object | null,
    categories:Object | null,
    filters: Object,
    errors: Object,
    update_url: null,
});

let createForm = useForm({
    noteId:null,
    title: null,
    notes:null,
    agents: [],
    status: props.note?.status === 1,
    category:null,

    processing: Boolean,
})

createForm.noteId = props.note?.id;
createForm.title = props.note?.title;
createForm.notes = props.note?.notes;
createForm.category = props.note?.note_category.id;
createForm.agents = props.note?.users;



let createNote = () => {
    Inertia.post(props.update_url, createForm, {
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



</script>

<style>
.ck.ck-editor__main>.ck-editor__editable:not(.ck-focused){
    min-height: 300px !important;
}
.ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable, .ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners{
    min-height: 300px !important;
}
</style>

