<template>

    <Head title="CRM Dashboard"/>
    <!-- Dashboard Ecommerce Starts -->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <section id="dashboard-ecommerce">
            <div class="row match-height">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between">
                            <h1 class="text-capitalize">Working Summery</h1>
                            <button class="btn btn-primary" @click="addWorkSummery">Add Today Summery</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row match-height">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2>Users</h2>
                            <ul>
                                <li>
                                    <a :href="`/admin/admin-show-summery`">All Summery</a>
                                </li>
                                <li v-for="user in props.users">
                                    <a :href="`/admin/admin-show-summery?user=${user?.id}`">{{ user?.name }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-12 col-md-12" v-for="item in props.summery.data">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="fw-bold text-capitalize">{{ item?.user?.name }}</h2>
                                    <strong>Date: {{ moment(item?.created_at)?.format('lll') }}</strong>
                                    <p>{{ item?.message }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <Pagination :links=" props.summery.links" :from=" props.summery.from" :to=" props.summery.to" :total=" props.summery.total"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <Modal id="addWorkSummery" title="My Today Work Summery" v-vb-is:modal size="md">
        <form @submit.prevent="saveTodaySummery">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <textarea class="form-control" v-model="fromData.summery" rows="10"
                                  placeholder="Follow up message..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button :disabled="fromData.processing" type="submit"
                        rows="10"
                        class="btn btn-primary waves-effect waves-float waves-light">Save Work Summery
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>
</template>


<script setup>
import Pagination from "@/components/Pagination.vue";
import Modal from "@/components/Modal.vue";
import {useForm} from "@inertiajs/vue3";
import {router} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import moment from "moment";
let props = defineProps({
    summery:[]|null,
    users: [] |null,
    main_url:String,
})


const fromData = useForm({
    summery:null,
    processing:false
})

const addWorkSummery = () => document.getElementById('addWorkSummery').$vb.modal.show()

let saveTodaySummery = () => {
    router.post('/admin/work-summery/save', fromData, {
        preserveState: true,
        onStart: () => {
            fromData.processing = true
        },
        onFinish: () => {
            fromData.processing = false
        },
        onSuccess: () => {
            document.getElementById('addWorkSummery').$vb.modal.hide()
            fromData.reset()
            Swal.fire(
                'Saved!',
                'Your file has been Saved.',
                'success'
            )
        },onError: (err) =>{
            console.log(err);
            Swal.fire(
                'Error!',
                err.message,
                'error'
            )
        }
    })
}

</script>

<style scoped>
.newlineStringStyle {
    white-space: pre-wrap;
}
</style>
