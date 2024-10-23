<template>
    <Head title="Welcome Again"/>

    <div class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static">
        <div class="app-content content ">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="auth-wrapper auth-basic px-2">
                        <div class="auth-inner my-2">
                            <!-- Login basic -->
                            <div class="card mb-0">
                                <div class="card-body">
                                    <a href="/" class="brand-logo">
                                        <img src="../../../../public/creativeTechPark.png" alt="">
                                    </a>

                                    <form class="auth-login-form mt-2" @submit.prevent="submit">
                                        <div class="mb-1">
                                            <label for="login-email" class="form-label">Email</label>
                                            <input type="email" v-model="form.email" class="form-control" id="login-email"
                                                placeholder="john@example.com" aria-describedby="login-email" aria-invalid="true"
                                                tabindex="1" autofocus />
                                                <span v-if="form.errors.email" id="login-email-error" class="error">{{ form.errors.email }}</span>
                                        </div>

                                        <div class="mb-1">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label" for="login-password">Password</label>
<!--                                                <a href="#">-->
<!--                                                    <small>Forgot Password?</small>-->
<!--                                                </a>-->
                                            </div>


                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input :type="passwordFieldType" v-model="form.password" class="form-control form-control-merge"
                                                    id="login-password" tabindex="2"
                                                    placeholder="******"
                                                    aria-describedby="login-password" />

                                                <span class="input-group-text">
                                                    <vue-feather class="cursor-pointer" :type="passwordToggleIcon"
                                                        @click="togglePasswordVisibility" />
                                                </span>
                                                <span v-if="form.errors.password" id="login-password-error" class="error">{{ form.errors.password }}</span>
                                            </div>
                                        </div>


                                        <div class="mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input" v-model="form.remember" type="checkbox" id="remember-me"
                                                    tabindex="3" />
                                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary w-100" tabindex="4" type="submit" :disabled="form.processing">Sign in</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /Login basic -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    layout: null,
};
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue'

const passwordFieldType = ref('password')
let form = useForm({
    email: '',
    password: '',
    remember:true,
});
const togglePasswordVisibility = () => {
    passwordFieldType.value = passwordFieldType.value === "password" ? "text" : "password"
}
const passwordToggleIcon = computed(() =>{
    return passwordFieldType.value === 'password' ? 'eye' : 'eye-off'
})

let submit = () => {
    form.post('/admin/login',{
        onSuccess:(res)=>{
            $toast.info("Welcome Back...", {
                position: 'top-right'
            })
        },
        onError: (res)=>{
            $toast.error("Connection Error!")
        }
    });
};
</script>

<style lang="scss">
@import '@@/sass/base/pages/authentication.scss';
</style>
