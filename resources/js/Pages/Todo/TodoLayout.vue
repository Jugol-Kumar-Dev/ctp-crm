<template>
    <Head title="Todo Index"/>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <div class="row match-height ">
                    <div class="col-md-3 me-0 pe-0">
                        <Sidebar :users="users"/>
                    </div>
                    <div class="col-md-9 ms-0 ps-0  bg-white" id="mainSection">
                        <slot/>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!--
    <div class="modal modal-slide-in fade" id="showTodoModal" aria-hidden="true" v-vb-is:modal>
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Show Todo</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="d-flex flex-column mb-2">
                        <h1>{{ showTodoData.title }}</h1>
                        <small>{{ showTodoData.about_todo }}</small>
                        <span class="text-primary mt-1">{{ moment(showTodoData.date).format('MMMM, DD') }}</span>
                    </div>


                    <a v-if="showTodoData.downloadUrl" :href="showTodoData.downloadUrl"  v-c-tooltip="'View Or Download This File.'">
                        <img :src="showTodoData.downloadUrl" alt="" class="mb-1" style="max-width: 100%;">
                        {{ $page.props.auth.ADMIN_URL+''+showTodoData.downloadUrl }}
                    </a>
&lt;!&ndash;                    <iframe :src="showTodoData.downloadUrl" scrolling="no" style="overflow:hidden"></iframe>&ndash;&gt;
                </div>
            </div>
        </div>
    </div>
-->


</template>
<script setup>
import Sidebar from  './Sidebar.vue'

const props = defineProps({
    users:[]
})


</script>
<style>
#chat{
    min-height: calc(100vh - 20rem);
    overflow-y: scroll;
}
</style>
