<template>
    <div class="card h-100 mb-0 shadow-none rounded-0">
        <div class="card-header border-bottom border-end rounded-0">
            <div class="chat-fixed-search">
                <div class="d-flex align-items-center w-100">
                    <div class="input-group input-group-merge ms-1 w-100">
                        <button class="btn btn-primary" @click="addTodoModal">Add Todo</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body chat-height p-0 border-end">
            <h5 class="text-primary m-2">Actions</h5>
            <div class="sidebar-menu-list ps ">
                <div class="list-group list-group-filters">
                    <a href="/admin/todos" class="d-flex align-items-center list-group-item list-group-item-action border-0" @click="activeClass('todos')" :class="activeClassRef === 'todos' ? 'active-list' : ''">
                        <vue-feather type="bar-chart-2" size="12"/>
                        <span class="align-middle ms-1" >All Task</span>
                    </a>

                    <a href="/admin/my-todos" class="d-flex align-items-center list-group-item list-group-item-action border-0" @click="activeClass('my-todos')" :class="activeClassRef ===  'my-todos' ? 'active-list' : ''">
                        <vue-feather type="mail" size="12"/>
                        <span class="align-middle ms-1"> My Task</span>
                    </a>

                    <a href="/admin/complete-todos" class="d-flex align-items-center list-group-item list-group-item-action border-0" @click="activeClass('complete-todos')" :class="activeClassRef === 'complete-todos' ? 'active-list' : ''">
                        <vue-feather type="check-circle" size="12"/>
                        <span class="align-middle ms-1">Completed</span>
                    </a>
                    <!--                                        <a href="#" class="d-flex align-items-center list-group-item list-group-item-action border-0" @click="activeClass('deleted')" :class="activeClassRef === 'deleted' ? 'active-list' : ''">-->
                    <!--                                            <vue-feather type="trash" size="12"/>-->
                    <!--                                            <span class="align-middle ms-1">Deleted</span>-->
                    <!--                                        </a>-->
                </div>

                <!--                                    <div class="mt-3 px-2 d-flex justify-content-between">
                                                        <h6 class="section-label mb-1">Tags</h6>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus cursor-pointer"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    </div>
                                                    <div class="list-group list-group-labels">
                                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0">
                                                            <span class="bullet bullet-sm bullet-primary me-1"></span>Team
                                                        </a>
                                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0">
                                                            <span class="bullet bullet-sm bullet-success me-1"></span>Low
                                                        </a>
                                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0">
                                                            <span class="bullet bullet-sm bullet-warning me-1"></span>Medium
                                                        </a>
                                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0">
                                                            <span class="bullet bullet-sm bullet-danger me-1"></span>High
                                                        </a>
                                                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center border-0">
                                                            <span class="bullet bullet-sm bullet-info me-1"></span>Update
                                                        </a>
                                                    </div>-->
            </div>
        </div>
    </div>



    <div class="modal modal-slide-in fade" id="addTodo" aria-hidden="true" v-vb-is:modal>
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Add New Todo</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form @submit.prevent="saveTodo">
                        <div class="mb-1">
                            <label>Title</label>
                            <input  v-model="formData.title" class="form-control" type="text" placeholder="Todo Title"/>
                            <span v-if="props.errors?.title" v-text="props.errors?.title" class="text-danger"></span>
                        </div>

<!--                        <div class="mb-1">
                            <label class="form-label">Date</label>
                            <Datepicker :monthChangeOnScroll="false"
                                        v-model="formData.date"
                                        placeholder="Select Payment Date"
                                        autoApply></Datepicker>
                            <span v-if="props.errors?.date" v-text="props.errors?.date" class="text-danger"></span>
                        </div>-->
                        <div class="mb-1">
                            <label>Todo For</label>
                            <Switch class="d-flex"
                                    v-if="!formData.users.length"
                                    v-model="formData.allUsers" label="Check For All Users"/>
                            <v-select
                                v-if="!formData.allUsers"
                                class="form-control select-padding"
                                multiple
                                v-model="formData.users"
                                :options="props.users"
                                placeholder="Who Can See This Todo"
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
                            <span v-if="props.errors?.users" v-text="props.errors?.users" class="text-danger"></span>
                        </div>


                        <div class="mb-1">
                            <label>Priority</label>
                            <v-select
                                class="form-control select-padding"
                                v-model="formData.priority"
                                :options="priority"
                                :reduce="priority => priority.name"
                                placeholder="Select Todo Priority"
                                label="name">
                            </v-select>
                        </div>

                        <div class="mb-1">
                            <label for="customFile" class="form-label">Attachment</label>
                            <input class="form-control" type="file" @input="uploadAttachment"
                                   id="customFile" name="customFile" aria-invalid="false">
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="payment-note">About Todo</label>
                            <textarea class="form-control" v-model="formData.aboutTodo"
                                      id="payment-note" rows="5" placeholder="Write about this note..."></textarea>
                        </div>
                        <div class="d-flex flex-wrap mb-0">
                            <button type="submit" class="btn btn-primary me-1">Save Todo</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>



import {ref,onMounted} from "vue";
import {useForm} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import Switch from "@/components/Switch.vue";
import {useQuotationStore} from "../../Store/useQuotationStore";
const usersData = useQuotationStore();
import axios from "axios";
import {useJson} from "../../composables/useJson";
const {priority} = useJson()
const props = defineProps({
    errors:Object,
    users:[]
})

const checkUrl = window.location.pathname.split('/').pop();

const main_url = ref('/admin/todos')
const activeClassRef=ref(checkUrl)
const activeClass = (task) => activeClassRef.value = task;

const itemText = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur deleniti dolore ratione reprehenderit sint. Beatae debitis deleniti dolore doloribus exercitationem facere illum nobis perspiciatis reiciendis similique tempore, temporibus totam voluptatibus!"
const addTodoModal = () => document.getElementById('addTodo').$vb.modal.show()



const formData = useForm({
    title:null,
    users:[],
    allUsers:null,
    date:null,
    priority:null,
    aboutTodo:null,
    attachment:Object,
})

const uploadAttachment = (event) => formData.attachment = event.target.files[0];

const saveTodo = () => {
    formData.post('/admin/todos', {
        preserveState: true,
        // onStart: () =>{ data.processing = true},
        // onFinish: () => { data.processing = false},
        onSuccess: () => {
            formData.reset();
            Swal.fire(
                'Saved!',
                'Your file has been Saved.',
                'success'
            )

            document.getElementById('addTodo').$vb.modal.hide()
        },
        onError: () =>{
            Swal.fire(
                'Error!',
                'Have an error, Try again.',
                'error'
            )
        }
    })
}


</script>

<style scoped>

.active-list{
    border: 0;
    border-left: 3px solid #7367f0 !important;
    border-radius: 0 !important;
    color: #7367f0;
}

.min-chat-height{
    max-height: calc(100vh - 14.5rem);
    overflow-y: scroll;
}

.todo-item-style{
    border: 1px solid #f5f5f5;
    border-top: none;
    color: #212020;
    transition: 0.3s all ease;
}
.todo-item-style:hover{
    box-shadow:0 5px 15px -8px #c3bfbf;
}
.todo-item-style:first-child{
    border-top: 1px solid #f5f5f5;
}
.form-control:focus:valid, .form-control:focus.is-valid{
    box-shadow: none;
}
.select-padding{
    padding:4px 0 0 5px !important;
}
</style>
