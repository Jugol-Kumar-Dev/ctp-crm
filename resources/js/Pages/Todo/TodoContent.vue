<template>
    <div class="avatar avatar-border me-1">
        <img :src="props.todo?.user?.photo" alt="user_avatar" height="42" width="42">
        <span class="avatar-status-online"></span>
    </div>
    <div class="message bg-gradient bg-white">
        <div class="todo-content overflow-hidden">
            <div class="d-flex flex-column gap-1"  v-if="isImage(props.todo?.downloadUrl) === 'image' && showBigImage">
                <img :src="props.todo.downloadUrl" class="todo-file" alt="user_avatar">
                <div class="d-flex flex-column gap-1">
                    <a class="btn btn-sm btn-primary"

                       :href="`/admin/project/download-attachment?path=${props.todo?.file}`" style="width:40%" >Download File</a>
                </div>
            </div>

            <div class="d-flex gap-1" v-if="isImage(props.todo?.downloadUrl) === 'image' && !showBigImage">
                <img  :src="props.todo?.downloadUrl" width="80" alt="user_avatar">
                <div class="d-flex flex-column gap-1">
                    <span class="d-block" style="width: 70%">{{$page.props.auth.ADMIN_URL+''+props.todo.downloadUrl}}</span>
                    <a class="btn btn-sm btn-primary"
                       :href="`/admin/project/download-attachment?path=${props.todo?.file}`" style="width:40%" >Download File</a>
                </div>
            </div>

            <div class="d-flex gap-1" v-if="isImage(props.todo.downloadUrl) === 'docx'">
                <img  src="./images/word.svg" width="50" alt="user_avatar">
                <div class="d-flex flex-column gap-1">
                    <span class="d-block" style="width: 70%">{{$page.props.auth?.ADMIN_URL+''+props.todo.downloadUrl}}</span>
                    <a class="btn btn-sm btn-primary"
                       :href="`/admin/project/download-attachment?path=${props.todo.file}`" style="width:40%" >Download File</a>
                </div>
            </div>
            <div class="d-flex gap-1" v-if="isImage(props.todo.downloadUrl) === 'pdf'">
                <img  src="/images/pdf.svg" width="50" alt="user_avatar">
                <div class="d-flex flex-column gap-1">
                    <span class="d-block" style="width: 70%">{{$page.props.auth.ADMIN_URL+''+props.todo.downloadUrl}}</span>
                    <a class="btn btn-sm btn-primary"
                       :href="`/admin/project/download-attachment?path=${props.todo.file}`" style="width:40%" >Download File</a>
                </div>
            </div>
            <div class="d-flex gap-1" v-if="isImage(props.todo.downloadUrl) === 'xlsx'">
                <img  src="/images/excel.svg" width="50" alt="user_avatar">
                <div class="d-flex flex-column gap-1">
                    <span class="d-block" style="width: 70%">{{$page.props.auth.ADMIN_URL+''+props.todo.downloadUrl}}</span>
                    <a class="btn btn-sm btn-primary"
                       :href="`/admin/project/download-attachment?path=${props.todo.file}`" style="width:40%" >Download File</a>
                </div>
            </div>
            <div class="d-flex gap-1" v-if="isImage(props.todo.downloadUrl) === 'other'">
                <img  src="/images/vscode.svg" width="50" alt="user_avatar">
                <div class="d-flex flex-column gap-1">
                    <span class="d-block" style="width: 70%">{{$page.props.auth.ADMIN_URL+''+props.todo.downloadUrl}}</span>
                    <a class="btn btn-sm btn-primary"
                       :href="`/admin/project/download-attachment?path=${props.todo.file}`" style="width:40%" >Download File</a>
                </div>
            </div>
            <p v-if="props.todo.about_todo">{{ props.todo.about_todo }}</p>
        </div>
        <div class="info">
            <p class="name">~{{ props.todo?.user?.name }}</p>
            <p class="ms-2 time">{{moment(props.todo.created_at).calendar()  }}</p>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";
import {useAction} from "../../composables/useAction";
import {ref} from 'vue'
const {chatStyle} = useAction();
const props = defineProps({
    todo:{
        type:Object,
        required:true,
    }
})
const showBigImage = ref(false)


const isImage =(url)=> {
    if (url){
        const fileExtension = url.split(".").pop().toLowerCase();
        if(fileExtension === "jpg" || fileExtension === "jpeg" || fileExtension === "png" || fileExtension === "gif" ||  fileExtension === "svg"){
            return "image";
        }else if(fileExtension === "docx"){
            return "docx"
        }else if(fileExtension === "pttx"){
            return 'pttx'
        }else if(fileExtension === "xlsx"){
            return 'xlsx'
        }else if(fileExtension === "pdf"){
            return 'pdf'
        }else{
            return 'other';
        }
    }
}

</script>
