<template>
    <Head title="Print Quotation"/>

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
                        <div class="col-xl-12 col-md-8 col-12" id="pdfElements">
                            <div class="card invoice-preview-card">
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
                                            <div class="d-flex align-items-center mb-1">
                                                <p>Valid Date: {{moment(props.quotation.qut_date).format('D/M/Y')  }}</p>
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
                                                <div class="col-md-10" v-if=" props.quotation.client">
                                                    <h6 class="mb-25">{{ props.quotation.client.name }}</h6>
                                                    <p class="card-text mb-25">{{ props.quotation.client.company }}</p>
                                                    <p class="card-text mb-25">{{ props.quotation.client.address }}</p>
                                                    <p class="card-text mb-25">{{ props.quotation.client.phone ?? props.quotation.client.secondary_phone }}</p>
                                                    <p class="card-text mb-0">{{ props.quotation.client.email ??  props.quotation.client.secondary_email}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="subject-line">Subject: THis si my subject</h4>
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
                                <div class="card-body invoice-padding">
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
                                                    <p class="invoice-total-amount">0.00</p>
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

                                <hr class="invoice-spacing mt-0" />

                                <div class="card-body invoice-padding py-0">
                                    <!-- Invoice Note starts -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <label for="note" class="form-label fw-bold">Note:</label>
                                                <p id="note">{{ props.quotation.note }}</p>
                                                <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquam dicta esse nemo officia perspiciatis reiciendis velit. Aperiam commodi delectus deserunt ea exercitationem iste nobis quaerat sint. Accusamus accusantium, ad assumenda at blanditiis consequuntur cum cumque cupiditate deserunt dicta doloremque eligendi enim error est facilis fugiat harum in iste iure magnam nihil placeat porro provident quaerat quibusdam quod reprehenderit repudiandae saepe sint suscipit. Alias animi assumenda, at atque beatae cumque debitis delectus dignissimos ducimus eligendi excepturi facilis inventore ipsa ipsam labore optio perspiciatis placeat, porro qui quia quos repellat temporibus vel velit veniam. Eligendi ipsa odio optio ratione repellendus unde!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice Note ends -->
                                </div>



                                <div class="page-break">
                                    <h3>Payment Method:</h3>
                                    <p>Pay Direct to Our Corporate Bank Account</p>
                                    <table class="table table-striped">
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
                                    <h3>Direct Payment Bill Online at <a href="https://creativetechpark.com/pay" >https://creativetechpark.com/pay</a></h3>

                                    <!--                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam delectus eos perspiciatis provident quidem repudiandae suscipit ullam. Ab beatae doloremque eos facilis, magnam non totam? Ad maxime molestias numquam voluptatum!</p>-->

                                </div>

                            </div>

                        </div>
                        <!-- Invoice Edit Left ends -->

                        <!-- Invoice Edit Right starts -->
                        <div class="col-xl-3 col-md-4 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-primary w-100 mb-75" @click="saveQuotation">
                                        Save Quotation
                                    </button>
                                    <button class="btn btn-outline-primary w-100 mb-75" @click="createPDF">Download PDF</button>
                                    <button type="button" class="btn btn-outline-primary w-100 mb-75">Save</button>
                                    <button class="btn btn-success w-100 mb-75" data-bs-toggle="modal" data-bs-target="#add-payment-sidebar">
                                        Add Payment
                                    </button>
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
                </section>
            </div>
        </div>
    </div>
    <button class="btn btn-primary printBtn" @click="createPDF">Generate PDF</button>
</template>

<script>
// export default {
//     layout:null
// }
</script>

<script setup>
import moment from "moment";
import {computed} from "vue";

const props = defineProps({
    quotation:Object|[]|null,
})
const pdfOptions = {
    margin: [10, 10, 10, 0], // Set the margins to 10mm on each side
    filename: 'my_document.pdf', // Set the filename for the PDF
    image: { type: 'jpeg', quality: 0.98 }, // Set the image quality to 98%
    html2canvas: { scale: 2 }, // Scale the HTML content by a factor of 2 for better resolution
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' } // Set the PDF unit to millimeters, the format to A4, and the orientation to portrait
};

const createPDF = () =>{
    const element = document.getElementById("pdfElements");
    // html2pdf(element);
    html2pdf().set(pdfOptions).from(element).save();

};

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

    })
    return pref;
})


</script>

<style lang="sass" scoped>
@import "../../../sass/base/pages/app-invoice.scss"

</style>

<style lang="css" scoped>

.newlineStringStyle {
    white-space: pre-wrap;
    font-size: 11px;
}
.table td{
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    border: none !important;
}
.table tr{
    border-bottom: 1px dotted #cfcfcf;
}
.price_qty{
    font-size:11px;
}
.page-break {
    page-break-before: always;
    margin-bottom: 50px;
}
.text-justify{
    text-align: justify;
}
.subject-line{
    position: relative;
    left: -10px;
    bottom: -24px;
}
</style>
