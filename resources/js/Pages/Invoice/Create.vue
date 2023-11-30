<template>
    <Head title="Create New Invoice"/>

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
                                    <h4 class="card-title">Invoice Information's </h4>
                                    <Link href="/admin/invoices" class="dt-button add-new btn btn-primary">Manage
                                        Invoice
                                    </Link>
                                </div>
                            </div>
                        </div>


<!--                        <ul class="nav nav-tabs" id="myTab" role="tablist">
&lt;!&ndash;                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab"
                                        data-bs-toggle="tab" data-bs-target="#home"
                                        type="button" role="tab" aria-controls="home"
                                        aria-selected="true">Form Quotation</button>
                            </li>&ndash;&gt;
&lt;!&ndash;                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab"
                                        data-bs-toggle="tab" data-bs-target="#profile"
                                        type="button" role="tab" aria-controls="profile"
                                        aria-selected="false">Custom Invoice</button>
                            </li>&ndash;&gt;

                        </ul>-->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="col-md-8 mx-auto">
                                    <div class="card">
                                        <div class="card-body">
                                            <h2>Quotation To Invoice</h2>


                                            <form @submit.prevent="createInvoice" class="mt-3">


                                                <div class="col-md mb-1">
                                                    <label>Selet Quotation </label>
                                                    <div class="">
                                                        <v-select v-model="invoiceFormData.quotationId"
                                                                  :options="quotations"
                                                                  :reduce="qut => qut.id"
                                                                  class="form-control select-padding"
                                                                  @update:modelValue="selectQuotation"
                                                                  label="subject" placeholder="Select Quotation"></v-select>
                                                        <InputFieldError :errors="errors.client_id"/>
                                                    </div>
                                                </div>

                                                <div class="mb-1">
                                                    <label class="form-label">Total Price</label>
                                                    <input type="text" class="form-control" readonly disabled/>
                                                </div>


                                                <div class="mb-1">
                                                    <label class="form-label">Given Discount</label>
                                                    <input type="text" v-model="invoiceFormData.discount" @input="invoiceDiscount" class="form-control" placeholder="If want to given again discount..."/>
                                                </div>

                                                <div class="mb-1">
                                                    <label class="form-label">Payable Amount</label>
                                                    <input type="text" class="form-control" readonly disabled/>
                                                </div>


                                                <div class="mb-1">
                                                    <label class="form-label">Total pay</label>
                                                    <input type="text" v-model="invoiceFormData.pay" @input="payAmount" class="form-control" placeholder="If want to given again discount..."/>
                                                </div>

                                                <div class="mb-1">
                                                    <label class="form-label">Total Due</label>
                                                    <input type="text" class="form-control"  readonly disabled/>
                                                </div>

                                                <div class="mb-1">
                                                    <label class="form-label"></label>
                                                    <v-select :options="props.paymentMethods"
                                                              :reduce="payment => payment.id"
                                                              class="form-control select-padding"
                                                              v-model="invoiceFormData.payment_method"
                                                              label="name"  placeholder="Select Payment Method"></v-select>
                                                </div>


                                                <div class="mb-1">
                                                    <label class="form-label">Note</label>
                                                    <textarea class="form-control"
                                                              v-model="invoiceFormData.note"
                                                              cols="3" rows="5" placeholder="Want to Say Something...?"></textarea>
                                                </div>

                                                <div class="mb-1 d-flex flex-wrap mt-2">
                                                    <button type="submit" class="btn btn-primary me-1" data-bs-dismiss="modal">Create</button>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>





                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <StoreInvoice  :subtotal="totalPrice" :clients="props.clients" :errors="props.errors" @storeEvent="saveInvoice">
                                    <div class="card-body invoice-padding invoice-product-details" v-for="(item, index) in formData.items">
                                        <div class="source-item">
                                            <div data-repeater-list="group-a" >
                                                <div class="repeater-wrapper">
                                                    <div class="row">
                                                        <div class="col-12 d-flex product-details-border position-relative pe-0">
                                                            <p class="card-text col-title mb-md-50 mb-0">Item</p>
                                                            <div class="row w-100 pe-lg-0 pe-1 py-2">
                                                                <div class="col-12">
                                                                    <div class="mb-1">
                                                                        <textarea v-model="formData.items[index].description" class="form-control" rows="4" placeholder="Item Details"></textarea>
                                                                    </div>
                                                                    <div class="input-group border-0 d-flex">
                                                                        <input v-model="formData.items[index].price" type="number" class="form-control rounded-start"
                                                                               placeholder="Price" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column align-items-center justify-content-between invoice-product-actions py-50 px-25">
                                                                <vue-feather type="x" class="cursor-pointer font-medium-3" @click="removeItem(index)"></vue-feather>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" @click="addItem" class="btn btn-primary btn-sm btn-add-new" data-repeater-create>
                                            <i data-feather="plus" class="me-25"></i>
                                            <span class="align-middle">Add Item</span>
                                        </button>
                                    </div>
                                </StoreInvoice>
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
import Pagination from "../../components/Pagination"
import Icon from '../../components/Icon'
import Modal from '../../components/Modal'
import {ref, watch,computed} from "vue";
import debounce from "lodash/debounce";
import {Inertia} from "@inertiajs/inertia";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/inertia-vue3";
import TextEditor from "../../components/TextEditor";
import TextArea from "../../components/Textarea";
import QuantityButton from "../../components/QuantityButton";
import ServiceItem from "../../components/ServiceItem";
import ServiceCard from "../../components/ServiceCard";
import InputFieldError from "../../components/InputFieldError";
import QtyButton from "../../components/QtyButton";

import StoreInvoice from './partial/StoreInvoice.vue'

let props = defineProps({
    clients: Object,
    paymentMethods:Array|[]|null,
    quotations:Array|[]|null,
    errors: null,
    store_url:null,
})



const invoiceFormData = useForm({
    quotationId:null,
    totalPrice:null,
    discount:null,
    pay:null,
    payment_method:null,
    note:null,
})


const selectQuotation = (event) =>{
    console.log(event)
}


const formData = useForm({
    totalPrice:0,
    items:[{
        description:null,
        price:null,
    }]
})
const processing = ref(false)
const saveInvoice = (events) =>{
    Inertia.post(props.store_url, {...formData, ...events}, {
        preserveState: true,
        onStart: () =>{ processing.value = true},
        onFinish: () => {processing.value = false},
        onSuccess: ()=> { $toast.success('Invoice Created Successfully Done...') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}

let addItem = () => {
    formData.items.push({
        description: null,
        price: null,
    })
}

let removeItem = (index) => formData.items.splice(index, 1)


const totalPrice = computed(()=>{
    let totalPrice = 0;
    formData.items.map(item =>{
        totalPrice = totalPrice + item.price;
    })
    formData.totalPrice = totalPrice;
    return totalPrice;

})

</script>


<style lang="scss">
/*@import "../../../../sass/base/plugins/tables/datatables";*/
</style>
