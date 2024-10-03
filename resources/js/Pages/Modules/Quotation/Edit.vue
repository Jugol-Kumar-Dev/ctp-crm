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
                                                        <v-select
                                                            v-model="formData.client_id"
                                                            :options="clients"
                                                            placeholder="Search Country Name"
                                                            :reduce="client => client.id"
                                                            label="name">
                                                            <template v-slot:option="option">
                                                                <li class="d-flex align-items-start py-1">
                                                                    <div class="d-flex align-items-center justify-content-between w-100">
                                                                        <div class="me-1 d-flex flex-column">
                                                                            <strong class="mb-25">{{ option.name }}</strong>
                                                                            <span>{{ option.email }}</span>
                                                                            <span class="text-warning">{{ option.phone }}</span>
                                                                            <small class="text-danger">{{ option.address }}</small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </template>
                                                        </v-select>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <label>Subject hrere:
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
                                    <div class="card-header">
                                        <h4 class="card-title">{{ worksTitle }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
<!--                                            <div class="col-md-4 mb-1" v-for="(option , index) in filterWOrk"-->
                                            <div class="col-md-4 mb-1" v-for="(option , index) in props.edit_works"
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
                                    <div class="card-header">
                                        <h4 class="card-title">{{ domainTitle }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in filterDomains[0]"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="option.p"
                                                                       type="checkbox" :checked="option.is_show">
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

                                <!--hosting sections-->
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ HostingTitle }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in filterHostings[0]"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="option.p"
                                                                       type="checkbox" :checked="is_show">
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


                                <!--package sections-->
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ packageTitle }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-1" v-for="(option , index) in filterPackages[0]"
                                                 :key="index">
                                                <span>{{ option.name }} <strong>({{ option.price }} Tk)</strong></span>
                                                <div class="border-1 border-light rounded-3 p-25">
                                                    <div class="input-group border-0">
                                                        <div class="input-group-text border-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" v-model="option.p"
                                                                       type="checkbox" :checked="option.is_show">
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
                                        <div class="modal-body">
                                            <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Payment Policy :
                                                        <Required/>
                                                    </label>
                                                    <div class="">
                                                        <TextEditor v-model="formData.payment_policy"></TextEditor>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <label>Terms Of Service : </label>
                                                    <div class="">
                                                        <TextEditor v-model="formData.Trams_Services"></TextEditor>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6" data-repeater-item
                                         v-for="(item, index) in formData.quatations">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">index: {{ index }} total:
                                                    {{ formData.quatations.length - 1 }}</h4>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-12">
                                                        <div class="mb-1">
                                                            <TextEditor v-model="item.itemname"
                                                                        placeholder="Item Details"/>
                                                        </div>
                                                        <div class="input-group border-0 d-flex">
                                                            <!-- <QtyButton/>-->

                                                            <input type="number" class="form-control rounded-start"
                                                                   placeholder="quantity" v-model="item.quantity">

                                                            <input type="number" class="form-control rounded-start"
                                                                   placeholder="Price" v-model="item.price">

                                                            <input type="number" class="form-control"
                                                                   placeholder="Discount" v-model="item.discount">
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
                                                    v-else
                                                    class="btn btn-danger btn-sm float-end mt-25"
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
import {computed} from "vue";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";

let props = defineProps({
        clients      : Object,
        services     : Object,
        packages     : Object,
        platforms    : Object,
        works        : Object | null,
        filters      : Object,
        domains      : Object,
        hostings     : Object,
        notification : Object,
        errors       : Object,

        edit_quot    : Object,
        edit_works: [] | null,

        eWorks       : []
    })


    let formData = useForm({
        client_id     : null,
        subject       : null,
        date          : null,
        valid_until   : null,
        payment_policy: null,
        Trams_Services: null,
        status        : null,


        hostings      : null,
        domains       : null,
        works         : null,
        packages      : null,

        quatations: null,
    });

    props.edit_works.forEach(function(item, index){
        console.log(item)
        formData.works[index] = item;
    });


    let worksTitle   = "Select work services"
    let domainTitle  = "Select domains"
    let HostingTitle = "Select hosting"
    let packageTitle = "Select packages"



    let updateQutation = () => {
        router.patch(props.edit_quot.update_url, formData, {
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
            itemname: '',
            price: '',
            discount: '',
            quantity: ''
        })
    }

    let deleteRow = (index) => {
        formData.quatations.splice(index, 1)
    }

    let filterWOrk = computed(() =>{
        let arrNew = [];
        props.edit_quot.works.forEach(obj1 => {
            let found = props.works.find(obj2 => obj2.id === obj1.id);

            arrNew.push({...obj1,});
        });
        return arrNew;
    })




    let filterDomains = computed(() =>{
        return props.edit_quot.domains.map(function (o1) {
            return props.domains.map(function (o2) {
                return {
                    name:o1.name,
                    id:o1.id,
                    price:o2.price,
                    quantity:o1.pivot.quantity > 0 ?? null,
                    discount:o1.pivot.discount  > 0 ?? null,
                    is_show: o1.id === o2.id,
                }
            });
        });
    })

    let filterHostings = computed(() =>{
        return props.edit_quot.hostings.map(function (o1) {
            return props.hostings.map(function (o2) {
                return {
                    name:o1.name,
                    id:o1.id,
                    price:o2.price,
                    quantity:o1.pivot.quantity > 0 ?? null,
                    discount:o1.pivot.discount  > 0 ?? null,
                    is_show: o1.id === o2.id,
                }
            });
        });
    })

    let filterPackages = computed(() =>{
        return props.edit_quot.packages.map(function (o1) {
            return props.packages.map(function (o2) {
                return {
                    name:o1.name,
                    id:o1.id,
                    price:o2.price,
                    quantity:o1.pivot.quantity > 0 ?? null,
                    discount:o1.pivot.discount  > 0 ?? null,
                    is_show: o1.id === o2.id,
                }
            });
        });
    })


</script>
