<template>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ cardTitle }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-1" v-for="(option , index) in items" :key="index">
                    <ServiceItem :data="{name:option.name, price:option.price, id:option.id}" @itemData="itemData"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import ServiceItem from '@/components/ServiceItem.vue'
    let props = defineProps({
        cardTitle:String,
        items:Object,
    })

    let itemData = (data) =>{

    }


</script>

<style scoped>

</style>
