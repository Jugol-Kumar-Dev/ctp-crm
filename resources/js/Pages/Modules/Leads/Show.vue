<template>
    <Head title="Show Lead"/>

    <UserProfile :user="props.user" :image="props.image"/>

</template>

<script setup>
import VueLink from '../../../components/ViewLink'
import UserProfile from "../../../components/UserProfile";
let props = defineProps({
    user:[],
    image:''
})
</script>
