<template>
    <Head title="Show Invoice"/>

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="invoice-edit-wrapper">
                    <div class="row invoice-edit">
                        <InvoiceContent :isShowSidebar="props.isShowSidebar"
                                        :errors="props.errors"
                                        :invoice="props.invoice"
                                        :pref="props.pref"/>

                        <!-- Invoice Edit Right starts -->
                        <div class="col-xl-3 col-md-4 col-12" v-if="props.isShowSidebar">
                            <div class="card">
                                <div class="card-body"  v-if="this.$page.props.auth.user.can.includes('invoice.edit') ||
                                                this.$page.props.auth.user.role.includes('Administrator')">
                                    <a v-if="props.invoice.invoice_type === 'custom'" :href="props.url.edit_url" class="btn btn-primary w-100 mb-75">
                                        Edit Invoice
                                    </a>
                                    <button type="button" class="btn btn-outline-primary w-100 mb-75" @click="sendEmail">
                                        Send Email
                                    </button>
                                    <a :href="props.url.invoice_url"  class="btn btn-outline-primary w-100 mb-75">Download PDF</a>
                                    <button type="button" class="btn btn-outline-primary w-100 mb-75" data-bs-toggle="modal"
                                            data-bs-target="#givenDiscount">Give Discount</button>

                                    <button type="button" class="btn btn-outline-primary w-100 mb-75" data-bs-toggle="modal"
                                            data-bs-target="#paymentHistory">Payment History</button>

                                    <button type="button" class="btn btn-success w-100 mb-75" data-bs-toggle="modal"
                                            data-bs-target="#addPayment">Add Payment</button>
                                </div>
                                <div class="card-body" v-else>
                                    <a :href="props.url.invoice_url"  class="btn btn-outline-primary w-100 mb-75">Download PDF</a>
                                </div>
                            </div>
                            <!--                            <div class="mt-2">
                                                            <p class="mb-50">Accept payments via</p>
                                                            <select class="form-select">
                                                                <option value="Bank Account">Bank Account</option>
                                                                <option value="Paypal">Paypal</option>
                                                                <option value="UPI Transfer">UPI Transfer</option>
                                                            </select>
                                                            <div class="invoice-terms mt-1">
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="invoice-terms-title mb-0" for="paymentTerms">Payment Terms</label>
                                                                    <div class="form-check form-switch">
                                                                        <input type="checkbox" class="form-check-input" checked id="paymentTerms" />
                                                                        <label class="form-check-label" for="paymentTerms"></label>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between py-1">
                                                                    <label class="invoice-terms-title mb-0" for="clientNotes">Client Notes</label>
                                                                    <div class="form-check form-switch">
                                                                        <input type="checkbox" class="form-check-input" checked id="clientNotes" />
                                                                        <label class="form-check-label" for="clientNotes"></label>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <label class="invoice-terms-title mb-0" for="paymentStub">Payment Stub</label>
                                                                    <div class="form-check form-switch">
                                                                        <input type="checkbox" class="form-check-input" id="paymentStub" />
                                                                        <label class="form-check-label" for="paymentStub"></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>-->
                        </div>
                        <!-- Invoice Edit Right ends -->
                    </div>
                    <div v-if="props.isShowSidebar" class="modal modal-slide-in fade" id="addPayment" aria-hidden="true">
                        <div class="modal-dialog sidebar-lg">
                            <div class="modal-content p-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title">
                                        <span class="align-middle">Add Payment</span>
                                    </h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <form @submit.prevent="savePayment">
                                        <div class="mb-1">
                                            <input id="balance" class="form-control" type="text"
                                                   :value="`Invoice Balance: ${props.invoice.due}`" disabled />
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label" for="amount">Payment Amount</label>
                                            <input id="amount" v-model="paymentFormData.pay" class="form-control" @input="payAmount" type="number" placeholder="Payable Amount" />
                                            <small class="text-danger">Total Due: {{ dueAmount }}</small>
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label">Payment Date</label>
                                            <Datepicker :monthChangeOnScroll="false"
                                                        v-model="paymentFormData.date"
                                                        placeholder="Select Payment Date"
                                                        :enable-time-picker="false"
                                                        :format="'dd-MM-Y'"
                                                        autoApply></Datepicker>
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label"></label>
                                            <v-select :options="props.paymentMethods"
                                                      :reduce="payment => payment.id"
                                                      class="form-control select-padding"
                                                      v-model="paymentFormData.payment_method"
                                                      label="name"  placeholder="Select Payment Method"></v-select>
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label" for="payment-note">Internal Payment Note</label>
                                            <textarea v-model="paymentFormData.note" class="form-control" id="payment-note" rows="5" placeholder="Internal Payment Note"></textarea>
                                        </div>
                                        <div class="d-flex flex-wrap mb-0">
                                            <button type="submit" class="btn btn-primary me-1" data-bs-dismiss="modal">Save Payment</button>
                                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="props.isShowSidebar" class="modal modal-slide-in fade" id="givenDiscount" aria-hidden="true">
                        <div class="modal-dialog sidebar-lg">
                            <div class="modal-content p-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title">
                                        <span class="align-middle">Give Discount</span>
                                    </h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <form>
                                        <div class="mb-1">
                                            <input class="form-control" type="text" :value="'Grand Total Price:'+props.invoice.due" disabled />
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label" for="amount">Discount Amount</label>
                                            <input class="form-control" type="number" v-model="formData.discount" @input="discountInput" placeholder="e.g 1000 ৳" />
                                        </div>
                                        <div class="mb-1">
                                            <input class="form-control" type="text" :value="'New Price:'+newPrice" disabled />
                                        </div>

                                        <div class="d-flex flex-wrap mb-0">
                                            <button type="button" class="btn btn-primary me-1" data-bs-dismiss="modal" @click="addDiscount">Save</button>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Modal v-if="props.isShowSidebar" id="paymentHistory" title="Payment History" v-vb-is:modal size="xl">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                <th class="py-1">TRX_ID</th>
                                <th class="py-1">Taken By</th>
                                <th class="py-1">Transaction Date</th>
                                <th class="py-1">Amount</th>
                                <th class="py-1">Pay Total</th>
                                <th class="py-1">Total Due</th>
                                <th class="py-1">Payment Method</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in props.invoice.transactions" :key="item.id">
                                    <td>{{ 'Trx_#'+item.transaction_id }}</td>
                                    <td>{{ item.received_by?.name }}</td>
                                    <td>{{ moment(item.payment_date).format('ll') }}</td>
                                    <td>{{ item.amount }}</td>
                                    <td>{{ item.pay }}</td>
                                    <td>{{ item.due }}</td>
                                    <td>{{ item.method?.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </Modal>
                    <!-- Send Invoice Email Sidebar -->
                    <div class="modal modal-slide-in fade" id="sendEmail" aria-hidden="true" v-vb-is:modal>
                        <div class="modal-dialog sidebar-lg">
                            <div class="modal-content p-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title">
                                        <span class="align-middle">Send Email</span>
                                    </h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <form @submit.prevent="emailSendApiCall">
                                        <div class="mb-1">
                                            <label class="form-label">Customer Email</label>
                                            <input v-model="customerEmail" type="email" class="form-control" placeholder="e.g customer@mail.com" />
                                        </div>

                                        <div class="mb-1 d-flex flex-wrap mt-2">
                                            <button type="submit" class="btn btn-primary me-1" data-bs-dismiss="modal">Send</button>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Send Invoice Email Sidebar -->
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</template>



<script setup>
import moment from "moment";
import {computed, ref} from "vue";
import {useForm, router} from "@inertiajs/vue3";
import Modal from "@/components/Modal.vue";
import {useAction} from "@/composables/useAction.js";
import {useActionStore} from "@/Store/useActionStore.js";
import InvoiceContent from "@/components/modules/InvoiceContent.vue"
import Swal from "sweetalert2";
const props = defineProps({
    invoice:Object|[]|null,
    pref:Array|[]|null,
    paymentMethods:Object|[]|null,
    url:Array|[]|null,
    errors:Object|{}|null,
    isShowSidebar:{
        type:Boolean,
        default:true
    },
})

const {isShow} = useAction();
const actionStore = useActionStore();

const formData = useForm({
    discount:0
})

const paymentFormData = useForm({
    invoiceId:props.invoice.id,
    totalPrice:props.invoice.due,
    clientId:props.invoice.client_id,
    pay:null,
    payment_method:null,
    note:null,
    date:null,
})

const newPrice = ref(props.invoice.due)
const dueAmount = ref(props.invoice.due)
const payAmount = (event) => dueAmount.value = props.invoice.due - event.target.value
const discountInput = (event) => newPrice.value = props.invoice.due - event.target.value

const processing=ref(false);
const savePayment = () =>{
    paymentFormData.post(props.url.payment_url ,{
        preserveState: true,
        onStart: () =>{ processing.value = true},
        onFinish: () => {processing.value = false},
        onSuccess: ()=> { $toast.success('Add Payment Done...') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}

const addDiscount = () =>{
    formData.post(props.url.add_discount ,{
        preserveState: true,
        onStart: () =>{ processing.value = true},
        onFinish: () => {processing.value = false},
        onSuccess: ()=> { $toast.success('Add Discount Done...') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}



const customerEmail = ref(props.invoice?.quotation?.client?.email ?? props.invoice?.client?.email)
const sendEmail = () => {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
            denyButton: "btn btn-info",
        },
        buttonsStyling: false
    });
    swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Send Now",
        cancelButtonText: "Cancel It",
        showDenyButton: true,
        denyButtonText: `Change Mail`,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            customerEmail.value = props.invoice?.quotation?.client?.email ?? props.invoice?.client?.email
            emailSendApiCall();
        } else if (result.isDenied) {
            document.getElementById('sendEmail').$vb.modal.show()
        }else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.close()
        }
    });
}

const emailSendApiCall =()=>{
    router.post(`/admin/send-invoice-email/${props.invoice.id}`, {email:customerEmail.value}, {
        preserveState: true,
        onStart: () =>{ processing.value = true},
        onFinish: () => {processing.value = false},
        onSuccess: (message)=> { $toast.success('Email Send Successfully Done....') },
        onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
    })
}


// console.log(props.invoice.quotation.items)
const preparedForShow = computed(()=>{
    let pref = [];
    JSON.parse(props.invoice.quotation.items).map(item =>{
        if (item.checkPackages.length){
            item.checkPackages.map(pack =>{
                pref.push({
                    name:pack.descriptions,
                    qty:pack.qty,
                    price:pack.price,
                })
            })
        }
        if (item.checkFeatrueds.length){
            item.checkFeatrueds.map(fes =>{
                pref.push({
                    name:fes.name,
                    qty:fes.qty,
                    price:fes.price,
                })
            })
        }
        if(item.customItem){
            pref.push({
                name:item.customItem.description,
                qty:item.customItem.qty,
                price:item.customItem.price
            })
        }
    })
    return pref;
})


</script>

<style lang="sass">
@import "../../../sass/base/pages/app-invoice.scss"

</style>
<style lang="css">
.newlineStringStyle {
    white-space: pre-wrap;
    font-size: 11px;
}
.vs__dropdown-toggle{
    border: 1px solid;
}
.swal2-actions{
     gap:10px !important;
     flex-direction: row-reverse;
 }
</style>
