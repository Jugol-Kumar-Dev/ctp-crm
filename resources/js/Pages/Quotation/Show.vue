<template>
    <Head title="Show Quotation"/>

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
                                                <img src="../../../../public/creativeTechPark.png" alt="" height="30">
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
                                                Quotation Id:  <vue-feather type="hash" size="15"/>_{{  props.quotation.quotation_id }}{{ props.quotation.id }}
                                            </div>
                                            <div class="d-flex align-items-center mb-1 flex-column">
                                                <p>Created on: {{moment(props.quotation.qut_date).format('D-M-Y')  }}</p>
                                                <p>Valid until: {{moment(props.quotation.due_date).format('D-M-Y')  }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Header ends -->

                                <hr class="invoice-spacing" />

                                <!-- Address and Contact starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row invoice-spacing">
                                        <div class="col-xl-8 p-0">
                                            <h6 class="">Quotation To:</h6>
                                            <div class="row">
                                                <div class="col-md-10" v-if="props.quotation.client">
                                                    <h6 class="mb-25">{{ props.quotation.client.name }}</h6>
                                                    <p class="card-text mb-25">{{ props.quotation.client.company }}</p>
                                                    <p class="card-text mb-25">{{ props.quotation.client.address }}</p>
                                                    <p class="card-text mb-25">{{ props.quotation.client.phone ?? props.quotation.client.secondary_phone }}</p>
                                                    <p class="card-text mb-0">{{ props.quotation.client.email ??  props.quotation.client.secondary_email}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="subject-line" v-if="props.quotation?.subject">Subject: {{ props.quotation.subject }}</h4>
                                </div>
                                <!-- Address and Contact ends -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Details</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item) in preparedForShow" v-if="preparedForShow.length">
                                            <td>
                                                <span class="newlineStringStyle">{{ item.name }}</span>
                                            </td>
                                            <td class="text-end price_qty">{{ item.price }} * {{ item.qty }}</td>
                                        </tr>
                                    </tbody>
                                </table>




                                <!-- Invoice Total starts -->
                                <div class="card-body invoice-padding pb-5">
                                    <div class="row invoice-sales-total-wrapper">
                                        <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <label class="form-label">Salesperson: {{ props.quotation.user.name }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                                            <div class="invoice-total-wrapper">
                                                <!--                                                <div class="invoice-total-item">
                                                                                                    <p class="invoice-total-title">Subtotal:</p>
                                                                                                    <p class="invoice-total-amount">{{ props.subtotal }} Tk</p>
                                                                                                </div>
                                                                                                <div class="invoice-total-item">
                                                                                                    <p class="invoice-total-title">Discount:</p>
                                                                                                    <p class="invoice-total-amount">$28</p>
                                                                                                </div>
                                                                                                <div class="invoice-total-item">
                                                                                                    <p class="invoice-total-title">Tax:</p>
                                                                                                    <p class="invoice-total-amount">21%</p>
                                                                                                </div>-->
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Total:</p>
                                                    <p class="invoice-total-amount">{{ props.quotation.total_price }}.00</p>
                                                </div>
                                                <div class="invoice-total-item">
                                                    <p class="invoice-total-title">Discount:</p>
                                                    <p class="invoice-total-amount">{{ props.quotation.discount }}.00</p>
                                                </div>
                                                <hr class="my-50" />
                                                <div class="invoice-total-item">
                                                    <strong class="invoice-total-title">Grand Total:</strong>
                                                    <strong class="invoice-total-amount">{{ props.quotation.grand_total }}.00</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Total ends -->

                                <hr class="invoice-spacing mt-0" v-if="props.quotation?.note"/>

                                <div class="card-body invoice-padding py-0">
                                    <!-- Invoice Note starts -->
                                    <div class="row" v-if="props.quotation?.note">
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <label for="note" class="form-label fw-bold">Note:</label>
                                                <p id="note">{{ props.quotation.note }}</p>
                                                <p class="text-justify"></p>
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
                                <div class="card-body" v-if="this.$page.props.auth.user.can.includes('quotation.edit') ||
                                this.$page.props.auth.user.role.includes('Administrator')" >
                                    <a :href="props.url.edit_url" class="btn btn-primary w-100 mb-75">
                                        Edit Quotation
                                    </a>
                                    <button type="button" class="btn btn-outline-primary w-100 mb-75" @click="sendEmail">
                                        Send Email
                                    </button>


                                    <a :href="props.url.show_url+'?download=true'"  class="btn btn-outline-primary w-100 mb-75">Download PDF</a>
                                    <a :href="props.url.show_url+'?print=true'"  class="btn btn-outline-primary w-100 mb-75">Print Quotation</a>


                                    <button type="button" class="btn btn-outline-primary w-100 mb-75" data-bs-toggle="modal"
                                            data-bs-target="#givenDiscount">Give Discount</button>
                                    <button v-if="props.quotation.invoice === null" class="btn btn-success w-100 mb-75"
                                            data-bs-toggle="modal"
                                            data-bs-target="#createInvoice">
                                        Generate Invoices
                                    </button>
                                    <a :href="props.url.invoice_url" v-else  class="btn btn-success w-100 mb-75">
                                        Get Invoices
                                    </a>
                                </div>
                                <div class="card-body" v-else>
                                    <a :href="props.url.show_url+'?download=true'"  class="btn btn-outline-primary w-100 mb-75">Download PDF</a>
                                    <a :href="props.url.show_url+'?print=true'"  class="btn btn-outline-primary w-100 mb-75">Print Quotation</a>
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

                    <!-- Send Invoice Sidebar -->
                    <div class="modal modal-slide-in fade" id="createInvoice" aria-hidden="true">
                        <div class="modal-dialog sidebar-lg">
                            <div class="modal-content p-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title">
                                        <span class="align-middle">Create Invoice</span>
                                    </h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <form @submit.prevent="createInvoice">
                                        <div class="mb-1">
                                            <label class="form-label">Total Price</label>
                                            <input type="text" class="form-control" :value="props.quotation.grand_total" readonly disabled/>
                                        </div>


                                        <div class="mb-1">
                                            <label class="form-label">Give Discount</label>
                                            <input type="text" v-model="invoiceFormData.discount" @input="invoiceDiscount" class="form-control" placeholder="If want to given again discount..."/>
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label">Payable Amount</label>
                                            <input type="text" class="form-control" :value="payableAmount" readonly disabled/>
                                        </div>


                                        <div class="mb-1">
                                            <label class="form-label">Total Paid</label>
                                            <input type="text" v-model="invoiceFormData.pay" @input="payAmount" class="form-control"/>
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label">Total Due</label>
                                            <input type="text" class="form-control" :value="dueAmount" readonly disabled/>
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label"></label>
                                            <v-select :options="props.paymentMethods"
                                                      :reduce="payment => payment.id"
                                                      v-model="invoiceFormData.payment_method"
                                                      class="form-control"
                                                      label="name"  placeholder="Select Payment Method"></v-select>
                                        </div>

                                        <div class="mb-1">
                                            <div class="d-flex justify-content-between">
                                                <label class="invoice-terms-title mb-0" for="sendEmailInvoice">Send Invoice Client Email </label>
                                                <div class="form-check form-switch">
                                                    <input v-model="invoiceFormData.sendEmail" type="checkbox" class="form-check-input" checked id="sendEmailInvoice" />
                                                    <label class="form-check-label" for="sendEmailInvoice"></label>
                                                </div>
                                            </div>

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
                    <!-- Add Payment Sidebar -->
                    <div class="modal modal-slide-in fade" id="givenDiscount" aria-hidden="true">
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
                                            <input class="form-control" type="text" :value="'Grand Total Price:'+props.quotation.total_price" disabled />
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
                    <!-- /Add Payment Sidebar -->
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</template>



<script setup>
    import moment from "moment";
    import {computed, ref} from "vue";
    import {useForm} from "@inertiajs/vue3";
    import Swal from "sweetalert2";
    import {router} from "@inertiajs/vue3";

    const props = defineProps({
        quotation:Object|[]|null,
        paymentMethods:Object|[]|null,
        url:Array|[]|null,
        errors:Object|{}|null,
    })

    const formData = useForm({
        discount:0
    })

    const invoiceFormData = useForm({
        quotationId:props.quotation.id,
        totalPrice:props.quotation.grand_total,
        clientId:props.quotation.client_id,
        sendEmail:false,
        discount:null,
        pay:null,
        payment_method:null,
        note:null,
    })

    const newPrice= ref(props.quotation.total_price)
    const payableAmount = ref(props.quotation.grand_total)
    const dueAmount = ref(payableAmount.value)
    const discountInput = (event) =>{
        newPrice.value = props.quotation.total_price - event.target.value
    }

    const invoiceDiscount = (event) =>{
        invoiceFormData.pay = null;
        let amount = props.quotation.grand_total - event.target.value
        payableAmount.value = amount;
        dueAmount.value = amount;
    }

    const payAmount = (event) =>{
        dueAmount.value = payableAmount.value - event.target.value
    }

    const processing=ref(false);
    const addDiscount = () =>{
        formData.post(props.url.add_discount ,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> { $toast.success('Quotation Discount Done...') },
            onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
        })
    }

    const createInvoice =()=>{
        invoiceFormData.post(props.url.create_invoice ,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> { $toast.success('Invoice Created Successfully Done...') },
            onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
        })
    }

    const preparedForShow = computed(()=>{
        let pref = [];
        JSON.parse(props.quotation.items).map(item =>{
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
    const customerEmail = ref(props.quotation.client.email)
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
                customerEmail.value = props.quotation.client.email
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
        router.post(`/admin/send-quotation-email/${props.quotation.id}`, {email:customerEmail.value}, {
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: (message)=> { $toast.success('Email Send Successfully Done....') },
            onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
        })
    }

</script>

<style lang="sass" scoped>
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
