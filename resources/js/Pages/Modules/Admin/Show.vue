<template>
    <Head title="Show User"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card mb-4">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="text-center">

                                        <div class="d-flex align-items-center flex-column">
                                            <input type="file" accept="image/*" id="imageInput" @change="uploadImage" class="d-none">
                                            <div class="avatar cursor-pointer">
                                                <label for="imageInput">
                                                    <img class="cursor-pointer" v-if="profileImage !== null" :src="profileImage" height="150" width="150"  alt="">
                                                    <img class="cursor-pointer" v-else :src="props.user.photo" height="150" width="150" alt="">
                                                </label>
                                            </div>
                                            <button class="btn btn-primary btn-sm my-1" v-if="profileImage !== null" @click="updateProfileImage">upload</button>
                                        </div>



                                        <h4 class="mb-0">{{ props.user?.name ?? '---'}}</h4>

                                        <span class="badge bg-light-info me-25 my-1" v-for="role in props.user.roles" v-text="role.name"></span>
                                        <p class="mb-0">{{ props.user?.email ?? '' }}</p>
                                        <p class="mb-0">{{ props.user?.phone ?? '' }}</p>
                                        <p class="mb-0">{{ props.user?.address }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form @submit.prevent="updatePassword">
                                        <div class="mb-1">
                                            <label>Name</label>
                                            <input class="form-control" type="text" v-model="formData.name" placeholder="Change Name">
                                            <span class="text-danger" v-if="props.errors.name" v-text="props.errors.name"></span>
                                        </div>

                                        <div class="mb-1">
                                            <label>Email</label>
                                            <input class="form-control" type="text" v-model="formData.email" placeholder="Change Email">
                                            <span class="text-danger" v-if="props.errors.email" v-text="props.errors.email"></span>
                                        </div>

                                        <label>password</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input :type="passwordFieldType" v-model="formData.password" class="form-control form-control-merge"
                                                   id="login-password" tabindex="2"
                                                   placeholder="******"
                                                   aria-describedby="login-password" />


                                            <span class="input-group-text">
                                                    <vue-feather class="cursor-pointer" :type="passwordToggleIcon"
                                                                 @click="togglePasswordVisibility" />
                                                </span>
                                        </div>
                                        <p class="text-danger" v-if="props.errors.password" v-text="props.errors.password"></p>


                                        <button type="submit" class="btn btn-sm btn-primary mt-1">Update Credintials</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
    import VueLink from '../../../components/ViewLink'
    import UserProfile from "../../../components/UserProfile";
    import {useForm, usePage} from "@inertiajs/inertia-vue3";
    import {computed, ref} from "vue";
    import {Inertia} from "@inertiajs/inertia";
    let props = defineProps({
        user:[],
        errors:Object,
    })


    const formData = useForm({
        name:props.user.name,
        email:props.user.email,
        password:null,
    })

    const updatePassword =()=>{
        formData.post('/admin/user/update-pass/'+props.user.id,{
            preserveState:true,
            onSuccess:()=>$toast.success('Credentials Updated...')
        })
    }


    const passwordFieldType = ref('password')

    const togglePasswordVisibility = () => {
        passwordFieldType.value = passwordFieldType.value === "password" ? "text" : "password"
    }
    const passwordToggleIcon = computed(() =>{
        return passwordFieldType.value === 'password' ? 'eye' : 'eye-off'
    })

    const profileImage = ref(null);
    const uploadImageData = ref(null);
    const uploadImage = (event) =>{
        profileImage.value=URL.createObjectURL(event.target.files[0])
        uploadImageData.value = event.target.files[0];
    }

    const updateProfileImage = () =>{

        Inertia.post('/admin/user/update-profile-image', {image:uploadImageData.value, userId:props.user.id},{
            preserveState: true,
            replace: true,
            onSuccess: page => {
                uploadImageData.value = {};
                $toast.success('Profile Picture Updated...')
            },
            onError: errors => {
                $toast.error("Server Error 500")
            }
        })
    }



</script>
