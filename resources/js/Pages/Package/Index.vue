<template>
    <Head title="Package Management"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Packages</h4>

                                    <div
                                        class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                        <div class="select-search-area">
                                            <label>Search:<input v-model="search" type="search"
                                                                 class="form-control" placeholder="Search Now"
                                                                 aria-controls="DataTables_Table_0"></label>
                                        </div>
                                    </div>
                                    <a class="dt-button add-new btn btn-primary"
                                       v-if="$page.props.auth.user.can.includes('packages.create') || $page.props.auth.user.role.includes('Administrator')"
                                       :href="props.main_url+'/create'">
                                        Add Package
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Advanced Search -->
                <section>
                    <div class="row">
                        <div class="col-md-4" v-for="pac in props.packages.data" :key="pac.id">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="text-capitalize">{{ pac.name }}</h5>
                                        <CDropdown
                                            v-if="$page.props.auth.user.can.includes('packages.delete') || $page.props.auth.user.can.includes('packages.edit') || $page.props.auth.user.role.includes('Administrator')">
                                            <CDropdownToggle class="p-0">
                                                <vue-feather type="more-vertical" />
                                            </CDropdownToggle>
                                            <CDropdownMenu>
                                                <CDropdownItem
                                                    :href="pac.edit_url"
                                                    v-if="$page.props.auth.user.can.includes('packages.edit') || $page.props.auth.user.role.includes('Administrator')"
                                                >
                                                    <vue-feather type="edit" size="15"/>
                                                    <span class="ms-1">Edit</span>
                                                </CDropdownItem>

                                                <CDropdownItem
                                                    v-if="$page.props.auth.user.can.includes('packages.delete') || $page.props.auth.user.role.includes('Administrator')"
                                                    @click="deleteItem(props.main_url, pac.id)">
                                                    <vue-feather type="trash-2" size="15"/>
                                                    <span class="ms-1">Delete</span>
                                                </CDropdownItem>
                                            </CDropdownMenu>
                                        </CDropdown>
                                    </div>
                                    <p class="text-capitalize">platform: {{ pac.platform.name }}</p>
                                    <p v-html="pac.description"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <Pagination :links="packages.links" :from="packages.from" :to="packages.to" :total="packages.total"/>
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
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce";
    import {router} from "@inertiajs/vue3";
    import Swal from 'sweetalert2'
    import {useForm} from "@inertiajs/vue3";
    import Pagination from "@/components/Pagination.vue";

    import axios from "axios";
    import {CDropdown,CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
    import {useAction} from "../../composables/useAction";

    const {deleteItem} = useAction();
    const props = defineProps({
        packages:Array|null,
        filters: Object,
        main_url:String|null,
    })
    const search = ref(props.filters.search);
    const perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.main_url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>

<style lang="scss">

</style>
