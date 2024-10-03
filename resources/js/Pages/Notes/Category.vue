<template>
    <Head title="Create New Notes"/>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <!-- Advanced Search -->
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Categories Information's </h4>
                                </div>
                                <div class="card-datatable table-responsive pt-0 px-2">
                                    <div class="d-flex align-items-center justify-content-between border-bottom">
                                        <div class="select-search-area d-flex align-items-center">
                                            <select class="form-select" v-model="perPage">
                                                <option :value="undefined">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                                <option value="500">500</option>
                                            </select>
                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input v-model="search"
                                                           type="search"
                                                           class="form-control"
                                                           placeholder="Search Now"
                                                           aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="category-list-table table">
                                        <thead class="table-light">
                                        <tr class=null>
                                            <th class="sorting">Client</th>
                                            <th class="sorting">Phone</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="category in categories.data" :key="category.id">
                                            <td>{{ category.id }}</td>
                                            <td>
                                                <span class="d-flex align-items-center" v-if="category.notes_count > 0">
                                                    {{ category.title }}
                                                    <span class="text-info" style="margin-left: 5px;" v-c-tooltip="`${category.notes_count} Notes Found In This Category.`">
                                                        <vue-feather type="info" size="15"/>
                                                    </span>
                                                </span>
                                                <span v-else>{{ category.title }}</span>
                                            </td>
                                            <td>{{ category.created_at }}</td>
                                            <td>
                                                <button @click="deleteItemModal(category.id)" type="button" class="btn btn-icon btn-icon waves-effect waves-float waves-light bg-light-danger">
                                                    <Icon title="trash" />
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="categories.links" :from="categories.from" :to="categories.to" :total="categories.total"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-4"
                             v-if="this.$page.props.auth.user.can.includes('note.category') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="card-title">Create Categories</h4>
                                </div>
                                <div class="card-body">
                                    <form @submit.prevent="createCategory">
                                        <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Title:
                                                        <Required/>
                                                    </label>
                                                    <div class=null>
                                                        <input v-model="createForm.title" type="text" placeholder="e.g notes category title" class="form-control">
                                                        <span v-if="errors.title" class="error text-sm text-danger">{{ errors.title }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <button :disabled="createForm.processing" type="submit"
                                                class="btn btn-primary waves-effect waves-float waves-light me-2">Submit
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Cancel
                                        </button>
                                    </form>
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

</template>



<script setup>
import Pagination from "@/components/Pagination.vue";
import Icon from "@/components/Icon.vue";
import Modal from "@/components/Modal.vue";
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";

let props = defineProps({
    categories: Object,
    filters: Object,
    errors: Object,
    main_url: null,
});



let createForm = useForm({
    title: null,
    processing: Boolean,
})



let deleteItemModal = (id) => {
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
            router.delete(props.main_url + `/${id}`, {
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

let addDataModal = () => document.getElementById('addItemModal').$vb.modal.show();

let createCategory = () => {
    router.post(props.main_url, createForm, {
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






let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);
watch([search, perPage ], debounce(function ([val, val2]) {
    router.get(props.main_url, { search: val, perPage: val2}, { preserveState: true, replace: true });
}, 300));

</script>

<style>
.dp__input_wrap svg{
    margin-left: 11px !important;
}
.dp__input_icon_pad {
    padding: 8px 35px !important;
    border-radius: 5px !important;
}
</style>

