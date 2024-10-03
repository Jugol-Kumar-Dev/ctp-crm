<template>
    <div class="row match-height">
        <div class="col-xl-3 col-lg-6 col-12" v-for="(developer, index) in info.users" :key="developer.id">
            <div class="card mb-4">
                <!-- card body  -->
                <div class="card-body py-1 px-1">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-lg">
                            <img :src="developer.photo" alt="" class="rounded-circle">
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-0 fs-5 fw-bold">
                                <a href="#" class="text-black">{{ developer.name }}</a>
                            </h4>
                            <p class="mb-0 text-muted fs-6" v-for="role in developer.roles">{{ role.name }}</p>
                        </div>
                    </div>
                    <div class="mt-1 lh-1 d-flex align-items-center">
                        <a :href="`tel:${developer.phone}`" class="me-1 text-muted texttooltip" data-template="phone">
                            <vue-feather type="phone-call" size="15"/>
                        </a>
                        <a href="#" class="me-1 text-muted texttooltip" data-template="video" v-c-tooltip="'Video Call Coming Soon'">
                            <vue-feather type="video" size="15"/>
                        </a>
                        <CDropdown>
                            <CDropdownToggle>
                                <vue-feather type="more-horizontal" size="15"/>
                            </CDropdownToggle>
                            <CDropdownMenu>
                                <CDropdownItem >
                                    <vue-feather type="eye" size="15"/>
                                    <span class="ms-1">Show</span>
                                </CDropdownItem>
                                <CDropdownItem @click="removeUser(`${props.urls.remove_user}?project_id=${props.info.id}&user_id=${developer.id}`)">
                                    <vue-feather type="trash-2" size="15"/>
                                    <span class="ms-1">Remove</span>
                                </CDropdownItem>
                            </CDropdownMenu>
                        </CDropdown>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {useAction} from "@/composables/useAction.js";
    import {router} from "@inertiajs/vue3";
    import Swal from "sweetalert2";
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'

    const props = defineProps({
        info:{
            type:Object,
            required:true,
        },
        urls:{
            type:Array,
        }
    })


    const removeUser = (url)=> {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2e0080',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                   router.get(url, {
                       preserveState: true, replace: true,
                       onSuccess: ()=> { $toast.success('Remove User Form This Project...') },
                       onError: ()=> { $toast.error('Have An Error. Please Try Again.') },
                   })
                console.log("call here");
            }
        })
    }

</script>

<style scoped>


</style>
