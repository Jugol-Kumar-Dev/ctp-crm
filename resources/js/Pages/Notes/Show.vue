<template>
    <Head title="Show Notes"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="row">
                    <div class="col-m-8 mx-auto">
                        <div class="card">
                             <div class="card-body">
                                 <h2 class="card-title fw-bold">{{ note.title }}</h2>
                                 <h4>Note Category: {{ note.note_category?.title }}</h4>
                                 <div class="d-flex align-items-end">
                                     <h4 class="me-2">Assigned Users: </h4>
                                     <div class="avatar-group mt-50">
                                         <div class="avatar pull-up "
                                              v-for="(user, index) in note.users"
                                              :key="user.id" v-c-tooltip="user.name">
                                             <img :src="user.photo" alt="Avatar" height="30" width="30">
                                         </div>
                                     </div>
                                 </div>
                                 <hr>
                                 <div>
                                     <h2>Notes</h2>
                                     <p class="newlineStringStyle" v-html="note.notes"></p>
                                 </div>
                             </div>

                        </div>
                    </div>
                </div>
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

let props = defineProps({
    note: Object,
});




</script>

<style>
.dp__input_wrap svg{
    margin-left: 11px !important;
}
.dp__input_icon_pad {
    padding: 8px 35px !important;
    border-radius: 5px !important;
}
.newlineStringStyle {
    white-space: pre-wrap;
}
</style>

