<template>
<!-- User Create:START -->
    <Head title="Create User"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Create User</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <Link href="/test">Home</Link>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Create User
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button
                                class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <vue-feather type="settings" />
                            </button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html"><i class="me-1" data-feather="message-square"></i><span class="align-middle">Chat</span></a><a class="dropdown-item" href="app-email.html"><i class="me-1" data-feather="mail"></i><span class="align-middle">Email</span></a><a class="dropdown-item" href="app-calendar.html"><i class="me-1" data-feather="calendar"></i><span class="align-middle">Calendar</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <form @submit.prevent="submit">
                <div class="content-body">
                    <section id="basic-input">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Basic Information</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first_name">Full Name <span class="text-danger">*</span></label>
                                                    <input
                                                        v-model="form.full_name"
                                                        type="text"
                                                        class="form-control"
                                                        id="first_name"
                                                        placeholder="First Name" />
                                                </div>
                                            </div>


                                            <div class="col-xl-4 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                                    <input
                                                        v-model="form.email"
                                                        type="email"
                                                        id="email"
                                                        class="form-control"
                                                        placeholder="eg.example@testmail.com" />
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 col-12">
                                                <div class="d-flex flex-column">
                                                    <label class="form-check-label mb-50" for="is_active">Is Active ?</label>
                                                    <div class="form-check form-check-success form-switch">
                                                        <input
                                                            v-model="form.is_active"
                                                            type="checkbox"
                                                            checked
                                                            class="form-check-input"
                                                            id="is_active" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Authentication information</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="username">User Name <span class="text-danger">*</span></label>
                                                    <input
                                                        v-model="form.username"
                                                        type="text"
                                                        class="form-control"
                                                        id="username"
                                                        placeholder="User Name" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                    <input
                                                        v-model="form.password"
                                                        type="password"
                                                        class="form-control"
                                                        id="password"
                                                        placeholder="Password"/>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                    <input
                                                        v-model="form.password_confirmation"
                                                        type="password"
                                                        class="form-control"
                                                        id="conform_password"
                                                        placeholder="Password"/>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6 col-12">
                                                <div class="d-flex flex-column">
                                                    <label class="form-check-label mb-50" for="allow_login">Allow login ?</label>
                                                    <div class="form-check form-check-success form-switch">
                                                        <input
                                                            v-model="form.allow_login"
                                                            type="checkbox"
                                                            checked="true"
                                                            class="form-check-input"
                                                            id="allow_login" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--
                                    <section >
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title">More Information's</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-1">
                                                                        <label class="form-label" for="fp-default">Default</label>
                                                                        <input type="text" id="fp-default" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                                                    </div>
                                                                    <div class="col-md-6 mb-1">
                                                                        <label class="form-label" for="fp-time">Time picker</label>
                                                                        <input type="text" id="fp-time" class="form-control flatpickr-time text-start" placeholder="HH:MM" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="username">User Name <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="username" placeholder="User Name" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="username">User Name <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="username" placeholder="User Name" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="username">User Name <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="username" placeholder="User Name" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="username">User Name <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="username" placeholder="User Name" />
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-3 col-md-6 col-12">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="conform_password">Conform Password <span class="text-danger">*</span></label>
                                                                    <input type="password" class="form-control" id="conform_password" placeholder="Password"/>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>-->
                </div>

                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-danger me-1">Cancle</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

            </form>
        </div>
    </div>

<!-- User Create:END -->
</template>
<script>

</script>
<script setup>
    import {useForm} from "@inertiajs/inertia-vue3";
    import {Inertia} from "@inertiajs/inertia";
    import {defineProps} from "@vue/runtime-core";


    let form = useForm({
        full_name:"",
        is_active:Boolean,

        username:"",
        password:"",
        password_confirmation:"",
        allow_login:true,

    });

    let submit = () =>{
        Inertia.post("/users", form,{
            onSuccess:  () =>{
                alert('Data Save Successfully done.');
            },
            onError: () =>{
                alert('Have Some Errors');
            }
        });
    }

    defineProps({
       'user' : Object,
       'notification' : Array,
    });


</script>


<style>
</style>
