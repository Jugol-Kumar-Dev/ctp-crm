<template>
    <div class="card">
        <div class="card-body d-flex align-items-center">
            <vue-feather type="info"/>
            <p class="text-info ms-2 p-0">This Module Coming soon....</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2>Project Progressment</h2>
                    <div class="row">
                        <ProgressChart class="col" :progress="50" :height="200"/>
                        <ProgressChart class="col" :progress="75" :height="200"/>
                        <ProgressChart class="col" :progress="45" :height="200"/>
                        <ProgressChart class="col" :progress="80" :height="200"/>
                        <ProgressChart class="col" :progress="95" :height="200"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import ProgressChart from "@/components/ProgressChart.vue";


</script>

<style scoped>

</style>
