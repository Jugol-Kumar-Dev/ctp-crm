<template>
    <span>{{ data.name }} <strong>({{ data.price }} Tk)</strong></span>
    <div class="border-1 border-light rounded-3 p-25">
        <div class="input-group border-0">
            <div class="input-group-text border-0">
                <div class="form-check">
                    <input class="form-check-input" @change="check($event)" v-model="item_id" :value="data.id" type="checkbox"
                           id="inputCheckbox">
                </div>
            </div>

            <QtyButton/>

            <input type="number" class="form-control border-0" @input="modalValue" placeholder="Discount">
        </div>
    </div>
</template>

<script setup>
    import QuantityButton from "@/components/QuantityButton.vue";
    import QtyButton from "@/components/QtyButton.vue";
    let props = defineProps({
        discount:String,

        data:{
            name:String,
            price:String,
            id:String,
            discount:null,
        },
    })

    const emit = defineEmits(['update:modelValue'])

    const updateValue = (event) => {
        emit('update:modelValue', event.target.value)
    }


</script>

<script>
    export default {

        methods:{
            check (e) {
                this.$nextTick(() => {
                    console.log(e.target.value)
                })
            }
        }


    }

</script>



<style scoped>

</style>
