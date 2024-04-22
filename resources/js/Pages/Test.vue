<template>

    <Head title="CRM Dashboard"/>
    <!-- Dashboard Ecommerce Starts -->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <section id="dashboard-ecommerce">
            <div class="row match-height">
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="text-capitalize">Welcome {{ this.$page.props.auth.user.username }}</h1>
<!--                            <pre>
                                {{ this.$page.props.auth.user.can }}
                            </pre>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row">
            <div class="col-md-6" v-if="this.$page.props.auth.user.can.includes('leads.index') ||
            this.$page.props.auth.user.can.includes('leads.ownonly') ||
            this.$page.props.auth.user.role.includes('Administrator')">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Today Followup Leads</h2>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lead in props.followup_leads">
                                    <td>{{ lead.name}}</td>
                                    <td>{{ lead.email}}</td>
                                    <td>{{ lead.phone}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Others Followup Leads</h2>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lead in props.others_followup_leads">
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ lead.name }}</strong>
                                            <smalll>{{ lead.email }}</smalll>
                                        </div>
                                    </td>
                                    <td>{{ lead.phone}}</td>
                                    <td>{{ moment(lead.follow_up)?.format('D-MM-y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6" v-if="this.$page.props.auth.user.can.includes('client.index') ||
            this.$page.props.auth.user.role.includes('Administrator')">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Today Followup Clients</h2>
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="client in props.followup_clients">
                                <td>{{ client.name }}</td>
                                <td>{{ client.email }}</td>
                                <td>{{ client.phone }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Others Followup Clients</h2>
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="client in props.others_followup_Clients">
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ client.name }}</strong>
                                        <small>{{ client.email }}</small>
                                    </div>
                                </td>
                                <td>{{ client.phone}}</td>
                                <td>{{ moment(client.follow_up)?.format('D-MM-y') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="this.$page.props.auth.user.can.includes('note.ownonly')
        || this.$page.props.auth.user.can.includes('note.index') || this.$page.props.auth.user.role.includes('Administrator')">
            <div class="col-md-6">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Notes</h2>
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th></th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="note in props.notes">
                                <td class="d-flex flex-column">
                                    <strong class="text-capitalize">{{ note?.title }}</strong>
                                    <small>Category: {{ note?.note_category?.title }}</small>
                                </td>
                                <td></td>
                                <td>
                                    <a :href="`/admin/notes/${note.id}`" class="btn btn-sm btn-default" v-if="this.$page.props.auth.user.role.includes('Administrator') || this.$page.props.auth.user.can.includes('note.show')">
                                        <vue-feather type="eye" size="14"/>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6" v-if="props.todos?.leangth">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Active Todos</h2>
                        <ul id="chat" class="m-0 p-0" style="list-style:none">
                            <li v-for="todo in props.todos" class="todo-item-style p-1 border-bottom">
                                <a :href="`/admin/todos/${todo.id}?show_data=true`" class="d-flex align-items-center justify-content-between">
                                   <div class="d-flex align-items-center">
                                        <div class="ms-1 todo-content cursor-pointer">
                                            <h4 class="m-0 text-black" >
                                                {{ todo?.title.slice(0, 100) }}
                                            </h4>
                                            <small @click="showItem(todo.id)">
                                                <span style="margin-left:5px;">{{ todo.about_todo?.slice(0, 100) }}</span>
                                                <span v-if="todo.file">
                                                    |
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 15px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>
                                                </span>
                                            </small>
                                        </div>
                                   </div>
                                    <div class="d-flex align-items-center" style="gap:5px">
                                    <span class="badge"
                                          :class="{
                                            'badge-light-success' : todo.priority === 'Complete',
                                            'badge-light-primary' : todo.priority === 'Normal',
                                            'badge-light-info' : todo.priority === 'Contacted',
                                            'badge-light-info' : todo.priority === 'Medium',
                                            'badge-light-purple' : todo.priority === 'Low',
                                            'badge-light-danger' : todo.priority === 'High',
                                            'badge-light-indego' : todo.priority === 'New Lead',
                                            'badge-light-warning' : todo.priority === 'In Process',
                                            'badge-light-indego' : todo.priority === 'First',
                                        }"
                                    >{{ todo.priority }}</span>
                                        <small class="badge text-black">{{ moment(todo.date).format('MMMM, D') }}</small>
                                        <span class="avatar me-1" v-c-tooltip="todo.user?.name">
                                        <img :src="todo.user.photo" height="32" width="32" alt="Generic placeholder image">
                                    </span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <!--        <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h1 class="fw-bolder">Total Seals</h1>
                                    <div class="d-flex align-items-center justify-content-between pe-3">
                                        <div>
                                            <h2 class="text-success">{{ trans.totalGrand }} Tk</h2>
                                            <small class="text-warning">Total Seals Without Discount : {{ trans.totalSeals }} Tk</small>
                                        </div>
                                        <span class="text-success">
                                            <vue-feather type="trending-up" size="40"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="fw-bolder">Total Expanse</h2>
                                    <div class="d-flex align-items-center justify-content-between pe-3">
                                        <h2 class="text-danger">{{ trans.totalExpanse }} Tk</h2>
                                        <span class="text-danger">
                                            <vue-feather type="trending-down" size="40"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <h2 class="fw-bolder">Gross Profit</h2>
                                        <span class="text-info cursor-pointer" v-c-tooltip="`(Custom Invoice Amount + Quotation Invoice Amount ) - (Custom Invoice Discount + Quotation Invoice Discount + Total Expanse ) = Gross profit`"><vue-feather type="info"/></span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pe-3">
                                        <h2 class="text-info">{{ trans.totalProfit }} Tk</h2>
                                        <span class="text-info">
                                            <vue-feather type="refresh-ccw" size="40"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75"> {{ trans.totalPay }} Tk</h3>
                                        <span>Total Payment</span>
                                    </div>
                                    <div class="avatar bg-light-success p-50">
                                        <vue-feather type="dollar-sign"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ trans.totalDue }} Tk</h3>
                                        <span>Total Due</span>
                                    </div>
                                    <div class="avatar bg-light-primary p-50">
                                        <span class="avatar-content">
                                            <vue-feather type="corner-down-left"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ trans.todayIn }} Tk</h3>
                                        <span>Today Income</span>
                                    </div>
                                    <div class="avatar bg-light-danger p-50">
                                        <span class="avatar-content">
                                            <vue-feather type="command"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="fw-bolder mb-75">{{ trans.todayExp }} Tk</h3>
                                        <span>Today Expanse</span>
                                    </div>
                                    <div class="avatar bg-light-info p-50">
                                        <vue-feather type="framer"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <Bar :data="data" :options="options" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>-->


<!--
        <section class="row">

            <div class="col-xl-12 col-md-6 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                        <h4 class="card-title">Statistics</h4>
                        &lt;!&ndash;                            <div class="d-flex align-items-center">&ndash;&gt;
                        &lt;!&ndash;                                <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p>&ndash;&gt;
                        &lt;!&ndash;                            </div>&ndash;&gt;
                    </div>
                    <div class="card-body statistics-body">
                        <div class="row">
                            <div class="col-xl col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-primary me-2">
                                        <div class="avatar-content">
                                            <vue-feather type="users"/>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{ props.data.clients }}</h4>
                                        <p class="card-text font-small-3 mb-0">Clients</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-sm-6 col-12 mb-2 mb-xl-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-info me-2">
                                        <div class="avatar-content">
                                            <vue-feather type="refresh-ccw"/>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{ props.data.users }}</h4>
                                        <p class="card-text font-small-3 mb-0">Users</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-sm-6 col-12 mb-2 mb-sm-0">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-success me-2">
                                        <div class="avatar-content">
                                            <vue-feather type="trending-up"/>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{ props.data.website }}</h4>
                                        <p class="card-text font-small-3 mb-0">Websits</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-danger me-2">
                                        <div class="avatar-content">
                                            <vue-feather type="trending-down"/>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{ props.data.work }}</h4>
                                        <p class="card-text font-small-3 mb-0">Work</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-secondery me-2">
                                        <div class="avatar-content">
                                            <vue-feather type="zap-off"/>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{ props.data.platforms }}</h4>
                                        <p class="card-text font-small-3 mb-0">Platforms</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl col-sm-6 col-12">
                                <div class="d-flex flex-row">
                                    <div class="avatar bg-light-warning me-2">
                                        <div class="avatar-content">
                                            <vue-feather type="star"/>
                                        </div>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="fw-bolder mb-0">{{ props.data.packages }}</h4>
                                        <p class="card-text font-small-3 mb-0">Packages</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            &lt;!&ndash;/ Statistics Card &ndash;&gt;
        </section>
-->



    </div>
    <!-- Dashboard Ecommerce ends -->


</template>


<script setup>
import ProgressChart from "../components/ProgressChart.vue";
import moment from "moment";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import { Bar} from 'vue-chartjs'
import {options} from './Modules/chartConfig'
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)


let props = defineProps({
    trans: Object,
    followup_leads:[]|null,
    others_followup_leads:[]|null,
    followup_clients:[]|null,
    others_followup_Clients:[]|null,
    todos:[]|null,
    notes:[],
})

/*
console.log(props.trans.tranByExp);

const data = {
    labels:['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [
        {
            label: 'Transaction Count',
            backgroundColor: '#d8b7ff',
            data:  props.trans.tranByCount,
        },
        {
            label: 'Transaction Amount Tk ',
            backgroundColor: '#c6ffa3',
            data:  props.trans.tranByAmount,
        },
        {
            label: 'Expanse Amount Tk ',
            backgroundColor: '#ffb5b5',
            data:  props.trans.tranByExp,
        },
    ]

    // labels: [ 'January', 'February', 'March'],
    // datasets: [
    //     {
    //         label: 'Data One',
    //         backgroundColor: '#f87979',
    //         data: [40, 20, 12]
    //     }
    // ]
}

*/


</script>

<style scoped>
.newlineStringStyle {
    white-space: pre-wrap;
}
</style>
