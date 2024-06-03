<template>
    <div :class="{'mx-auto': !props.isShowSidebar}" class="col-xl-9 col-md-8 col-12">
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
                            Invoice Id:  <vue-feather type="hash" size="15"/>_{{  props.invoice.invoice_id }}{{ props.invoice.id }}
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <p>Invoice Date: {{moment(props.invoice.created_at).format('D/M/Y')  }}</p>
                        </div>
                    </div>
                </div>
                <h3 v-if="props.invoice?.subject">Subject: {{ props.invoice?.subject }}</h3>
            </div>
            <!-- Header ends -->

            <hr class="invoice-spacing" />

            <!-- Address and Contact starts -->
            <div class="card-body invoice-padding pt-0">
                <div class="row invoice-spacing">
                    <div class="col-xl-8 p-0">
                        <h6 class="">Invoice To:</h6>
                        <div class="row">
                            <div class="col-md-10" v-if="props.invoice.client">
                                <h6 class="mb-25">{{ props.invoice.client.name }}</h6>
                                <p class="card-text mb-25">{{ props.invoice.client.company }}</p>
                                <p class="card-text mb-25">{{ props.invoice.client.address }}</p>
                                <p class="card-text mb-25">{{ props.invoice.client.phone ?? props.invoice.client.secondary_phone }}</p>
                                <p class="card-text mb-0">{{ props.invoice.client.email ??  props.invoice.client.secondary_email}}</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                <tr v-for="(item) in props.pref" v-if="props.pref.length">
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
                            <label class="form-label">Salesperson: {{ props.invoice.user.name }}</label>
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
                                <p class="invoice-total-amount">{{ props.invoice.total_price ?? 0 }}.00</p>
                            </div>
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Discount:</p>
                                <p class="invoice-total-amount">{{ props.invoice.discount ?? 0 }}.00</p>
                            </div>
                            <hr class="my-50" />
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Grand Total:</p>
                                <p class="invoice-total-amount">{{ props.invoice.grand_total ?? 0 }}.00</p>
                            </div>
                            <div class="invoice-total-item">
                                <p class="invoice-total-title">Total Pay:</p>
                                <p class="invoice-total-amount">{{ props.invoice.pay ?? 0}}.00</p>
                            </div>
                            <hr class="my-50">
                            <div class="invoice-total-item">
                                <strong class="invoice-total-title">Total Due:</strong>
                                <strong class="invoice-total-amount">{{ props.invoice.due ?? 0}}.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Invoice Total ends -->

            <hr class="invoice-spacing mt-0" />

            <div class="card-body invoice-padding py-0" v-if="props.invoice.note">
                <!-- Invoice Note starts -->
                <div class="row">
                    <div class="col-12">
                        <div class="mb-2" >
                            <label for="note" class="form-label fw-bold">Note:</label>
                            <p id="note">{{ props.invoice.note }}</p>
                            <p class="text-justify"></p>
                        </div>
                    </div>
                </div>
                <!-- Invoice Note ends -->
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";
    const props = defineProps({
        isShowSidebar:{
            type:Boolean
        },
        pref:Array,
        errors:Object,
        invoice:Object,
    })
</script>
