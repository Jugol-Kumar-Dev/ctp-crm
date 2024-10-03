<template>
    <nav
        class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item">
                        <a class="nav-link menu-toggle" @click="toggleVerticalMenuActive" href="#">
                            <vue-feather type="menu" />
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav bookmark-icons">
                    <li class="nav-item d-none d-lg-block" v-c-tooltip="'Toto List'">
                        <a class="nav-link" href="/admin/todos">
                            <vue-feather type="check-square" />
                        </a>
                    </li>
                    <li class="nav-item d-none d-lg-block" v-if="this.$page.props.auth.user.role.includes('Administrator')" v-c-tooltip="'Today Working Summery'">
                        <a class="nav-link" href="/admin/admin-show-summery">
                            <vue-feather type="list" />
                        </a>
                    </li>

                    <li class="nav-item d-none d-lg-block" v-else v-c-tooltip="'Today Working Summery'">
                        <a class="nav-link" href="/admin/work-summery">
                            <vue-feather type="list" />
                        </a>
                    </li>
                </ul>
<!--                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><vue-feather type="star" /></a>
                        <div class="bookmark-input search-input">
                            <div class="bookmark-input-icon"><vue-feather type="search" /></div>
                            <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0"
                                data-search="search">
                            <ul class="search-list search-list-bookmark"></ul>
                        </div>
                    </li>
                </ul>-->
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">



                <li class="nav-item dropdown dropdown-notification me-25">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown" @click="openNotefication">
                        <vue-feather type="bell"/>
                        <span class="badge rounded-pill bg-danger badge-up" v-if="$page.props.auth.user.notifications.length" v-text="$page.props.auth.user.notifications.length"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end" :class="{'d-block':isopen}">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">
                                    <span class="dropdown-notification">Notifications</span>
                                </h4>
                                <div class="badge rounded-pill badge-light-dark cursor-pointer" @click="openNotefication">X Close</div>
                            </div>
                        </li>

                        <li class="scrollable-container media-list overflow-y-scroll notification-scroll">
                            <div class="d-flex cursor-pointer " v-for="noti in $page.props.auth.user.notifications" @click="showItem(noti.data.notefication?.id, noti.id)">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-danger">
                                            <div class="avatar-content">
                                                <img :src="noti.data.notefication?.user?.photo" alt="" width="32" height="32">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading">
                                            <span class="fw-bolder text-capitalize">{{  noti.data.notefication?.user?.name  }}</span>
                                            <span style="font-size: 10px; margin-left: 5px;">{{ noti.data.notefication.type }}</span>
                                        </p>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="notification-text">
                                                <span>{{ noti.data.notefication?.title }}</span>
                                                <span v-if="noti.data.notefication?.file">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 15px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                            </span>
                                                <span style="margin-left:5px;">{{ noti.data.notefication?.about_todo }}</span>
                                            </div>

                                            <span class="unread-badge" v-if="noti.read_at === null"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
<!--                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all notifications</a></li>-->
                    </ul>
                </li>


                <dark-toggle />
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" :href="`/admin/users/${this.$page.props.auth.user.id}`">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name fw-bolder">{{ this.$page.props.auth.user.username }}</span>
                            <span class="user-status">{{ this.$page.props.auth.user.role[0]  }}</span>
                        </div>
                        <span class="avatar">
                            <img class="round" :src="this.$page.props.auth.user.photo" alt="avatar" height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="page-profile.html">
                            <vue-feather type="user" /> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="page-account-settings-account.html">
                            <vue-feather type="settings" /> Settings
                        </a>
                        <a class="dropdown-item" href="auth-login-cover.html">
                            <vue-feather type="power" /> Logout
                        </a>
                    </div>
                </li>
<!--                <li class="nav-item nav-search">
                    Logout
                    <Link preserve-scroll method="post" as="button" class="nav-link nav-link-search" href="/admin/logout">
                        <vue-feather type="power" />
                    </Link>
                </li>-->
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a
                class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75"
                        data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>


    <div class="modal modal-slide-in fade" id="showTodo" aria-hidden="true" v-vb-is:modal>
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
                        <h1>{{ showData?.title }}</h1>
                        <small>{{ showData?.about_todo }}</small>
                        <span class="text-primary mt-1">{{ moment(showData?.date).format('MMMM, DD') }}</span>
                    </div>


                    <a v-if="showData?.downloadUrl" :href="showData?.downloadUrl"  v-c-tooltip="'View Or Download This File.'">
                        <img :src="showData?.downloadUrl" alt="" class="mb-1" style="max-width: 100%;">
                        {{ $page.props.auth?.ADMIN_URL+''+showData?.downloadUrl }}
                    </a>
                    <!--                    <iframe :src="showData.downloadUrl" scrolling="no" style="overflow:hidden"></iframe>-->
                </div>
            </div>
        </div>
    </div>



</template>

<script setup>
import DarkToggle from "@/components/DarkToggle.vue";
import {ref} from "vue";
import moment from "moment";
import { router } from '@inertiajs/vue3'

const props = defineProps({
  toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
})


const isopen = ref(false);
const showData = ref();
const openNotefication = () => {
    // axios.get('/admin/read-all-notification').then((res) => console.log(res))
    isopen.value = !isopen.value
};
const showItem = (id, noti_id)=>{
    router.get
    router.get(`/admin/todos/${id}?show_data=true&notification_id=${noti_id}`);
}


</script>
<style>
.unread-badge{
    width: 13px;
    height: 12px;
    background: #7367f0;
    border-radius: 100%;
    display: block;
    position: absolute;
    right: 24px;
    top: auto;
    bottom: auto;
}
</style>
