<template>
    <Head title="Edit Package"/>

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
                                    <h4 class="card-title">Create Package</h4>
                                    <a :href="$page.props.auth.ADMIN_URL+'/package'" class="dt-button add-new btn btn-primary">
                                        Manage Package
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row match-height">
                            <div class="col-md-6">

                                <div class="card">

                                    <div class="card-header">
                                        <ul>
                                            <li class="text-danger" v-for="error in props.errors">{{ error }}</li>
                                        </ul>
                                    </div>


                                    <div class="card-body">
                                        <h2 class="card-title">Package Information </h2>
                                        <div class="mb-1">
                                            <label class="form-label">Name</label>
                                            <input v-model="formData.name" type="text" class="form-control" placeholder="e.g Package name">
                                        </div>
                                        <div class="mb-1">
                                            <label class="form-label">Price</label>
                                            <input v-model="formData.price" type="number" class="form-control" placeholder="e.g Package name">
                                        </div>
                                        <div class="">
                                            <label class="form-label">Details</label>
                                            <textarea v-model="formData.descriptions" class="form-control" rows="5" placeholder="e.g What you think about this package"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="card-title">Platform Information </h2>
                                        <div class="input-group">
                                        <v-select
                                            :options="props.platforms"
                                            style="width:100%"
                                            label="name"
                                            class="form-control select-padding"
                                            v-model="formData.platformId"
                                            :reduce="item => item.id"
                                            @update:modelValue="loadFeatures"
                                            placeholder="e.g Select Service">
                                                <template v-slot:option="option">
                                                    <li class="d-flex align-items-start py-1">
                                                        <div class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="me-1 d-flex flex-column" >
                                                                <h6>{{ option.name }}</h6>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </template>
                                        </v-select>
                                    </div>
                                        <div class="mt-1 ms-4 showContentQuotation">
                                            <ul>
                                                <li class="list-group-item" v-for="fea in features" :key="fea.name">
                                                    <vue-feather type="check-circle" size="12"/>
                                                    {{ fea.name }} ({{ fea.price }} Tk)
                                                    <vue-feather class="cursor-pointer" @click="copy(fea)" type="copy" size="15"/>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <button type="submit" @click="updatePack" class="btn btn-primary me-2">Update</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
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
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import axios from "axios";


    const props = defineProps({
        platforms:Array|null,
        update_url:String|null,
        errors:[],
        package:Object|null,
    })

    const formData = useForm({
        name:props.package.name,
        price:props.package.price,
        descriptions:props.package.descriptions,
        platformId:props.package.platform_id
    })

    const processing = ref(false);
    const updatePack = ()=>{
        formData.put(props.update_url,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> {
                formData.reset()
                $toast.success("Package Saved.")
            },
        })
    }


    const features = ref([])
    const loadFeatures =(event)=> features.value = props.platforms.filter(item => item.id === event)[0].featureds;
    const copy =(feature)=>{
        navigator.clipboard.writeText(feature.name)
        $toast.success("Feature Coppid...")
    }

</script>

<style lang="scss">

</style>
