<template>


    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section class="invoice-preview-wrapper">
                    <div class="row invoice-preview">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justify-content-between">
                                <h4 class="card-title">Invoices Information's </h4>
                                <div>
                                    <a class="dt-button add-new btn btn-primary" href="/admin/invoices">Manage Invoices</a>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice -->
                        <div class="col-xl-9 col-md-9 col-12">
                            <div class="card invoice-preview-card">
                                <div class="card-body invoice-padding pb-0">
                                    <!-- Header starts -->
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                        <div>
                                            <div class="logo-wrapper">
                                                <h3 class="text-primary invoice-logo">Creative Tech Park</h3>
                                            </div>
                                            <p>Imperial Irish Kingdom, Mo-03 <br>(3rd Floor), Merul Badda, Dhaka 1212</p>
                                            <p>Phone: +8801639-200002</p>
                                            <p>Email: info@creativetechpark.com</p>
                                        </div>
                                        <div class="mt-md-0 mt-2">
                                            <h4 class="invoice-title">
                                                Invoice
                                                <span class="invoice-number">#{{ info.invoice_id }}</span>
                                            </h4>
                                            <div class="invoice-date-wrapper">
                                                <p class="invoice-date-title">Date Issued:</p>
                                                <p class="invoice-date">{{ info.created }}</p>
                                            </div>
<!--                                            <div class="invoice-date-wrapper">-->
<!--                                                <p class="invoice-date-title">Due Date:</p>-->
<!--                                                <p class="invoice-date">29/08/2020</p>-->
<!--                                            </div>-->
                                        </div>
                                    </div>
                                    <!-- Header ends -->
                                </div>

                                <hr class="invoice-spacing">

                                <!-- Address and Contact starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row invoice-spacing">
                                        <div class="col-xl-8 p-1">
                                            <h6 class="mb-2">Invoice To:</h6>
                                            <h6 class="mb-25">{{ info.client.name }}</h6>
                                            <p class="card-text mb-25">{{ info.client.company }}</p>
                                            <p class="card-text mb-25">{{ info.client.address }}</p>
                                            <p class="card-text mb-25">{{ info.client.phone }}</p>
                                            <p class="card-text mb-0">{{ info.client.email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Address and Contact ends -->

                                <!-- Invoice Description starts -->
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th class="py-1">Task description</th>
                                            <th class="py-1">Price</th>
                                            <th class="py-1">Discount</th>
                                            <th class="py-1">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in itemsWithTotal" :key="item.id">
                                                <td class="py-1">
                                                    <p class="card-text fw-bold mb-25" v-html="item.item_name"></p>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ item.price }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">- {{ item.discount ?? 0 }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ item.total }} Tk</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-body invoice-padding pb-0">
                                    <div class="row invoice-sales-total-wrapper">
                                        <div class="col-md-8">
                                            <p class="card-text mb-0">
                                                <span class="fw-bold">Salesperson:</span> <span
                                                class="ms-75">{{ info.creator.name }}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <table class="table table-borderless table-striped">
                                                <tr>
                                                    <td class="py-25">Sub Total</td>
                                                    <td class="py-25 text-end">{{ subTotal }} Tk</td>
                                                </tr>
                                                <tr class="border-bottom pb-1">
                                                    <td class="py-25">Total Discount</td>
                                                    <td class="py-25 text-end">- {{ totalDiscount }} Tk</td>
                                                </tr>
                                                <tr>
                                                    <th class="py-1 pt-0">Grand Total</th>
                                                    <td class="py-1 text-end">{{ grandTotal }} Tk</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row invoice-sales-total-wrapper">
                                        <div class="col-md-8">
                                        </div>
                                        <div class="col-md-4">
                                            <table class="table table-borderless table-striped">
                                                <tr class="border-bottom pb-1">
                                                    <td class="py-25">Total Pay</td>
                                                    <td class="py-25 text-end">- {{ info.total_pay }} Tk</td>
                                                </tr>
                                                <tr class="pb-1">
                                                    <th class="py-25">Total Due</th>
                                                    <td class="py-25 text-end"> {{ grandTotal - info.total_pay }} Tk</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Description ends -->

                                <!--  invoice transactions  -->


                                <hr class="invoice-spacing">

                                <!-- Invoice Note starts -->
                                <div class="card-body invoice-padding pt-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="fw-bold">Note:</span>
                                            <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
                                                projects. Thank You!</span>
                                        </div>
                                    </div>


                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <p id="inword"><strong>Inword:</strong> {{ grandTotal }} Tk Only.</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <h3>Note:</h3>
                                            <span id="mb-20">
                                                notes
                                            </span>
                                            <br>
                                            <br>
                                            <br>
                                        </div>
                                    </div>


<!--                                    <div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <p class="text-center">This is an electronically generated document, no signature is
                                                    required.</p>
                                                <p class="text-center">Created By {{ info.creator.name }}</p>
                                            </div>
                                        </div>
                                        <div class="page-break"></div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h3>Payment Mehod:</h3>
                                                <p>Pay Direct to Our Corporate Bank Account</p>
                                                <table class="table" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <th>Account Name</th>
                                                        <th>Account No</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>EASTERN BANK LTD</td>
                                                        <td>CREATIVE TECH PARK</td>
                                                        <td>1241070056170</td>
                                                    </tr>
                                                    <tr>
                                                        <td>THE CITY BANK LTD</td>
                                                        <td>CREATIVE TECH PARK</td>
                                                        <td>1502467424001</td>
                                                    </tr>
                                                    <tr>
                                                        <td>DUTCH BANGLA BANK LTD</td>
                                                        <td>CREATIVE TECH PARK</td>
                                                        <td>246.110.0005044</td>
                                                    </tr>
                                                    <tr>
                                                        <td>PREMIER BANK LTD</td>
                                                        <td>CREATIVE TECH PARK</td>
                                                        <td>13611100000331</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p><strong>Pay Using Our Merchant Mobile Banking Account:</strong></p>
                                                <p><strong>Bkash: 01639200002 (Payment)</strong></p>
                                                <ul>
                                                    <li>Go to Your bKash Mobile Menu by dialing *247#</li>
                                                    <li>Choose "Payment"</li>
                                                    <li>Enter Merchant bKash Account Number 01639200002</li>
                                                    <li>Enter the amount (Invoice Amount)</li>
                                                    <li>Enter a reference (Invoice No/ Your Name)</li>
                                                    <li>Enter Counter Number 0</li>
                                                    <li>Now enter your bKash Mobile Menu PIN to Confirm!</li>
                                                    <li>Done! You will receive a confirmation message from bKash*</li>
                                                </ul>
                                                <h3>Direct Payment Bill Online at <a href="https://creativetechpark.com/pay" >https://creativetechpark.com/pay</a>
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <h3>Payment Policy:</h3>
                                                <p v-html="info.invoice.privicy_and_policy"></p>
                                            </div>
                                        </div>

                                        <div class="row mb-50">
                                            <div class="col-6">
                                                <h3>Terms of Service:</h3>
                                                <p v-html="info.invoice.trams_and_condition"></p>
                                            </div>
                                        </div>
                                    </div>-->


                                </div>
                                <!-- Invoice Note ends -->
                            </div>
                        </div>
                        <!-- /Invoice -->



                        <!-- Invoice Actions -->
                        <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                            <div class="card">
                                <div class="card-body">
<!--                                    <button class="btn btn-primary w-100 mb-75" data-bs-toggle="modal" data-bs-target="#send-invoice-sidebar">-->
<!--                                        Send Invoice-->
<!--                                    </button>-->
                                    <a :href="info.download_url" class="btn btn-outline-secondary w-100 btn-download-invoice mb-75">Download</a>
<!--                                    <a class="btn btn-outline-secondary w-100 mb-75" href="./app-invoice-print.html" > Print </a>-->
                                    <button type="button" class="btn btn-primary w-100 mb-75" data-bs-toggle="modal" data-bs-target="#showTransactions">Payment History</button>
                                    <button class="btn btn-success w-100 mb-75" data-bs-toggle="modal" data-bs-target="#add-payment-sidebar">Add Payment</button>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Actions -->


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
                                                <small class="text-danger"> Total Due: {{ grandTotal - createForm.pay_amount - createForm.discount - info.total_pay  }} TK</small>
                                            </div>

                                            <div class="mb-1">
                                                <label class="form-label"></label>
                                                <v-select v-model="createForm.payment_id"
                                                          class="form-control select-padding"
                                                          :options="props.info.payment_methods"
                                                          :reduce="payment => payment.id"
                                                         label="name"  placeholder="Select Payment Method"></v-select>
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
                                                <textarea class="form-control" name="invoice-message" id="invoice-message" cols="3" rows="11" placeholder="Message...">
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


                        <!-- Show Transactions Modal -->
                        <div class="modal fade" id="showTransactions" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered modal-edit-user">
                                <div class="modal-content">
                                    <div class="modal-header bg-transparent">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pb-5 px-sm-5 pt-50">
                                        <div class="text-center mb-2">
                                            <h1 class="mb-1">{{ info.invoice.client.name }}</h1>
                                            <p>All Payment lists</p>
                                        </div>

                                        <table class="table table-striped table-borderless">
                                            <thead>
                                            <tr>
                                                <th class="py-1">Taken By</th>
                                                <th class="py-1">Transaction Date</th>
                                                <th class="py-1">Amount</th>
                                                <th class="py-1">Discount</th>
                                                <th class="py-1">Pay Total</th>
                                                <th class="py-1">Sub Total</th>
                                                <th class="py-1">Total Due</th>
                                                <th class="py-1">Payment Method</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(item, index) in info.transactions" :key="item.id">
                                                <td class="py-1">
                                                    <Link href="#" class="text-decoration-none">
                                                        {{ item.user.name }}
                                                    </Link>
                                                </td>
                                                <td>
                                                    {{ item.date }}
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ item.amount }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ item.discount ?? 0 }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ item.pay_amount }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ item.old_total_pay }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span class="fw-bold">{{ grandTotal - item.old_total_pay }} Tk</span>
                                                </td>
                                                <td class="py-1">
                                                    <span>{{ item.method }}</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Show Transactions Modal -->


                    </div>
                </section>
            </div>
        </div>
    </div>


</template>
<script>

</script>
<script setup>
    import { computed, ref } from "vue";
    import {useForm} from "@inertiajs/vue3";
    import {router} from "@inertiajs/vue3";

    let props = defineProps({
        info: Object,
    });

    let createForm = useForm({
        grandTotal: null,
        payment_id:null,
        pay_amount:null,
        discount:null,
        payment_note:null,
        invoice_id:props.info.invoice.id,
    })

    let addPayment = () => {

        router.post(props.info.payment_url, createForm, {
            onSuccess: () => {
                Swal.fire(
                    'Added!',
                    'Transition Added Successfully Done',
                    'success'
                )
            }
        })

        console.log(createForm);
    }


    let itemsWithTotal = computed(() => {
        return props.info.invoice_item.map(a =>{
            return {...a, total: a.price - a.discount}
        })
    });

    let subTotal = computed(()=>{
        let sum = 0;
        props.info.invoice_item.map(a =>{
            sum = sum + a.price
        })
        return sum;
    })

    const paymentAmount = ref(0)

    let totalDiscount = computed(()=>{
        let sum = 0;
        props.info.invoice_item.map(a =>{
            sum = sum + a.discount
        })
        return sum;
    })

    let grandTotal = computed(()=>{
        let sum = 0;
        props.info.invoice_item.map(a =>{
            sum = sum + (a.price - a.discount)
        })
        createForm.grandTotal = sum
        return sum;
    })


</script>



<style lang="css">

</style>
