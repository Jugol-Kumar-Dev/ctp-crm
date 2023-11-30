<template>
    <Head title="Show Old Invoice"/>

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
                        <div class="col-xl-9 col-md-8 col-12">
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
                                                    Invoice Id:  <vue-feather type="hash" size="15"/>{{ moment(props.content?.date).format('DDMMyyyy')+''+props.content?.id  }}
                                                </div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <p>Valid Date: {{moment(props.content.created_at).format('D/M/Y')  }}</p>
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
                                                <h6 class="">Invoice To:</h6>
                                                <div class="row">
                                                    <div class="col-md-10" v-if="props.content.quotation?.client">
                                                        <h6 class="mb-25">{{ props.content?.quotation?.client.name }}</h6>
                                                        <p class="card-text mb-25">{{ props.content?.quotation?.client.company }}</p>
                                                        <p class="card-text mb-25">{{ props.content?.quotation?.client.address }}</p>
                                                        <p class="card-text mb-25">{{ props.content?.quotation?.client.phone ?? props.content?.quotation?.client.secondary_phone }}</p>
                                                        <p class="card-text mb-0">{{ props.content?.quotation?.client.email ??  props.content?.quotation?.client.secondary_email}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="ps-2">
                                        <strong>Subject:</strong>
                                         {{ props.content.quotation?.subject }}</h4>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="newlineStringStyle">{{ props.content.quotation?.additional }}</p>

                                        <p class="newlineStringStyle">{{ props.content?.quotation?.additional_price }} Tk</p>
                                    </div>

                                    <!-- Invoice Total starts -->
                                    <div class="card-body invoice-padding">
                                        <div class="row invoice-sales-total-wrapper">
                                            <div class="col-md-6 order-md-1 order-2 mt-md-0 mt-3">
<!--                                                <div class="d-flex align-items-center mb-1">-->
<!--                                                    <label class="form-label">Salesperson: {{ props.invoice.user.name }}</label>-->
<!--                                                </div>-->
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
                                                        <p class="invoice-total-title">Sub Total:</p>
                                                        <p class="invoice-total-amount">{{ props.content?.sub_total}} Tk</p>
                                                    </div>
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">Discount:</p>
                                                        <p class="invoice-total-amount">{{ props.content?.discount }} Tk</p>
                                                    </div>
                                                    <hr class="my-50" />
                                                    <div class="invoice-total-item">
                                                        <p class="invoice-total-title">Grand Total:</p>
                                                        <p class="invoice-total-amount">{{ props.content?.grand_total }} Tk</p>
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
                                                    <p id="note">{{ props.content?.note }}</p>
                                                    <p class="text-justify"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Invoice Note ends -->
                                    </div>
                                </div>
                            </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
</template>



<script setup>
import moment from "moment";
import {computed, ref} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import Modal from "../../components/Modal.vue"
import {useAction} from "../../composables/useAction";
import {useActionStore} from "../../Store/useActionStore";
import InvoiceContent from "../../components/modules/InvoiceContent.vue"
const props = defineProps({
    content:Object|[]|null,
})
</script>

<style lang="sass">
@import "../../../sass/base/pages/app-invoice.scss"

</style>
<style lang="css">
.newlineStringStyle {
    white-space: pre-wrap;
    font-size: 15px;
    padding:0 2rem;
}
.vs__dropdown-toggle{
    border: 1px solid;
}
</style>
