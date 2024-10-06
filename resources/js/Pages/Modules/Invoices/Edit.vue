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
                                    <h4 class="card-title">Edit Invoice</h4>
                                    <Link href="/admin/invoices" class="dt-button add-new btn btn-primary">Manage
                                        Invoice
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-11 mx-auto">
                            <form @submit.prevent="updateQutation">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="modal-body">
                                            <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Client :
                                                        <Required/>
                                                    </label>
                                                    <div class="">
                                                        <v-select v-model="formData.client_id" :options="clients"
                                                                  :reduce="client => client.id" label="name"
                                                                  placeholder="Select Client"></v-select>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <label>Subject : </label>
                                                    <div class="">
                                                        <input type="text" placeholder="Subjects" v-model="formData.subject"
                                                               class="form-control">
                                                        <span class="error text-sm text-danger"></span>
                                                        <InputFieldError :errors="errors.subject"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Date :
                                                        <Required/>
                                                    </label>
                                                    <div class="">
                                                        <Datepicker v-model="formData.date"
                                                                    :monthChangeOnScroll="false"
                                                                    placeholder="Select Date"
                                                                    autoApply></Datepicker>
                                                        <InputFieldError :errors="errors.date"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card">
                                    <div class="card-body">
                                        <div class="modal-body">
                                            <div class="row mb-1">
                                                <div class="col-md">
                                                    <label>Payment Policy :
<!--                                                        <Required/>-->
                                                    </label>
                                                    <div class="">
                                                        <TextEditor v-model="formData.payment_policy"></TextEditor>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <label>Terms Of Service : </label>
                                                    <div class="">
                                                        <TextEditor v-model="formData.trams_and_condition"></TextEditor>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6" data-repeater-item
                                         v-for="(item, index) in formData.quatations">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Item: {{ index + 1 }} </h4>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-12">
                                                        <div class="mb-1">
                                                            <TextEditor v-model="formData.quatations[index].item_name"
                                                                        placeholder="Item Details"/>
                                                        </div>
                                                        <div class="input-group border-0 d-flex">

<!--                                                            <QtyButton/>-->

                                                            <input type="number" class="form-control rounded-start"
                                                                   placeholder="Price" v-model="formData.quatations[index].price">

                                                            <input type="number" class="form-control"
                                                                   placeholder="Discount" v-model="formData.quatations[index].discount" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <button
                                                    v-if="index === formData.quatations.length - 1"
                                                    class="btn btn-primary btn-sm float-end mt-25"
                                                    type="button"
                                                    name="button"
                                                    @click="addRow">
                                                    <vue-feather type="plus"/>
                                                </button>
                                                <button
                                                    class="btn btn-danger btn-sm float-end mt-25 me-1"
                                                    @click="deleteRow(index)"
                                                    data-repeater-delete
                                                    type="button">
                                                    <vue-feather type="trash"/>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-item-center justify-content-between">
                                            <div class="d-flex flex-column">
                                                <label class="form-check-label mb-50"
                                                       for="customSwitch10">Primary</label>
                                                <div class="form-check form-switch form-check-primary">
                                                    <input type="checkbox" class="form-check-input"
                                                           v-model="formData.status" id="customSwitch10" checked/>
                                                    <label class="form-check-label" for="customSwitch10">
                                                        <span class="switch-icon-left"><i
                                                            data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div>
                                                <button type="submit"
                                                        class="btn btn-primary waves-effect waves-float waves-light me-2">
                                                    Submit
                                                </button>
                                                <button type="reset" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close">Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!--/ Advanced Search -->
                <!--/ Multilingual -->
            </div>
        </div>
    </div>


</template>


<script setup>
    import Pagination from "@/components/Pagination.vue"
    import Icon from '@/components/Icon.vue'
    import Modal from '@/components/Modal.vue'
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {router} from "@inertiajs/vue3";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/vue3";
        import TextEditor from "@/components/TextEditor.vue";
    import TextArea from "@/components/Textarea.vue";
    import QuantityButton from "@/components/QuantityButton.vue";
    import ServiceItem from "@/components/ServiceItem.vue";
    import ServiceCard from "@/components/ServiceCard.vue";
    import InputFieldError from "@/components/InputFieldError.vue";
    import QtyButton from "@/components/QtyButton.vue";

    let props = defineProps({
        info    : Object,
        clients : Object,
        errors  : Object,

    })

    let formData = useForm({
        status: props.info.invoice.status === 1 ? true : false,
        subject: props.info.invoice.subject,
        client_id: props.info.invoice.client_id,
        date: props.info.invoice.date,
        payment_policy:props.info.invoice.privicy_and_policy,
        trams_and_condition:props.info.invoice.trams_and_condition,

        quatations: [
            {
                id:'',
                item_name: '',
                price: '',
                discount: '',
                quantity: ''
            }
        ],

    });

    props.info.invoice_item.forEach(function(item, index){
        formData.quatations[index] = item;
    });



    let updateQutation = () => {
        router.patch(props.info.update_url, formData,{
           onSuccess: () =>{
               formData.reset()
               Swal.fire(
                   'Saved!',
                   'Your file has been Saved.',
                   'success'
               )
           }
        });
    }

    let addRow = () => {
        formData.quatations.push({
            item_name: '',
            price: '',
            quantity: '',
            discount: '',
        })
    }

    let deleteRow = (index) => {
        formData.quatations.splice(index, 1)
    }


</script>


<style lang="scss">
    /*@import "../../../../sass/base/plugins/tables/datatables";*/
</style>
