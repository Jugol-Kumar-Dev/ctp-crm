<template>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section class="invoice-preview-wrapper">
                    <div class="row invoice-preview">
                        <!-- Invoice -->
                        <div class="col-xl-9 col-md-8 col-12">
                            <div class="card invoice-preview-card">
                                <div class="card-body invoice-padding pb-0">
                                    <!-- Header starts -->
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                        <div>

                                            <div class="logo-wrapper">
                                                <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                                    <defs>
                                                        <linearGradient id="invoice-linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                                            <stop stop-color="#000000" offset="0%"></stop>
                                                            <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="invoice-linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                                            <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                            <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                        </linearGradient>
                                                    </defs>
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-400.000000, -178.000000)">
                                                            <g transform="translate(400.000000, 178.000000)">
                                                                <path class="text-primary" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill: currentColor"></path>
                                                                <path d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#invoice-linearGradient-1)" opacity="0.2"></path>
                                                                <polygon fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                                <polygon fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                                <polygon fill="url(#invoice-linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <h3 class="text-primary invoice-logo">Vuexy</h3>
                                            </div>

                                            <h3 class="card-text">Creative Tech Park</h3>
                                            <p class="card-text">Imperial Irish Kingdom, Mo-03 <br>(3rd Floor), Merul Badda, Dhaka 1212</p>
                                            <p class="card-text mb-0 pb-0">Phone: +8801639-200002</p>
                                            <p class="card-text">Email: info@creativetechpark.com</p>

                                        </div>
                                        <div class="mt-md-0 mt-2">
                                            <h4 class="invoice-title">
                                                Invoice
                                                <span class="invoice-number">#{{ info.invoice.u_id+info.invoice.id }}</span>
                                            </h4>
                                            <div class="invoice-date-wrapper">
                                                <p class="invoice-date-title">Date:</p>
                                                <p class="invoice-date">
                                                    {{ moment(info.invoice.created_at).format('ll')}}
                                                    <span class="text-info cursor-pointer"  v-c-tooltip="`Invoice Created Date`" >
                                                        <vue-feather type="info" size="10"/>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Header ends -->
                                </div>

                                <hr class="invoice-spacing" />
                                <!-- Address and Contact starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row invoice-spacing">
                                        <div class="col-xl-8 p-0">
                                            <h6 class="mb-2">Invoice To:</h6>
                                            <h6 class="mb-25" v-if="info.quotation_owner.client.name">{{ info.quotation_owner.client.name }}</h6>
                                            <p class="card-text mb-25" v-if="info.quotation_owner.client.company">{{ info.quotation_owner.client.company}}</p>
                                            <p class="card-text mb-25" v-if="info.quotation_owner.client.address">{{ info.quotation_owner.client.address}}</p>
                                            <p class="card-text mb-25" v-if="info.quotation_owner.client.phone || info.quotation_owner.client.secondary_phone" >
                                                {{ info.quotation_owner.client.phone ?? info.quotation_owner.client.secondary_phone }}
                                            </p>
                                            <p class="card-text mb-0" v-if="info.quotation_owner.client.email || info.quotation_owner.client.secondary_email" >
                                                {{ info.quotation_owner.client.email || info.quotation_owner.client.secondary_email }}
                                            </p>
                                        </div>

                                        <div class="col-xl-4 p-0 mt-xl-0 mt-2">
                                            <h6 class="mb-2">Payment Details:</h6>
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td class="pe-1">Total Due:</td>
                                                    <td><span class="fw-bold">{{ info.invoice.due }} Tk</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">Bank name:</td>
                                                    <td>American Bank</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">Country:</td>
                                                    <td>United States</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">IBAN:</td>
                                                    <td>ETD95476213874685</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-1">SWIFT code:</td>
                                                    <td>BR91905</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>


                                <!-- Address and Contact ends -->

                                <!-- Invoice Description starts -->
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="py-1">Service description</th>
                                            <th class="py-1">Price</th>
                                            <th class="py-1">Discount</th>
                                            <th class="py-1">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, index) in  allData">
                                            <td class="py-1">
                                                <h5 class="card-text fw-bold mb-25 text-capitalize" v-html="item.name"></h5>
                                                Price: <small>{{ item.price }} * {{ item.quantity }} = {{ item.price * item.quantity }} Tk</small>
                                            </td>
                                            <td class="py-1">
                                                <span class="fw-bold"> {{ item.price * item.quantity }} </span>
                                            </td>
                                            <td class="py-1">
                                                <span class="fw-bold">{{ item.discount }}</span>
                                            </td>
                                            <td class="py-1">
                                                <span class="fw-bold">{{ (item.price * item.quantity) - item.discount }} Tk</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-body invoice-padding pb-0">
                                    <div class="row invoice-sales-total-wrapper">
                                        <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                            <p class="card-text mb-0">
                                                <span class="fw-bold">Salesperson:</span>
                                                <span class="ms-75 text-capitalize">
                                                    {{ info.quotation_owner.creator.name }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                            <div class="invoice-total-wrapper">
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Subtotal:</p>
                                                    <p class="invoice-total-amount">{{ props.info.quotation.price }} Tk</p>
                                                </div>
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Discount:</p>
                                                    <p class="invoice-total-amount">{{ props.info.quotation.discount }} Tk</p>
                                                </div>
                                                <!--                                                <div class="invoice-total-item">
                                                                                                    <p class="invoice-total-title">Tax:</p>
                                                                                                    <p class="invoice-total-amount">21%</p>
                                                                                                </div>-->
                                                <hr class="my-50" />
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Total:</p>
                                                    <p class="invoice-total-amount">{{ grandTotal }} Tk</p>
                                                </div>

                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Total Pay:</p>
                                                    <p class="invoice-total-amount">{{ props.info.invoice.pay }} Tk</p>
                                                </div>
                                                <hr class="my-50" />
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Total Due:</p>
                                                    <p class="invoice-total-amount">{{ props.info.invoice.due }} Tk</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Description ends -->

                                <hr class="invoice-spacing" />

                                <!-- Invoice Note starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <span class="fw-bold me-1">Note:</span>
                                            <span v-html="info.quotation.note"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Note ends -->
                            </div>
                        </div>
                        <!-- /Invoice -->

                        <!-- Invoice Actions -->
                        <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-primary w-100 mb-75" data-bs-toggle="modal" data-bs-target="#send-invoice-sidebar">
                                        Send Invoice
                                    </button>
                                    <a :href="info.others_info.create_invoice+'?is_invoice=true'" class="btn btn-outline-secondary w-100 btn-download-invoice mb-75">Download</a>
                                    <button class="btn btn-info w-100 mb-75" @click="openTransactions">Transaction History</button>
                                    <button class="btn btn-success w-100 mb-75" data-bs-toggle="modal" data-bs-target="#add-payment-sidebar">Add Payment</button>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Actions -->
                    </div>
                </section>
            </div>
        </div>
    </div>




    <!-- Add Payment Sidebar -->
    <div class="modal modal-slide-in fade" id="add-payment-sidebar" aria-hidden="true">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">
                            Add Payment
                        </span>

                        <span class="text-info cursor-pointer"
                              @click="openTransactions"
                              v-c-tooltip="{content: `Click For See All \nTransaction About This Invoice`, placement: 'right'}" >

                            <vue-feather type="info" size="15"/>
                        </span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form @submit.prevent="addPayment">
                        <div class="mb-1">
                            <input id="balance" class="form-control" type="text" :value="`Grand Total: `+grandTotal+` TK`" disabled />
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="amount">Given Discount</label>
                            <input class="form-control"
                                   v-model="createForm.discount"
                                   @input="createForm.discount = $event.target.value"
                                   type="number" placeholder="Enter Discount Amount"/>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="amount">Payment Amount</label>
                            <input id="amount" class="form-control"
                                   v-model="createForm.pay_amount"
                                   @input="createForm.pay_amount = $event.target.value"
                                   type="number" placeholder="Enter Payment Amount"/>
                            <small class="text-danger"> Total Due: {{ grandTotal - createForm.pay_amount - createForm.discount - info.invoice.pay  }} TK</small>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Payment Method: <Required/></label>
                            <v-select v-model="createForm.payment_id" :options="props.info.payment_methods" :reduce="payment => payment.id"
                                      label="name"  placeholder="Select Payment Method"></v-select>
                            <InputFieldError :errors="errors.payment_id"/>

                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="payment-note">Internal Payment Note</label>
                            <textarea class="form-control" id="payment-note" v-model="createForm.payment_note" rows="5" placeholder="Internal Payment Note"></textarea>
                        </div>

                        <div class="d-flex flex-wrap mb-0">
                            <button type="submit" class="btn btn-primary me-1" data-bs-dismiss="modal">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Payment Sidebar -->


    <!-- Show Transactions Modal -->
    <Modal class="modal fade" id="showTransactions" v-vb-is:modal tabindex="-1" size="lg" aria-hidden="true">
        <div class="modal-body">
            <div class="text-center mb-2">
                <p>All Payment lists</p>
            </div>

            <table class="table table-striped table-borderless">
                <thead>
                <tr>
                    <th class="py-1" v-c-tooltip="`Transaction Id`">#Id</th>
                    <th class="py-1">Total pay</th>
                    <th class="py-1">Receive by</th>
                    <th class="py-1">Payment By</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="item in info.invoice.transactions" :key="item.u_id">
                        <td>
                            <a href="javascript:void(0)">#{{ item.u_id }}</a>
                        </td>
                        <td>
                            Pay
                            <span class="cursor-pointer" v-c-tooltip="`Payable Abount`">{{item.pay_amount}}</span> +
                            <span class="cursor-pointer" v-c-tooltip="`Given Discount`">{{item.discount ?? 0}}</span> =
                            <span class="cursor-pointer" v-c-tooltip="`Total Amount`">{{item.pay_amount + item.discount}} Tk</span>
                            <br>
                            Due <span>{{ item.total_due }} Tk</span>
                        </td>
                        <td>
                            <span class="text-capitalize">{{ item.user.name  }}</span>
                        </td>
                        <td><span class="text-capitalize">{{ item.method.name }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="cursor-pointer text-info me-2" v-if="item.note" v-c-tooltip="item?.note">
                                    <vue-feather type="info"/>
                                </span>
                                <span class="cursor-pointer text-warning" v-c-tooltip="`Download Payment Statement`">
                                    <vue-feather type="download"/>
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </Modal>
    <!--/ Show Transactions Modal -->



</template>

<script setup>

import { computed } from "vue";
import {useForm} from "@inertiajs/vue3";
import {router} from "@inertiajs/vue3";
import {useDate} from "@/composables/useDate.js";
import Modal from '@/components/Modal.vue'
import moment from 'moment';
import Swal from "sweetalert2";
import InputFieldError from "@/components/InputFieldError.vue";

let { formatted } = useDate();

let props = defineProps({
    info:Object,
    errors:Object | null,
})
let updateForm = useForm({
    quotId: props.info.quotation.id,
    status:props.info.quotation.status,
    processing:false,
})


const endDate = moment(props.info.dates.valid_until, "DD.MM.YYYY");
const startDate = moment(props.info.dates.date, "DD.MM.YYYY");
const diffDate = computed(() => endDate.diff(startDate, 'days'))



let allData = computed(() =>{
    return [...props.info.others_info.items].map(item => {
        return {
            name     : item.name ?? item.itemname,
            price    : parseInt(item.price) ?? 0,
            quantity : parseInt(item.quantity) ?? 1,
            discount : parseInt(item.discount) ?? 0,
            qtyprice : (parseInt(item.price) ?? 0) * (parseInt(item.quantity) ?? 1),
            subTotal : ((parseInt(item.price) ?? 0) * (parseInt(item.quantity) ?? 1)) - parseInt(item.discount) ?? 0,
        }
    });
})


let subTotal = computed(() =>{
    let sum = 0;
    [...props.info.others_info.items].map(item => sum = sum + ((parseInt(item.price) ?? 0) * (parseInt(item.quantity) ?? 1)))
    return sum;
})



let grandTotal = computed(() =>{
    return props.info.quotation.price - props.info.quotation.discount;
})


let createForm = useForm({
    pay_amount:null,
    discount:null,
    payment_note:null,
    quotation_id:props.info.quotation.id,
})

let addPayment = () => {
    router.post(props.info.add_payment, createForm, {
        onSuccess: () => {
            createForm.reset();
            Swal.fire(
                'Added!',
                'Transition Added Successfully Done',
                'success'
            )
        },
        onError: (res)=>{
            Swal.fire(
                'Transaction Failed!',
                res.payment_id,
                'error'
            )
        }
    })
}

const openTransactions =()=> document.getElementById('showTransactions').$vb.modal.show();




</script>

<style lang="sass">
@import "../../../../sass/base/pages/app-invoice"
</style>
