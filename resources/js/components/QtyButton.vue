<template>
    <div class="input-group input-group-lg bootstrap-touchspin">
        <span
            class="input-group-btn bootstrap-touchspin-injected">
            <button @click="decrement()"
                    class="btn bg-light-primary bootstrap-touchspin-down"
                    type="button">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round"
                     class="feather feather-minus">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </span>

        <input type="number" class="touchspin form-control" v-model="formData.qty" @input="udpateModal">

        <span class="input-group-btn bootstrap-touchspin-injected">
            <button @click="increment()"
                    class="btn bg-light-primary bootstrap-touchspin-up"
                    type="button">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round"
                     class="feather feather-plus">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </span>
    </div>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";

    let formData = useForm({
        qty:1,
    });

    let increment = () =>{
        formData.qty++
    }
    let decrement = () =>{
        formData.qty > 1 ? formData.qty-- : alert("minimum qty is 1"), formData.qty = 1
    }
    let emit = defineEmits(['getqty']);
    let updateModal = (value) =>{
        emit('getqty', value)
    }


</script>

<style scoped>

</style>
