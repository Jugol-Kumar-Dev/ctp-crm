<template>
    <Head title="Website Settings"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section class="app-dashboard">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="card card-statistics">
                    <div class="card-header">
                        <vue-notification-list></vue-notification-list>
                        <h4 class="card-title">Settings</h4>
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="nav flex-column nav-pills me-3 border text-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active"
                                            id="v-pills-smtp-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-smtp"
                                            type="button" role="tab"
                                            aria-controls="v-pills-smtp"
                                            aria-selected="false">SMTP Setup</button>

                                    <button class="nav-link"
                                            id="v-pills-tac-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-tac"
                                            type="button" role="tab"
                                            aria-controls="v-pills-tac"
                                            aria-selected="false">Terms and conditions</button>

                                    <button class="nav-link"
                                            id="v-pills-payment_policy-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-payment_policy"
                                            type="button" role="tab"
                                            aria-controls="v-pills-payment_policy"
                                            aria-selected="false">Payment Policy</button>

                                    <button class="nav-link"
                                            id="v-pills-payment_method-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-payment_method"
                                            type="button" role="tab"
                                            aria-controls="v-pills-payment_method"
                                            aria-selected="false">Payment Methods</button>


                                    <button class="nav-link"
                                            id="v-pills-quotation_template-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-quotation_template"
                                            type="button" role="tab"
                                            aria-controls="v-pills-quotation_template"
                                            aria-selected="false">Quotation Template</button>

                                    <button class="nav-link"
                                            id="v-pills-invoice_template-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#v-pills-invoice_template"
                                            type="button" role="tab"
                                            aria-controls="v-pills-invoice_template"
                                            aria-selected="false">Invoice Template</button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-smtp" role="tabpanel" aria-labelledby="v-pills-smtp">
                                        <div class="card">
                                            <h2>SMTP Setup</h2>
                                            <form class="form form-vertical" @submit.prevent="updateMailSetup()">
                                                <div class="row">
                                                    <div class="col-12 mb-1">
                                                        <label>Mail Driver</label>
                                                        <v-select :options="smtpMillers"
                                                                  placeholder="e.g Mail Driver"
                                                                 v-model="mailSetup.mailDriver" label="name"
                                                                 :reduce="item =>item.name"
                                                                 class="text-capitalize">
                                                        </v-select>
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label>Mail Host</label>
                                                        <input type="text" class="form-control" v-model="mailSetup.mailHost" placeholder="e.g Mail Host" />
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label>Mail Port</label>
                                                        <input type="text" class="form-control" v-model="mailSetup.mailPort" placeholder="e.g Mail Port" />
                                                    </div>


                                                    <div class="col-12 mb-1">
                                                        <label>Username</label>
                                                        <input type="text" class="form-control" v-model="mailSetup.username" placeholder="e.g Username" />
                                                    </div>


                                                    <div class="col-12 mb-1">
                                                        <label>Password</label>
                                                        <input type="text" class="form-control" v-model="mailSetup.password" placeholder="e.g Password" />
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label>From Email</label>
                                                        <input type="text" class="form-control" v-model="mailSetup.fromEmail" placeholder="e.g Form Email" />
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label>From Name</label>
                                                        <input type="text" class="form-control" v-model="mailSetup.fromName" placeholder="e.g Form Name" />
                                                    </div>

                                                    <div class="col-12 mb-1">
                                                        <label>Encryption</label>
                                                        <select class="form-control" v-model="mailSetup.encryption">
                                                            <option disabled selected value="">Select Mail Encryption</option>
                                                            <option value="null">NULL</option>
                                                            <option value="tls">TLS</option>
                                                            <option value="ssl">SSL</option>
                                                        </select>
                                                    </div>


                                                    <div class="col-12 mt-2 d-inline-flex align-item-center">
                                                        <button v-if="!isLoding" type="submit" disabled class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                            <div class="spinner-border text-white me-1"  role="status"></div>
                                                            <span>Submit</span>
                                                        </button>

                                                        <button v-else class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade show" id="v-pills-tac" role="tabpanel" aria-labelledby="v-pills-tac">
                                        <div class="card">
                                            <h2>Terms And Condition</h2>
                                            <form class="form form-vertical" @submit.prevent="updateSettings">
                                                <textarea type="text"
                                                          v-model="createForm.trams_and_condition"
                                                      placeholder="Edit trams and conditions"
                                                      rows="5" class="form-control"></textarea>

                                                <div class="col-12 mt-2 d-inline-flex align-item-center">
                                                    <button v-if="!isLoding" type="submit" disabled class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        <div class="spinner-border text-white me-1"  role="status"></div>
                                                        <span>Submit</span>
                                                    </button>

                                                    <button v-else class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="v-pills-payment_policy" role="tabpanel" aria-labelledby="v-pills-payment_policy">
                                        <div class="card">
                                            <h2>Payment Policy</h2>
                                            <form class="form form-vertical" @submit.prevent="updateSettings">
                                                <textarea type="text"
                                                          v-model="createForm.payment_policy"
                                                      placeholder="Edit trams and conditions"
                                                      rows="5" class="form-control"></textarea>

                                                <div class="col-12 mt-2 d-inline-flex align-item-center">
                                                    <button v-if="!isLoding" type="submit" disabled class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        <div class="spinner-border text-white me-1"  role="status"></div>
                                                        <span>Submit</span>
                                                    </button>

                                                    <button v-else class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="v-pills-payment_method" role="tabpanel" aria-labelledby="v-pills-payment_method">
                                        <div class="card">
                                            <h2>Payment Methods</h2>
                                            <form class="form form-vertical" @submit.prevent="updateSettings">
                                                <textarea type="text"
                                                          v-model="createForm.payment_methods"
                                                      placeholder="Edit trams and conditions"
                                                      rows="5" class="form-control"></textarea>

                                                <div class="col-12 mt-2 d-inline-flex align-item-center">
                                                    <button v-if="!isLoding" type="submit" disabled class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        <div class="spinner-border text-white me-1"  role="status"></div>
                                                        <span>Submit</span>
                                                    </button>

                                                    <button v-else class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="v-pills-quotation_template" role="tabpanel" aria-labelledby="v-pills-quotation_template">
                                        <div class="card">
                                            <h2>Quotaiton Email Template</h2>
                                            <form class="form form-vertical" @submit.prevent="updateSettings">
                                                <textarea type="text"
                                                          v-model="createForm.quotation_template"
                                                      placeholder="Edit trams and conditions"
                                                      rows="5" class="form-control"></textarea>

                                                <div class="col-12 mt-2 d-inline-flex align-item-center">
                                                    <button v-if="!isLoding" type="submit" disabled class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        <div class="spinner-border text-white me-1"  role="status"></div>
                                                        <span>Submit</span>
                                                    </button>

                                                    <button v-else class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="v-pills-invoice_template" role="tabpanel" aria-labelledby="v-pills-invoice_template">
                                        <div class="card">
                                            <h2>Invoice Email Template</h2>
                                            <form class="form form-vertical" @submit.prevent="updateSettings">
                                                <textarea type="text"
                                                          v-model="createForm.invoice_template"
                                                          placeholder="Edit trams and conditions"
                                                          rows="5" class="form-control"></textarea>

                                                <div class="col-12 mt-2 d-inline-flex align-item-center">
                                                    <button v-if="!isLoding" type="submit" disabled class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        <div class="spinner-border text-white me-1"  role="status"></div>
                                                        <span>Submit</span>
                                                    </button>

                                                    <button v-else class="btn btn-primary me-1 waves-effect waves-float waves-light">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import Image from "@/components/ImageUploader.vue";
import Modal from "@/components/Modal.vue";
import Layout from "../../Shared/Layout.vue";
import Swal from 'sweetalert2'
import { usePage, useForm } from '@inertiajs/vue3'
import {computed, ref} from "vue"

const APP_URL = usePage().props.value?.ADMIN_URL;

let props = defineProps({
    option:[],
    countries:"",
    errors:"",
    bSettings:"",
    main_url:String|null,
    updateSmtp:String|null,
    categories:[]|null,
})





let createForm = useForm({
    trams_and_condition:props.bSettings.trams_and_condition ?? '',
    payment_policy:props.bSettings.payment_policy ?? '',
    payment_methods:props.bSettings.payment_methods ?? '',

    quotation_template:props.bSettings.quotation_template ?? '',
    invoice_template:props.bSettings.invoice_template ?? '',

})

const mailSetup = useForm({
    mailDriver        : props.bSettings.mailDriver ?? '',
    mailHost          : props.bSettings.mailHost ?? '',
    mailPort          : props.bSettings.mailPort ?? '',
    username          : props.bSettings.username ?? '',
    password          : props.bSettings.password ?? '',
    fromEmail         : props.bSettings.fromEmail ?? '',
    fromName          : props.bSettings.fromName ?? '',
    encryption        : props.bSettings.encryption ?? '',
})

let isLoding = ref({});
let updateMailSetup = () =>{
    isLoding.value = false
    mailSetup.post(props.updateSmtp, {
        onSuccess: (res)=>{
            console.log(res);
            isLoding.value = true
            Swal.fire({
               icon: "success",
               text: "Business Settings Update Successfully Done.:)",
            });
        },
        onError: (res) =>{
            Swal.fire({
                icon: "error",
                text: "Business Settings Not Update (:",
            });
        }
    });
}

const updateSettings = () =>{
    isLoding.value = false
    createForm.post(props.main_url+"/all-settings", {
        onSuccess: (res)=>{
            isLoding.value = true
            Swal.fire({
                icon: "success",
                text: "Business Settings Update Successfully Done.:)",
            });
        },
        onError: (res) =>{
            Swal.fire({
                icon: "error",
                text: "Business Settings Not Update (:",
            });
        }
    });
}




const smtpMillers = [
        {name:"smtp"}, {name:"sendmail"}, {name:"mailgun"}, {name:"ses"}, {name:"postmark"}, {name:"log"}, {name:"array"}, {name:"failover"}
]





const formattedParentCategories = computed(() => {
    return renderOptionGroup(props.categories);
});
</script>

<style scoped>
.spinner-border{
    --bs-spinner-width: 1.2rem !important;
    --bs-spinner-height: 1.2rem !important;
}
.v-select{
    border: 1px solid #d8d6de !important;
    border-radius: 5px !important;
}
</style>
