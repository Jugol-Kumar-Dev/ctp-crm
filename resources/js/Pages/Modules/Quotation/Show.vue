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
                                    <div>
                                        <Link href="/admin/quotations" class="dt-button add-new btn btn-primary">Manage
                                            Quotations
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mx-auto">
                            <div class="row match-height">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Client Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                <h3 class="text-capitalize" v-if="props.info">Client Name: {{ props.info.quotation_owner.client.name }}</h3>
                                                <tr>
                                                    <td v-if="props.info">Client Email: {{ props.info.quotation_owner.client.email }}</td>
                                                </tr>
                                                <tr>
                                                    <td v-if="props.info">Client Phone: {{ props.info.quotation_owner.client.phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td v-if="props.info">Client Secondery Phone: {{
                                                        props.info.quotation_owner.client.secondary_phone }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td v-if="props.info">Client Phone: {{ props.info.quotation_owner.client.address }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Quotation Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                <h3>Quotation Id: CTP-{{ props.info.quotation.id }}</h3>
                                                <tr>
                                                    <td>Quotation Validate: {{  props.info.dates.date}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Quotation Validate: {{ props.info.dates.valid_until }}</td>
                                                </tr>
                                                <tr>
                                                    <td v-if="props.info.quotation_owner">Quotation Creator: {{ props.info.quotation_owner.creator.name }}</td>
                                                </tr>
                                                <tr>
                                                    <td v-if="props.info.quotation_owner">Creator Email: {{ props.info.quotation_owner.creator.email }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Subject:</h2>
                                    </div>
                                    <div class="card-body">

                                        <table class="table table-borderless table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-left bg-primary text-white">Description</th>
                                                <th class="text-right bg-primary text-white">Price</th>
                                                <th class="text-right bg-primary text-white">Discount</th>
                                                <th class="text-right bg-primary text-white">Total</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr v-for="(item, index) in allData" :key="item.id">
                                                <td class="">
                                                    <p v-html="item.name"></p>
                                                </td>
                                                <td class="text-end">
                                                    <p>{{ item.price }} * {{ item.quantity }} = {{ item.qtyprice }} Tk</p>
                                                </td>
                                                <td class="text-end">
                                                    <p>{{ item.discount }} Tk</p>
                                                </td>
                                                <td class="text-end">
                                                    <p>{{ item.subTotal }} Tk</p>
                                                </td>
                                            </tr>
                                            </tbody>

                                            <tfoot class="table_bottom_border">
                                                <tr class="text-end">
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right">Sub Total =</td>
                                                    <td class="text-right"><strong> {{ subTotal }} Tk </strong></td>
                                                </tr>
                                                <tr class="text-end">
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right">Discount =</td>
                                                    <td class="text-right">
                                                        <strong>- {{ discount }} Tk</strong>
                                                    </td>
                                                </tr>
                                                <tr class="text-end">
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right border-top">Grand Total =</td>
                                                    <td class="text-right border-top"><strong>{{ grandTotal }} Tk</strong></td>
                                                </tr>

                                                <tr class="text-end">
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right">Total Pay =</td>
                                                    <td class="text-right"><strong>- {{ info.total_pay }} Tk</strong></td>
                                                </tr>
                                                <tr class="text-end">
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right border-top">Total Due =</td>
                                                    <td class="text-right border-top"><strong>{{ grandTotal - info.total_pay }} Tk</strong></td>
                                                </tr>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                                <div class="card">
                                    <div class="card-body">
                                        <a :href="info.others_info.create_invoice" class="btn btn-outline-secondary w-100 btn-download-invoice mb-75">Download</a>
                                        <button type="button" class="btn btn-primary w-100 mb-75" data-bs-toggle="modal" data-bs-target="#showTransactions">Payment History</button>
                                        <button class="btn btn-success w-100 mb-75" data-bs-toggle="modal" data-bs-target="#add-payment-sidebar">Add Payment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Advanced Search -->
                <!--/ Multilingual -->


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
                                        <label class="form-label">Select Payment Methods</label>
                                        <v-select v-model="createForm.method_id"
                                                  @update:modelValue="subCategorySelected"
                                                  label="name"
                                                  :options="props.info.payment_methods"
                                                  placeholder="~~Select Payment Method~~"
                                                  :reduce="optoin => optoin.id"></v-select>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label" for="payment-note">Internal Payment Note</label>
                                        <textarea class="form-control" id="payment-note" v-model="createForm.payment_note"
                                                  rows="5" placeholder="Internal Payment Note"></textarea>
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
                <div class="modal fade" id="showTransactions" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-edit-user">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-5 px-sm-5 pt-50">
                                <div class="text-center mb-2">
                                    <h1 class="mb-1" v-if="info">{{ info.quotation_owner.client.name }}</h1>
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
                                            {{ formatted(item.date) }}
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
                                            <span class="fw-bold">{{ item.amount - item.old_total_pay }} Tk</span>
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
        </div>
    </div>

</template>

<script setup>

    import { computed } from "vue";
    import {useForm} from "@inertiajs/vue3";
    import {router} from "@inertiajs/vue3";
    import {useDate} from "../../../composables/useDate";

    let { formatted } = useDate();

    let props = defineProps({
        info:Object,
    })
    let createForm = useForm({
        grandTotal: null,
        payment_id: null,
        pay_amount: null,
        discount: null,
        payment_note: null,
        method_id: null,
        quotation_id:props.info.quotation.id,
    })


    let addPayment = () => {
        router.post(props.info.payment_url, createForm, {
            onSuccess: () => {
                createForm.reset();
                alert("saved Transaction")
            }
        })
        console.log(createForm);
    }


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
            let sum = 0;
         [...props.info.others_info.items].map(item => sum = sum + ((parseInt(item.price) ?? 0) * (parseInt(item.quantity) ?? 1)) - parseInt(item.discount) ?? 0)
        createForm.grandTotal = sum
        return sum;
    })

    let discount = computed(() => {
        let sum = 0;
        [...props.info.others_info.items].map(item => sum = sum +  parseInt(item.discount) ?? 0)
        return sum;
    })










</script>

<style scoped>
    .table_bottom_border{
        border-top: 1px solid darkblue;
    }
</style>
