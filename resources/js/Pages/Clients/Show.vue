<template>

    <UserProfile
        :user="props.user"
        :image="props.image"
        :showUrl="props.show_url"
        :errors="props.errors"
        :users="props.users"
    />

</template>

<script setup>
import VueLink from '@/components/ViewLink.vue'
import UserProfile from "@/components/Uerprofile/ClientUserProfile.vue";
let props = defineProps({
    user:[]|Object|null,
    users:[]|Object|null,
    image:'',
    show_url:String,
    errors:Object,
})
</script>
