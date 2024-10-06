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
                            <form @submit.prevent="updateQutation">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="modal-body">
                                            <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Client :
                                                        <Required/>
                                                    </label>
                                                    <div class="">
                                                        <v-select v-model="formData.client_id" :options="clients"
                                                                  :reduce="client => client.id" label="name"
                                                                  placeholder="Select Client"></v-select>
                                                        <InputFieldError :errors="errors.client_id"/>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <label>Subject :
                                                        <Required/>
                                                    </label>
                                                    <div class="">
                                                        <input type="text" placeholder="Subjects"
                                                               v-model="formData.subject"
                                                               class="form-control">
                                                        <span class="error text-sm text-danger"></span>
                                                        <InputFieldError :errors="errors.subject"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Date :
                                                        <Required/>
                                                    </label>
                                                    <div class="">
                                                        <Datepicker v-model="formData.date" :monthChangeOnScroll="false"
                                                                    placeholder="Select Date" autoApply></Datepicker>
                                                        <InputFieldError :errors="errors.date"/>
                                                    </div>
                                                </div>

                                                <div class="col-md">
                                                    <label>Valid until :
                                                        <Required/>
                                                    </label>

                                                    <div class="">
                                                        <Datepicker v-model="formData.valid_until"
                                                                    :monthChangeOnScroll="false"
                                                                    placeholder="Select Date" autoApply></Datepicker>
                                                        <InputFieldError :errors="errors.valid_until"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


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
                                            <div class="col-md-4 mb-1" v-for="(option , index) in editable_works"
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

                                <!--hosting sections-->
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h4 class="card-title">Hosting</h4>

                                        <button @click="collupsHosting" class="btn-icon rounded-circle btn bg-light-primary" type="button">
                                            <vue-feather type="arrow-down"></vue-feather>
                                        </button>
                                    </div>
                                    <div class="card-body collapse" id="collapseExample3">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.edited_hosting"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="formData.hostings[index].p"
                                                                       type="checkbox">
                                                            </div>
                                                        </div>
                                                        <input type="hidden"  v-model="formData.hostings[index].price">
                                                        <input type="hidden"  v-model="formData.hostings[index].id">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="formData.hostings[index].quantity" placeholder="quantity">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="formData.hostings[index].discount" placeholder="Discount">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--domain sections-->
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h4 class="card-title">{{ domainTitle }}</h4>

                                        <button @click="collupsService" class="btn-icon rounded-circle btn bg-light-primary" type="button">
                                            <vue-feather type="arrow-down"></vue-feather>
                                        </button>
                                    </div>
                                    <div class="card-body collapse" id="collapseExample2">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.edited_domains"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="formData.domains[index].p" type="checkbox">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" v-model="formData.domains[index].price">
                                                        <input type="hidden" v-model="formData.domains[index].id">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="formData.domains[index].quantity" placeholder="quantity">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="formData.domains[index].discount" placeholder="Discount">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--package sections-->
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ packageTitle }}</h4>
                                        <button @click="collupsPackage" class="btn-icon rounded-circle btn bg-light-primary" type="button">
                                            <vue-feather type="arrow-down"></vue-feather>
                                        </button>
                                    </div>
                                    <div class="card-body collapse" id="collapseExample4">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.edit_packages"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="formData.packages[index].p" type="checkbox">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" v-model="formData.packages[index].price">
                                                        <input type="hidden" v-model="formData.packages[index].id">
                                                        <input type="number" class="form-control border-0" v-model="formData.packages[index].quantity" placeholder="quantity">
                                                        <input type="number" class="form-control border-0" v-model="formData.packages[index].discount" placeholder="Discount">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Note</h4>
                                        <div class="row d-flex align-items-center">
                                            <div class="col-12">
                                                <TextEditor v-model="formData.note"
                                                            placeholder="Item Details"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6" data-repeater-item
                                         v-for="(item, index) in formData.quatations">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Custom's </h4>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-12">
                                                        <div class="mb-1">
                                                            <TextEditor v-model="formData.quatations[index].item_name"
                                                                        placeholder="Item Details"/>
                                                        </div>
                                                        <div class="input-group border-0 d-flex">

                                                            <!--                                                            <QtyButton/>-->

                                                            <input type="number" class="form-control rounded-start"
                                                                   placeholder="Price" v-model="formData.quatations[index].price">

                                                            <input type="number" class="form-control"
                                                                   placeholder="Discount" v-model="formData.quatations[index].discount" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <button
                                                    v-if="index === formData.quatations.length - 1"
                                                    class="btn btn-primary btn-sm float-end mt-25"
                                                    type="button"
                                                    name="button"
                                                    @click="addRow">
                                                    <vue-feather type="plus"/>
                                                </button>
                                                <button
                                                    class="btn btn-danger btn-sm float-end mt-25 me-1"
                                                    @click="deleteRow(index)"
                                                    data-repeater-delete
                                                    type="button">
                                                    <vue-feather type="trash"/>
                                                </button>
                                            </div>
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
                                                           v-model="formData.status" id="customSwitch11" checked/>
                                                    <label class="form-check-label" for="customSwitch11">
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
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import TextEditor from "@/components/TextEditor.vue";
import InputFieldError from "@/components/InputFieldError.vue";

const props=defineProps({
    editable_works:[]|null,
    edited_hosting:[]|null,
    edited_domains:[]|null,
    edit_packages:[]|null,
    quotItems:[]|null,
    quotDetails:Object|null,

    clients:[]|null,
    errors:[]|null,
    update_url:null
})


const formData = useForm({
    client_id:null,
    subject:null,
    date:null,
    valid_until:null,
    note          : null,


    works:[],
    hostings:[],
    domains:[],
    packages:[],
    quatations: [
        {
            id:'',
            item_name: '',
            price: '',
            discount: '',
            quantity: ''
        }
    ],
});

formData.client_id=props.quotDetails.client.name;
formData.subject=props.quotDetails.subject;
formData.valid_until=props.quotDetails.valid_until;
formData.date=props.quotDetails.date;
formData.note=props.quotDetails.note;

props.editable_works.forEach(function(item, index){
    formData.works[index] = item;
});
props.edited_hosting.forEach(function(item, index){
    formData.hostings[index] = item;
});
props.edited_domains.forEach(function(item, index){
    formData.domains[index] = item;
});

props.edit_packages.forEach(function(item, index){
    formData.packages[index] = item;
});

props.quotItems.forEach(function(item, index){
    formData.quatations[index] = item;
});


let worksTitle   = "Select work services"
let domainTitle  = "Select domains"
let hostingTitle = "Select hosting"
let packageTitle = "Select packages"

const deleteItemModal = (id) => {
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


const updateQutation = () => {
    router.patch(props.update_url, formData, {
        preserveState: true,
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
        item_name: '',
        price: '',
        quantity: '',
        discount: '',
    })
}

let deleteRow = (index) => {
    formData.quatations.splice(index, 1)
}


const collupsWork = () =>{
    const element = document.getElementById("collapseExample1");
    element.classList.toggle("show");
}

const collupsService = () =>{
    const element = document.getElementById("collapseExample2");
    element.classList.toggle("show");
}
const collupsHosting = () =>{
    const element = document.getElementById("collapseExample3");
    element.classList.toggle("show");
}
const collupsPackage = () =>{
    const element = document.getElementById("collapseExample4");
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
