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
                                    <h4 class="card-title">Quotations Information's </h4>
                                    <Link href="/admin/quotations" class="dt-button add-new btn btn-primary">Manage
                                        Quotations
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-11 mx-auto">
                            <form @submit.prevent="createQutation">
                                <!--works sections-->
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h4 class="card-title">{{ worksTitle }}</h4>
                                        <button @click="collupsWork" class="btn-icon rounded-circle btn bg-light-primary" type="button">
                                            <vue-feather type="arrow-down"></vue-feather>
                                        </button>
                                    </div>
                                    <div class="card-body collapse" id="collapseExample1">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in works"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="formData.works[index].p"
                                                                       type="checkbox">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" v-model="formData.works[index].price">
                                                        <input type="hidden" v-model="formData.works[index].id">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="formData.works[index].quantity" placeholder="quantity">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="formData.works[index].discount" placeholder="Discount">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-item-center justify-content-between">
                                                        <div class="d-flex flex-column">
                                                            <label class="form-check-label mb-50"
                                                                   for="customSwitch10">Primary</label>
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="form-check-input"
                                                                       v-model="formData.status" id="customSwitch10" checked/>
                                                                <label class="form-check-label" for="customSwitch10">
                                                        <span class="switch-icon-left"><i
                                                            data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <button type="submit"
                                                                    class="btn btn-primary waves-effect waves-float waves-light me-2">
                                                                Submit
                                                            </button>
                                                            <button type="reset" class="btn btn-outline-secondary"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close">Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
import Pagination from "@/components/Pagination.vue"
import Icon from '@/components/Icon.vue'
import Modal from '@/components/Modal.vue'
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
let props=defineProps({
    works:null,
})


let formData = useForm({
    works:[],

    quatations: [
        {
            itemname: '',
            price: '',
            discount: '',
            quantity: ''
        }
    ],
});


props.works.forEach(function(item, index){
    formData.works[index] = item;
});


let worksTitle   = "Select work services"


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
            router.delete(adminPath.value + '/users/' + id, {
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


let createQutation = () => {
    console.log(formData)
    router.post('/admin/quotations', formData, {
        preserveState: true,
        // onStart: () =>{ data.processing = true},
        // onFinish: () => { data.processing = false},
        onSuccess: () => {
            this.data.quatations.reset()
            Swal.fire(
                'Saved!',
                'Your file has been Saved.',
                'success'
            )
        },
    })
}


let addRow = () => {
    formData.quatations.push({
        itemname: '',
        price: '',
        discount: '',
        quantity: ''
    })
}

let deleteRow = (index) => {
    formData.quatations.splice(index, 1)
}


let collupsWork = () =>{
    var element = document.getElementById("collapseExample1");
    element.classList.toggle("show");
}

let collupsService = () =>{
    var element = document.getElementById("collapseExample2");
    element.classList.toggle("show");
}
let collupsHosting = () =>{
    var element = document.getElementById("collapseExample3");
    element.classList.toggle("show");
}
let collupsPackage = () =>{
    var element = document.getElementById("collapseExample4");
    element.classList.toggle("show");
}
</script>

<style scopd>

.webkitTransaction{
    -webkit-transition: all 5s ease-in-out;
    -moz-transition: all 5s ease-in-out;
    -ms-transition: all 5s ease-in-out;
    -o-transition: all 5s ease-in-out;
    transition: all 5s ease-in-out;
}

/*@import "../../../../sass/base/plugins/tables/datatables";*/
</style>
