<template>
    <div class="main-menu menu-fixed menu-accordion menu-shadow"
    :class="[
      { 'expanded': !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered) },
      themeSkin === 'light'|| themeSkin === 'bordered' ? 'menu-light' : 'menu-dark'
    ]"
    @mouseenter="updateMouseHovered(true)"
    @mouseleave="updateMouseHovered(false)"
    >
        <div class="navbar-header expanded">
            <slot
                name="header"
                :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :toggleCollapsed="toggleCollapsed"
                :collapseTogglerIcon="collapseTogglerIcon"
            >
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item me-auto">
                        <Link preserve-scroll class="navbar-brand"
                            href="/">
                            <span class="brand-logo">
                            </span>
                            <h2 class="brand-text">CTP BD CRM</h2>
                        </Link></li>
                    <li class="nav-item nav-toggle">
                        <Link preserve-scroll class="nav-link modern-nav-toggle">
                            <vue-feather type="x" @click="toggleVerticalMenuActive" class="d-block d-xl-none" />
                            <vue-feather :type="collapseTogglerIconFeather" @click="toggleCollapsed" class="d-none d-xl-block collapse-toggle-icon" />
                        </Link>
                    </li>
                </ul>
            </slot>
        </div>
        <div class="shadow-bottom"></div>
        <perfect-scrollbar>
            <div class="main-menu-content scroll-area">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('dashboard.show') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/dashboard">
                            <vue-feather type="home" size="8"/>
                            <span class="menu-title text-truncate" data-i18n="Dashboards">Dashboards</span>
                        </Link>
                    </li>


                    <li class=" nav-item has-sub" :class="{'open' : clickMenu === 1}"  @click="toggleSubMenu(1)"
                        v-if="this.$page.props.auth.user.role.includes('Administrator') ||
                        this.$page.props.auth.user.can.includes('user.index') ||
                        this.$page.props.auth.user.can.includes('authorization.index')">
                        <a preserve-scroll class="d-flex align-items-center">
                            <vue-feather type="users" size="8"/>
                            <span class="menu-title text-truncate"
                                  data-i18n="Authentication">User Management</span>
                        </a>
                        <ul class="menu-content">
                            <li v-if="this.$page.props.auth.user.can.includes('authorization.index') || this.$page.props.auth.user.role.includes('Administrator')">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/authorizations">
                                    <vue-feather type="circle" />
                                    <span class="menu-item text-truncate" data-i18n="Login">Role & Permissions</span>
                                </Link>
                            </li>
                            <li v-if="this.$page.props.auth.user.can.includes('user.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/users">
                                    <vue-feather type="circle" />
                                    <span class="menu-item text-truncate" data-i18n="Login">Manage user</span>
                                </Link>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item has-sub" :class="{'open' : clickMenu === 2}"  @click="toggleSubMenu(2)"
                        v-if="this.$page.props.auth.user.can.includes('client.index') ||
                        this.$page.props.auth.user.can.includes('leads.index') ||
                        this.$page.props.auth.user.can.includes('leads.ownonly') ||
                        this.$page.props.auth.user.role.includes('Administrator') ">
                        <a preserve-scroll class="d-flex align-items-center">
                            <vue-feather type="radio" size="8"/>
                            <span class="menu-title text-truncate"
                                  data-i18n="Authentication">Lead Source</span>
                        </a>
                        <ul class="menu-content">
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('leads.index') ||
                            this.$page.props.auth.user.can.includes('leads.ownonly') ||
                            this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/leads">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Leads</span>
                                </Link>
                            </li>
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('client.index') ||
                            this.$page.props.auth.user.can.includes('client.ownonly') ||
                             this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/clients">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Clients</span>
                                </Link>
                            </li>
                        </ul>
                    </li>

<!--                    <li class=" nav-item has-sub" :class="{'open' : clickMenu === 3}"  @click="toggleSubMenu(3)"
                        v-if="
                             this.$page.props.auth.user.role.includes('Administrator') ||
                             this.$page.props.auth.user.can.includes('platform.index') ||
                             this.$page.props.auth.user.can.includes('packages.index') ||
                             this.$page.props.auth.user.can.includes('services.index')
                          ">
                        <a preserve-scroll class="d-flex align-items-center">
                            <vue-feather type="gift" />
                            <span class="menu-title text-truncate"
                                  data-i18n="Authentication">Services</span>
                        </a>
                        <ul class="menu-content">
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('platform.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/platforms">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Platforms</span>
                                </Link>
                            </li>
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('packages.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/package" >
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Package</span>
                                </Link>
                            </li>
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('services.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/services">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Services</span>
                                </Link>
                            </li>
&lt;!&ndash;                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('feature.show') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/features">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Feature</span>
                                </Link>
                            </li>

                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('work.show') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/works">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Works</span>
                                </Link>
                            </li>

                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('domain.show') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/domains">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Domains</span>
                                </Link>
                            </li>
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('hosting.show') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/hostings">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Hostings</span>
                                </Link>
                            </li>&ndash;&gt;
                        </ul>
                    </li>-->
                    <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('services.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/services">
                            <vue-feather type="gift" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Services</span>
                        </Link>
                    </li>


                    <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('quotation.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/quotations">
                            <vue-feather type="aperture" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Quotations</span>
                        </Link>
                    </li>

                    <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('invoice.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/invoices">
                            <vue-feather type="archive" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Invoices</span>
                        </Link>
                    </li>

                    <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('project.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/projects">
                            <vue-feather type="package" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Projects</span>
                        </Link>
                    </li>

                    <li class=" nav-item"  v-if="this.$page.props.auth.user.can.includes('project.employees')">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/project/employee-projects">
                            <vue-feather type="package" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Projects</span>
                        </Link>
                    </li>


                    <li class="nav-item" v-if="this.$page.props.auth.user.can.includes('expanse.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/expense">
                            <vue-feather type="dollar-sign" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Expense</span>
                        </Link>
                    </li>


                    <li class="nav-item has-sub" :class="{'open' : clickMenu === 5}"  @click="toggleSubMenu(5)"
                        v-if="this.$page.props.auth.user.can.includes('note.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                        <a preserve-scroll class="d-flex align-items-center">
                            <vue-feather type="cpu" />
                            <span class="menu-title text-truncate"
                                  data-i18n="Authentication">Notes</span>
                        </a>
                        <ul class="menu-content">
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('note.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/notes-category">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Notes Category</span>
                                </Link>
                            </li>

                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('note.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/notes">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Notes</span>
                                </Link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" v-if="this.$page.props.auth.user.can.includes('note.employees')">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/employee-notes">
                            <vue-feather type="file-text" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Notes</span>
                        </Link>
                    </li>


                    <li class=" nav-item has-sub" :class="{'open' : clickMenu === 4}"  @click="toggleSubMenu(4)"
                        v-if="this.$page.props.auth.user.can.includes('transaction.index') ||
                        this.$page.props.auth.user.can.includes('purpose.index') ||
                        this.$page.props.auth.user.can.includes('method.index') ||
                        this.$page.props.auth.user.role.includes('Administrator') ">
                        <a preserve-scroll class="d-flex align-items-center">
                            <vue-feather type="trending-up" />
                            <span class="menu-title text-truncate"
                                  data-i18n="Authentication">Transactions</span>
                        </a>
                        <ul class="menu-content">
<!--                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('expanse.index') || this.$page.props.auth.user.role.includes('Administrator') ">-->
<!--                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/expense">-->
<!--                                    <vue-feather type="circle" />-->
<!--                                    <span class="menu-title text-truncate" data-i18n="Chat">Expense</span>-->
<!--                                </Link>-->
<!--                            </li>-->
                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('method.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/methods">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Methods</span>
                                </Link>
                            </li>

                            <li class=" nav-item" v-if="this.$page.props.auth.user.can.includes('purpose.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/purposes">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Purposes</span>
                                </Link>
                            </li>

                            <li class=" nav-item"  v-if="this.$page.props.auth.user.can.includes('transaction.index') || this.$page.props.auth.user.role.includes('Administrator') ">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/transaction">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">All Transactions</span>
                                </Link>
                            </li>
                        </ul>
                    </li>


                    <li class="nav-item" v-if="this.$page.props.auth.user.can.includes('settings.show') || this.$page.props.auth.user.role.includes('Administrator')">
                        <Link preserve-scroll class="d-flex align-items-center" href="/admin/business-settings">
                            <vue-feather type="settings" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Settings</span>
                        </Link>
                    </li>


                    <li class="nav-item has-sub" v-if="this.$page.props.auth.user.can.includes('show.old_data') || this.$page.props.auth.user.role.includes('Administrator')" :class="{'open' : clickMenu === 6}"  @click="toggleSubMenu(6)">
                        <a preserve-scroll class="d-flex align-items-center">
                            <vue-feather type="anchor" />
                            <span class="menu-title text-truncate"
                                  data-i18n="Authentication">Old Data</span>
                        </a>
                        <ul class="menu-content">
                            <li class="nav-item">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/old-quotation">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Old Quotation</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link preserve-scroll class="d-flex align-items-center" href="/admin/old-invoice">
                                    <vue-feather type="circle" />
                                    <span class="menu-title text-truncate" data-i18n="Chat">Old Invoice</span>
                                </Link>
                            </li>

                        </ul>
                    </li>




                    <li class="nav-item">
                        <Link preserve-scroll class="d-flex align-items-center" method="post" href="/admin/logout">
                            <vue-feather type="log-out" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Logout</span>
                        </Link>
                    </li>



<!--                    <li class=" nav-item">
                        <Link preserve-scroll method="post" class="d-flex align-items-center" href="/admin/logout">
                            <vue-feather type="power" />
                            <span class="menu-title text-truncate" data-i18n="Chat">Logout</span>
                        </Link>
                    </li>-->
                </ul>
            </div>
        </perfect-scrollbar>
    </div>
</template>

<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'

const props = defineProps({
  isVerticalMenuActive: {
      type: Boolean,
      required: true,
    },
    toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
})
const isMouseHovered = ref(false)
const openClass = ref('')
const clickMenu = ref(0)
const store = useStore()

const themeSkin = computed(() => store.state.skin)
const isVerticalMenuCollapsed = computed(() => store.state.isVerticalMenuCollapsed)

const collapseTogglerIconFeather = computed(() => (collapseTogglerIcon.value === 'unpinned' ? 'circle' : 'disc'))

onMounted(() => store.commit('UDATE_SKIN', themeSkin.value))

const collapseTogglerIcon = computed(() => {
    if (props.isVerticalMenuActive) {
      return isVerticalMenuCollapsed.value ? 'unpinned' : 'pinned'
    }
    return 'close'
  })

  const toggleCollapsed = () => {
      store.commit('UPDATE_MENU_COLLAPSED', !isVerticalMenuCollapsed.value)
  }

  const toggleSubMenu = (val) => {
      openClass.value = openClass.value ? '' : 'open'
      clickMenu.value = clickMenu.value === val ? 0 : val
  }

  const updateMouseHovered = val => {
    isMouseHovered.value = val
  }
</script>

<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css"/>
