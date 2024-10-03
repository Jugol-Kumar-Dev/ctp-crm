<template>
    <section class="invoice-edit-wrapper">
        <div class="row invoice-edit">
            <!-- Invoice Edit Left starts -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card">
                    <div class="card-header">
                        <ul>
                            <li class="text-danger" v-for="error in props.errors">{{ error }}</li>
                        </ul>
                    </div>

                    <!-- Header starts -->
                    <div class="card-body invoice-padding pb-0">
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div class="col-md-5">
                                <div class="logo-wrapper">
                                    <img src="../../../../../public/creativeTechPark.png" alt="" height="30">
                                </div>
                                <h3>Creative Tech Park</h3>
                                <p class="card-text mb-25">
                                    Imperial Irish Kingdom, Mo-03
                                    (3rd Floor), Merul Badda, Dhaka 1212
                                </p>
                                <p class="p-0 m-0">Phone: +8801639-200002</p>
                                <p>Email: info@creativetechpark.com</p>
                            </div>
                            <div class="invoice-number-date mt-md-0 mt-2">
                                <div class="d-flex align-items-center justify-content-md-end mb-1">
                                    <div class="input-group input-group-merge invoice-edit-input-group">
                                        <div class="input-group-text">
                                            <vue-feather type="hash" size="15"/>_id
                                        </div>
                                        <input type="text" class="form-control invoice-edit-input"
                                               :value="quotationStore.getQuotId" readonly/>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <Datepicker v-model="formData.date"
                                                :monthChangeOnScroll="false"
                                                :enable-time-picker="false"
                                                :format="'dd-MM-Y'"
                                                placeholder="Select Quotation Date"
                                                autoApply></Datepicker>
                                </div>
                                <!--                                            <div class="d-flex align-items-center">
                                                                                <span class="title">Due Date:</span>
                                                                                <Datepicker :monthChangeOnScroll="false"
                                                                                            placeholder="Select Date" autoApply></Datepicker>
                                                                            </div>-->
                            </div>
                        </div>
                        <input type="text" v-model="formData.subject" class="form-control mt-2 mb-1" style="margin-bottom:-20px" placeholder="e.g Subject...">
                    </div>
                    <!-- Header ends -->

                    <hr class="invoice-spacing" />

                    <!-- Address and Contact starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row invoice-spacing">
                            <div class="col-xl-8 p-0">
                                <h6 class="mb-2">Invoice To:</h6>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group col-md-5">
                                            <v-select :options="props.clients"
                                                      style="width: 100%"
                                                      label="name"
                                                      class="form-control select-padding py-0"
                                                      v-model="formData.clientId"
                                                      :reduce="client => client.id"
                                                      @update:modelValue="loadClient"
                                                      :filter="fuseSearch"
                                                      placeholder="e.g Select Client">
                                                <template v-slot:option="option">
<!--
                                                    <li class="d-flex align-items-start border-bottom">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="me-1 d-flex flex-column " >
                                                                <strong class="mb-25">{{ option.name }}</strong>
                                                                <span >{{ option.email }}</span>
                                                                <span >{{ option.phone }}
                                                                   <span v-if="option.secondary_phone">/ {{ option.secondary_phone}}</span>
                                                               </span>
                                                            </div>
                                                        </div>
                                                    </li>
-->

                                                    <li class="d-flex align-items-start py-1">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="me-1 d-flex flex-column">
                                                                <span>{{ option.name }}</span>
                                                                <span>{{ option.email ?? ''}}</span>
                                                                <span>{{ option.phone ?? ''}}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </template>
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="mt-2 col-md-10" v-if="clientDetails">
                                        <h6 class="mb-25">{{ clientDetails.name }}</h6>
                                        <p class="card-text mb-25">{{ clientDetails.company }}</p>
                                        <p class="card-text mb-25">{{ clientDetails.address }}</p>
                                        <p class="card-text mb-25">{{ clientDetails.phone ?? clientDetails.secondary_phone }}</p>
                                        <p class="card-text mb-0">{{ clientDetails.email ??  clientDetails.secondary_email}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Address and Contact ends -->
                    <slot/>
                    <!-- Invoice Total starts -->
                    <div class="card-body invoice-padding">
                        <div class="row invoice-sales-total-wrapper">
                            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                <div class="d-flex align-items-center mb-1">
                                    <label class="form-label">Salesperson:</label>
                                    <input type="text" class="form-control ms-50 " readonly :value="$page.props.auth.user.username"/>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                <div class="invoice-total-wrapper">
                                    <hr class="my-50" />
                                    <div class="invoice-total-item">
                                        <p class="invoice-total-title">Total:</p>
                                        <p class="invoice-total-amount">{{ props.subtotal }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Invoice Total ends -->

                    <hr class="invoice-spacing mt-0" />

                    <div class="card-body invoice-padding py-0">
                        <!-- Invoice Note starts -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="note" class="form-label fw-bold">Note:</label>
                                    <textarea class="form-control" rows="2" v-model="formData.note" id="note" placeholder="keep your note"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Invoice Note ends -->
                    </div>
                </div>
            </div>
            <!-- Invoice Edit Left ends -->

            <!-- Invoice Edit Right starts -->
            <div class="col-xl-3 col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary w-100 mb-75" @click="saveInvoice">
                            Save Invoice
                        </button>
                    </div>

                </div>
                <div class="mt-2">
                    <div class="invoice-terms mt-1">
                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentStub">Service Policy</label>
                            <div class="form-check form-switch">
                                <input v-model="formData.attachServicePolicy" type="checkbox" class="form-check-input" id="paymentStub" />
                                <label class="form-check-label" for="paymentStub"></label>
                                <vue-feather type="edit" size="20" class="cursor-pointer" v-c-tooltip="'Edit This Service Policy'"
                                             @click="editServicePolicy"/>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentTerms">Payment Policy</label>
                            <div class="form-check form-switch">
                                <input v-model="formData.attachPaymentPolicy" type="checkbox" class="form-check-input" checked id="paymentTerms" />
                                <label class="form-check-label" for="paymentTerms"></label>
                                <vue-feather type="edit" size="20" class="cursor-pointer" v-c-tooltip="'Edit This Payment Policy'"
                                             @click="editPaymentPolicy"/>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentMethods">Payment Methods</label>
                            <div class="form-check form-switch">
                                <input v-model="formData.attachPaymentMethods" type="checkbox" class="form-check-input" checked id="paymentMethods" />
                                <label class="form-check-label" for="paymentMethods"></label>
                                <vue-feather type="edit" size="20" class="cursor-pointer" v-c-tooltip="'Edit This Payment Methods'"
                                             @click="editPaymentMethods"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center justify-content-between gap-5" style="margin-top: 5px;">
                                <label class="invoice-terms-title mb-0" for="currency">Currency</label>
                                <input type="text" v-model="formData.currency" class="form-control" placeholder="e.g currency" id="currency">
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div class="mt-2">
                    <div class="invoice-terms mt-1">
                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentTerms">Payment Policy</label>
                            <div class="form-check form-switch">
                                <input v-model="formData.attachPaymentPolicy" type="checkbox" class="form-check-input" checked id="paymentTerms" />
                                <label class="form-check-label" for="paymentTerms"></label>
                                <vue-feather type="edit" size="20" class="cursor-pointer" v-c-tooltip="'Edit This Payment Policy'" @click="editPaymentPolicy"/>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <label class="invoice-terms-title mb-0" for="paymentStub">Service Policy</label>
                            <div class="form-check form-switch">
                                <input v-model="formData.attachServicePolicy" type="checkbox" class="form-check-input" id="paymentStub" />
                                <label class="form-check-label" for="paymentStub"></label>
                                <vue-feather type="edit" size="20" class="cursor-pointer" v-c-tooltip="'Edit This Service Policy'" @click="editServicePolicy"/>
                            </div>
                        </div>

                    </div>
                </div>-->
            </div>
            <!-- Invoice Edit Right ends -->
        </div>

        <!-- Send Invoice Sidebar -->
        <div class="modal modal-slide-in fade" id="send-invoice-sidebar" aria-hidden="true">
            <div class="modal-dialog sidebar-lg">
                <div class="modal-content p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">
                            <span class="align-middle">Send Invoice</span>
                        </h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <form>
                            <div class="mb-1">
                                <label for="invoice-from" class="form-label">From</label>
                                <input type="text" class="form-control" id="invoice-from" value="shelbyComapny@email.com" placeholder="company@email.com" />
                            </div>
                            <div class="mb-1">
                                <label for="invoice-to" class="form-label">To</label>
                                <input type="text" class="form-control" id="invoice-to" value="qConsolidated@email.com" placeholder="company@email.com" />
                            </div>
                            <div class="mb-1">
                                <label for="invoice-subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="invoice-subject" value="Invoice of purchased Admin Templates" placeholder="Invoice regarding goods" />
                            </div>
                            <div class="mb-1">
                                <label for="invoice-message" class="form-label">Message</label>
                                <textarea class="form-control" name="invoice-message" id="invoice-message" cols="3" rows="11">
                                            Dear Queen Consolidated,

                                            Thank you for your business, always a pleasure to work with you!

                                            We have generated a new invoice in the amount of $95.59

                                            We would appreciate payment of this invoice by 05/11/2019
                                            </textarea>
                            </div>
                            <div class="mb-1">
                                <span class="badge badge-light-primary">
                                    <i data-feather="link" class="me-25"></i>
                                    <span class="align-middle">Invoice Attached</span>
                                </span>
                            </div>
                            <div class="mb-1 d-flex flex-wrap mt-2">
                                <button type="button" class="btn btn-primary me-1" data-bs-dismiss="modal">Send</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Send Invoice Sidebar -->

        <!-- Add Payment Sidebar -->
        <div class="modal modal-slide-in fade" id="add-payment-sidebar" aria-hidden="true">
            <div class="modal-dialog sidebar-lg">
                <div class="modal-content p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                    <div class="modal-header mb-1">
                        <h5 class="modal-title">
                            <span class="align-middle">Add Payment</span>
                        </h5>
                    </div>
                    <div class="modal-body flex-grow-1">
                        <form>
                            <div class="mb-1">
                                <input id="balance" class="form-control" type="text" value="Invoice Balance: 5000.00" disabled />
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="amount">Payment Amount</label>
                                <input id="amount" class="form-control" type="number" placeholder="$1000" />
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="payment-date">Payment Date</label>
                                <input id="payment-date" class="form-control date-picker" type="text" />
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="payment-method">Payment Method</label>
                                <select class="form-select" id="payment-method">
                                    <option value="" selected disabled>Select payment method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Debit">Debit</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Paypal">Paypal</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label class="form-label" for="payment-note">Internal Payment Note</label>
                                <textarea class="form-control" id="payment-note" rows="5" placeholder="Internal Payment Note"></textarea>
                            </div>
                            <div class="d-flex flex-wrap mb-0">
                                <button type="button" class="btn btn-primary me-1" data-bs-dismiss="modal">Send</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Payment Sidebar -->

        <Modal id="paymentPolicy" title="Edit Payment Policy" v-vb-is:modal size="lg">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <textarea v-model="formData.paymentPolicy" type="text"
                                  placeholder="Edit payment policy"
                                  rows="5" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light" data-bs-dismiss="modal">ok</button>
            </div>
        </Modal>
        <Modal id="servicePolicy" title="Edit Service Policy" v-vb-is:modal size="lg">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                                    <textarea v-model="formData.servicePolicy" type="text"
                                              placeholder="Edit Service Policy"
                                              rows="5" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light" data-bs-dismiss="modal">ok</button>
            </div>
        </Modal>
        <Modal id="payemntMethods" title="Edit Payment Methods" v-vb-is:modal size="lg">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <textarea v-model="formData.payemtnPolicy" type="text"
                                  placeholder="Edit Payment Methods"
                                  rows="5" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light" data-bs-dismiss="modal">ok</button>
            </div>
        </Modal>

    </section>
</template>

<script setup>
import moment from 'moment/moment';
import {ref, computed,onMounted  } from "vue";
import {useQuotationStore} from "../../../Store/useQuotationStore";
import Fuse from "fuse.js";
import {useForm} from "@inertiajs/vue3";
import {usePolicyStore} from "../../../Store/usePolicyStore";
import Modal from "../../../components/Modal.vue";
import {storeToRefs} from "pinia";

const quotationStore = useQuotationStore();
const policyStore = usePolicyStore();
policyStore.setServicePolicy();
const servicePolicy  = storeToRefs(policyStore);
const props = defineProps({
    subtotal:{
        type:Number,
        default:0
    },
    clients:{
        type:Array,
        default:[],
        required:true
    },
    errors:{
        type:Object,
        default:[]
    }
})

const formData = useForm({
    clientId:null,
    date:null,
    note:null,
    subject:null,
    paymentPolicy:servicePolicy.getPaymentPolicy,
    servicePolicy:servicePolicy.getTramsAndCondition,
    payemtnPolicy:servicePolicy.getPaymentMethods,
    attachPaymentPolicy:true,
    attachServicePolicy:true,
    attachPaymentMethods:true,
    currency:'Taka',
})



const fuseSearch = (options, search) => {
    const fuse = new Fuse(options, {
        keys: ['name', 'email', 'phone','secondary_email', 'secondary_phone', 'company', 'address', 'status'],
        shouldSort: true,
    })
    return search.length
        ? fuse.search(search).map(({ item }) => item)
        : fuse.list
}

const clientDetails = ref(null)
const loadClient = (clientId)=> clientDetails.value = props.clients.filter(item => item.id === clientId)[0];

const editPaymentPolicy = () => document.getElementById('paymentPolicy').$vb.modal.show()
const editServicePolicy = () => document.getElementById('servicePolicy').$vb.modal.show()
const editPaymentMethods = () => document.getElementById('payemntMethods').$vb.modal.show()



const emits = defineEmits(["storeEvent"])
const saveInvoice =()=> emits("storeEvent", formData)

</script>

<style lang="sass" scoped>
@import "../../../../sass/base/pages/app-invoice.scss"
</style>

