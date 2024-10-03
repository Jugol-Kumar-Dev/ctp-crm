<template>
    <Head title="Edit Platform"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Services Information's </h4>
                                    <a :href="props.main_url" class="dt-button add-new btn btn-primary">
                                        Go To Platforms
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">Add New Platform</h2>

                                <div class="mb-1">
                                    <label class="form-label">Platform Name</label>
                                    <input class="form-control mb-1"
                                           v-model="formData.platform"
                                           type="text" placeholder="e.g Lravel, Vue js, React Js, Javascript etc"/>
                                    <span v-if="errors.platform" class="error text-sm text-danger">{{ errors.platform }}</span>
                                </div>
                                <h2 class="card-title">Platform Featured's</h2>
                                <div class="d-flex align-items-center mb-1 ms-5" v-for="(variant, index) in formData.features">

                                        <input type="text"
                                               class="form-control mb-1 rounded-start ms-1"
                                               placeholder="e.g Featured Name" v-model="formData.features[index].name" >
                                    <input type="number"
                                           class="form-control mb-1 rounded-start ms-1"
                                           placeholder="e.g Featured Price" v-model="formData.features[index].price" >

                                    <button
                                        v-if="index === formData.features.length - 1 "
                                        class="btn btn-primary btn-sm float-end mb-1 ms-1"
                                        type="button"
                                        @click="addRow()">
                                        <vue-feather type="plus" size="20"/>
                                    </button>
                                    <button
                                        v-else
                                        class="btn btn-danger btn-sm float-end mb-1 ms-1"
                                        @click="deleteRow(index)"
                                        data-repeater-delete
                                        type="button">
                                        <vue-feather type="trash" size="20"/>
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap mb-0">
                                    <button @click="savePlatform" class="btn btn-primary me-1">Save</button>
                                    <a :href="props.main_url" class="btn btn-outline-secondary">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import {useForm} from "@inertiajs/vue3";
    import {ref} from "vue";
    import {router} from "@inertiajs/vue3";

    const props = defineProps({
        platform:Object,
        update_url:String,
        errors:Object,
    })

    const formData = useForm({
        platform:props.platform.name,
        features: JSON.parse(props.platform.featureds),
    });

    const addRow = () => {
        formData.features.push({
            name:null,
            price:null
        })
    }

    const deleteRow = (index) => formData.features.splice(index, 1)

    const processing= ref(false)
    const savePlatform = () =>{
        router.put(props.update_url,formData,{
            preserveState: true,
            onStart: () =>{ processing.value = true},
            onFinish: () => {processing.value = false},
            onSuccess: ()=> {
                formData.reset()
            },
        })
    }


</script>

<style scoped>

</style>
