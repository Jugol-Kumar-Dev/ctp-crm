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
                                    <h4 class="card-title">Quotation #{{ moment(new Date()).format('YYYYMMD') }}__</h4>
                                    <Link href="/admin/quotations" class="dt-button add-new btn btn-primary">Manage
                                        Quotations
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <form class="row" @submit.prevent="createQutation">
                            <div class="col-md-9">
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
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.works"
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
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.domains"
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
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.hostings"
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
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.packages"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="option.p"
                                                                       type="checkbox">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" v-model="option.price">
                                                        <input type="hidden" v-model="option.id">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="option.quantity" placeholder="quantity">
                                                        <input type="number" class="form-control border-0"
                                                               v-model="option.discount" placeholder="Discount">
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
                                    <div class="col-md-12" data-repeater-item
                                         v-for="(item, index) in formData.quatations">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Custom Item's</h4>
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
                                        <div>
                                            <button type="submit"
                                                    class="btn btn-success waves-effect waves-float waves-light me-2">
                                                Save & Back
                                            </button>
                                            <button type="submit"
                                                    class="btn btn-primary waves-effect waves-float waves-light me-2">
                                                Save & Send
                                            </button>
                                            <button type="reset" class="btn btn-outline-warning me-2"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close">Form Reset
                                            </button>
                                            <a href="/admin/quotations"
                                                    class="btn btn-outline-secondary waves-effect waves-float waves-light me-2">
                                                Go To Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="card">
                                   <div class="card-body">
                                       <h2>Quotation Details</h2>
                                       <div class="mb-1">
                                           <label>Client <Required/></label>
                                           <div class="">
                                               <v-select
                                                   v-model="formData.client_id"
                                                   :options="clients"
                                                   placeholder="Search Country Name"
                                                   :reduce="client => client.id"
                                                   :filter="fuseSearch"
                                                   label="name">
                                                   <template v-slot:option="option">
                                                       <li class="d-flex align-items-start py-1">
                                                           <div class="d-flex align-items-center justify-content-between w-100">
                                                               <div class="me-1 d-flex flex-column">
                                                                   <strong class="mb-25">{{ option.name }}</strong>
                                                                   <span >{{ option.email }}</span>
                                                                   <span >{{ option.phone }}
                                                                       <span v-if="option.secondary_phone">/ {{ option.secondary_phone}}</span>
                                                                   </span>
                                                               </div>
                                                           </div>
                                                       </li>
                                                   </template>
                                               </v-select>





                                               <InputFieldError :errors="errors.client_id"/>
                                           </div>
                                       </div>
                                       <div class="mb-1">
                                            <label>Subject <Required/></label>
                                           <div class="">
                                               <input type="text" placeholder="Subjects" v-model="formData.subject" class="form-control">
                                               <InputFieldError :errors="errors.subject"/>
                                           </div>
                                       </div>
                                       <div class="mb-1">
                                           <label>Date <Required/></label>
                                           <div class="">
                                               <Datepicker v-model="formData.date" :monthChangeOnScroll="false"
                                                           placeholder="Select Date" autoApply></Datepicker>
                                               <InputFieldError :errors="errors.date"/>
                                           </div>
                                       </div>

                                       <div class="mb-1">
                                           <label>Valid until <Required/> </label>
                                           <div class="">
                                               <Datepicker v-model="formData.valid_until"
                                                           :monthChangeOnScroll="false"
                                                           placeholder="Select Date" autoApply></Datepicker>
                                               <InputFieldError :errors="errors.valid_until"/>
                                           </div>
                                       </div>
                                   </div>
                               </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h3>Given Discount</h3>
                                        <div class="col-md">
                                            <div class="">
                                                <input class="form-control" v-model="formData.discount" placeholder="e.g 0.00 Tk"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h3>Quotation Status</h3>
                                        <div class="col-md">
                                            <div class="">
                                                <v-select v-model="formData.status"
                                                          width="100%"
                                                          label="name"
                                                          :options="status"
                                                          placeholder="Select Quotation Status"></v-select>
                                                <InputFieldError :errors="errors.status"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h3>Payment Policy</h3>
                                        <div class="col-md">
                                            <div class="">
                                                <TextEditor v-model="formData.payment_policy"></TextEditor>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h3>Terms Of Service</h3>
                                        <div class="col-md">
                                            <div class="">
                                                <TextEditor v-model="formData.Trams_Services"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    import moment from 'moment'
    import {paymentPolicy, tramsConditions} from '../chartConfig.js'

    let props = defineProps({
        clients      : null,
        services     : null,
        packages     : null,
        platforms    : null,
        works        : null,
        filters      : null,
        domains      : null,
        hostings     : null,
        notification : null,
        errors       : null,
        methods      : null,
    })

    let formData = useForm({
        client_id     : null,
        subject       : null,
        date          : null,
        valid_until   : null,
        payment_policy: paymentPolicy,
        Trams_Services: tramsConditions,
        discount      : null,
        status        : null,
        note          : null,

        works         : [],
        domains       : [],
        hostings      : [],
        packages      : [],

        quatations: [
            {
                item_name: '',
                price: '',
                discount: '',
                quantity: ''
            }
        ],


    });


    const fuseSearch = (options, search) => {
        const fuse = new Fuse(options, {
            keys: ['name', 'email', 'phone','secondary_email', 'secondary_phone', 'company', 'address', 'status'],
            shouldSort: true,
        })
        return search.length
            ? fuse.search(search).map(({ item }) => item)
            : fuse.list
    }



    let status = [
        {"name":'New Quotation'}, {"name":'Sent'}, {"name":'Feedback'}
    ]
    props.works.forEach(function(item, index){
        formData.works[index] = item;
    });

    props.domains.forEach(function(item, index){
        formData.domains[index] = item;
    });

    props.hostings.forEach(function(item, index){
        formData.hostings[index] = item;
    });

    props.packages.forEach(function(item, index){
        formData.packages[index] = item;
    });



    let worksTitle   = "Select work services"
    let domainTitle  = "Select domains"
    let hostingTitle = "Select hosting"
    let packageTitle = "Select packages"


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
            item_name: '',
            price: '',
            quantity: '',
            discount: '',
        })
    }

    let deleteRow = (index) => {
        formData.quatations.splice(index, 1)
    }

    let collupsWork = () =>{
        const element = document.getElementById("collapseExample1");
        element.classList.toggle("show");
    }

    let collupsService = () =>{
        const element = document.getElementById("collapseExample2");
        element.classList.toggle("show");
    }
    let collupsHosting = () =>{
        const element = document.getElementById("collapseExample3");
        element.classList.toggle("show");
    }
    let collupsPackage = () =>{
        const element = document.getElementById("collapseExample4");
        element.classList.toggle("show");
    }
</script>

<style scoped>

    .webkitTransaction{
        -webkit-transition: all 5s ease-in-out;
        -moz-transition: all 5s ease-in-out;
        -ms-transition: all 5s ease-in-out;
        -o-transition: all 5s ease-in-out;
        transition: all 5s ease-in-out;
    }
</style>
