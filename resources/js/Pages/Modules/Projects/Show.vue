<template>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Advance Card</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Card</a>
                                    </li>
                                    <li class="breadcrumb-item active">Advance Card
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                            <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="app-todo.html"><i class="me-1" data-feather="check-square"></i><span class="align-middle">Todo</span></a><a class="dropdown-item" href="app-chat.html">
                                <i class="me-1" data-feather="message-square"></i>
                                <span class="align-middle">Chat</span></a>
                                <a class="dropdown-item" href="app-email.html">
                                    <i class="me-1" data-feather="mail"></i>
                                    <span class="align-middle">Email</span>
                                </a>
                                <a class="dropdown-item" href="app-calendar.html">
                                    <i class="me-1" data-feather="calendar"></i>
                                    <span class="align-middle">Calendar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Card Advance -->
                <div class="row match-height">
                    <!-- Profile Card -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-profile">
                            <img src="../../../app-assets/images/banner/banner-12.jpg" class="img-fluid card-img-top" alt="Profile Cover Photo" />
                            <div class="card-body">
                                <div class="profile-image-wrapper">
                                    <div class="profile-image">
                                        <div class="avatar">
                                            <img src="../../../app-assets/images/portrait/small/avatar-s-9.jpg" alt="Profile Picture" />
                                        </div>
                                    </div>
                                </div>
                                <h3 v-if="info.client">{{ info.client.name ?? " "}}</h3>
                                <h6 class="text-muted" v-if="info.client">{{ info.client.email }}</h6>
                                <span class="badge badge-light-primary profile-badge">Client</span>

                                <table class="table table-borderless table-striped">
                                    <tbody>
                                        <tr v-if="info.client">
                                            <th>Phone</th>
                                            <td>{{ info.client.phone }}</td>
                                        </tr>
                                        <tr v-if="info.client">
                                            <th>Secondary Phone</th>
                                            <td>{{ info.client.secondary_phone }}</td>
                                        </tr>
                                        <tr v-if="info.client">
                                            <th>Email</th>
                                            <td>{{ info.client.secondary_email }}</td>
                                        </tr>
                                        <tr v-if="info.client">
                                            <th>Email</th>
                                            <td>{{ info.client.company }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="text-start mt-2">
                                    <h5 class="border-bottom-primary d-inline" >Address:</h5>
                                    <p v-if="info.client">{{ info.client.address }}</p>
                                </div>
                                <hr class="mb-2" />
                                <Link class="btn bg-light-primary">Go To Client Profile</Link>

                            </div>
                        </div>
                    </div>
                    <!--/ Profile Card -->

                    <!-- App Design Card -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-app-design">
                            <div class="card-body">
                                <h4 class="card-title mt-1 mb-75 text-uppercase" v-if="info.name">{{ info.name }}</h4>
                                <p class="card-text font-small-2 mb-2 text-right" v-if="info.description" v-html="info.description"></p>


                                <h4>Project Credentials:</h4>
                                <p class="" v-if="info.credential" v-html="info.credential"></p>

                                <div class="design-group">
                                    <h6 class="section-label">Team</h6>
                                    <span class="badge badge-light-warning me-1">Figma</span>
                                    <span class="badge badge-light-primary">Wireframe</span>
                                </div>
                                <div class="design-group">
                                    <h6 class="section-label">Members</h6>
                                    <div class="avatar" v-for="developer  in info.users">
                                        <img src="../../../app-assets/images/portrait/small/avatar-s-9.jpg" width="34" height="34" alt="Avatar" />
                                    </div>
                                </div>
                                <div class="design-planning-wrapper">
                                    <div class="design-planning">
                                        <p class="card-text mb-25">Due Date</p>
                                        <h6 class="mb-0">12 Apr, 21</h6>
                                    </div>
                                    <div class="design-planning">
                                        <p class="card-text mb-25">Budget</p>
                                        <h6 class="mb-0">$49251.91</h6>
                                    </div>
                                    <div class="design-planning">
                                        <p class="card-text mb-25">Cost</p>
                                        <h6 class="mb-0">$840.99</h6>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <Link class="btn bg-light-warning">Edit This Project</Link>
                                </div>
                                <div class="">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ App Design Card -->

                    <!-- Apply Job Card -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-apply-job">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex flex-row">
                                        <div class="avatar me-1">
                                            <img src="../../../app-assets/images/portrait/small/avatar-s-20.jpg" alt="Avatar" width="42" height="42" />
                                        </div>
                                        <div class="user-info">
                                            <h5 class="mb-0" v-if="info.user">{{ info.user.name }}</h5>
                                            <small class="text-muted">Created {{ dates.created_at }}</small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill badge-light-primary">Design</span>
                                </div>
                                <h5 class="apply-job-title">Porject Nots:</h5>
                                <p>Lorem ip deleniti fuga necessitatibus numquam sint. lorem</p>
                                <p class="card-text mb-2" v-if="info.nots" v-html="info.nots"></p>
                                <div class="d-grid">
                                    <button type="button" class="btn bg-light-primary">Goto User Profile</button>
                                </div>
                            </div>
                        </div>



                        <div class="card card-apply-job">
                            <div class="card-body">
                                <div>
                                    <h3>Project Time line</h3>

                                    <span
                                        class="badge mb-1"
                                        :class="{
                                            'badge-light-primary'   : info.status === 'New Project',
                                            'badge-light-warning'   : info.status === 'Testing',
                                            'badge-light-success'   : info.status === 'Complete',
                                            'badge-light-secandery' : info.status === 'Revision',
                                            'badge-light-danger'    : info.status === 'Canceled',
                                        }">
                                        {{ info.status }}
                                    </span>
                                    <div class="progress" style="height: 7px;">
                                        <div role="progressbar"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             aria-valuenow="50"
                                             class="progress-bar progress-bar-striped"
                                             :class="{
                                                'bg-primary'   : info.status === 'New Project',
                                                'bg-warning'   : info.status === 'Testing',
                                                'bg-success'   : info.status === 'Complete',
                                                'bg-secandery' : info.status === 'Revision',
                                                'bg-danger'    : info.status === 'Canceled',
                                             }"
                                             :style="{ width: `${info.progress}%`}">
                                        </div>
                                    </div>
                                    <span>{{ info.progress }}%</span>
                                </div>


                                <div class="d-flex justify-content-around my-2 pt-75">
                                    <div class="d-flex align-items-start me-2">
                                            <span class="badge bg-light-warning p-75 rounded">
                                                <Icon type="edit"/>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0">1.23k</h4>
                                            <small>Tasks Done</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <Icon type="edit" width="21" height="21"/>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0">568</h4>
                                            <small>Projects Done</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-around my-2 pt-75">
                                    <div class="d-flex align-items-start me-2">
                                            <span class="badge bg-light-info p-75 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check font-medium-2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0">1.23k</h4>
                                            <small>Tasks Done</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                            <span class="badge bg-light-danger p-75 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase font-medium-2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0">568</h4>
                                            <small>Projects Done</small>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!--/ Apply Job Card -->
<!--
                     Transaction card
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-transaction">
                            <div class="card-header">
                                <h4 class="card-title">Transactions</h4>
                                <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"></i>
                            </div>
                            <div class="card-body">
                                <div class="transaction-item">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary rounded">
                                            <div class="avatar-content">
                                                <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="transaction-info">
                                            <h6 class="transaction-title">Wallet</h6>
                                            <small>Starbucks</small>
                                        </div>
                                    </div>
                                    <div class="fw-bolder text-danger">- $74</div>
                                </div>
                                <div class="transaction-item">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-success rounded">
                                            <div class="avatar-content">
                                                <i data-feather="check" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="transaction-info">
                                            <h6 class="transaction-title">Bank Transfer</h6>
                                            <small>Add Money</small>
                                        </div>
                                    </div>
                                    <div class="fw-bolder text-success">+ $480</div>
                                </div>
                                <div class="transaction-item">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-danger rounded">
                                            <div class="avatar-content">
                                                <i data-feather="dollar-sign" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="transaction-info">
                                            <h6 class="transaction-title">Paypal</h6>
                                            <small>Add Money</small>
                                        </div>
                                    </div>
                                    <div class="fw-bolder text-success">+ $590</div>
                                </div>
                                <div class="transaction-item">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-warning rounded">
                                            <div class="avatar-content">
                                                <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="transaction-info">
                                            <h6 class="transaction-title">Mastercard</h6>
                                            <small>Ordered Food</small>
                                        </div>
                                    </div>
                                    <div class="fw-bolder text-danger">- $23</div>
                                </div>
                                <div class="transaction-item">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-info rounded">
                                            <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="transaction-info">
                                            <h6 class="transaction-title">Transfer</h6>
                                            <small>Refund</small>
                                        </div>
                                    </div>
                                    <div class="fw-bolder text-success">+ $98</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    / Transaction card

                     Payment Card
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-payment">
                            <div class="card-header">
                                <h4 class="card-title">Pay Amount</h4>
                                <h4 class="card-title text-primary">$455.60</h4>
                            </div>
                            <div class="card-body">
                                <form action="javascript:void(0);" class="form">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <label class="form-label" for="payment-card-number">Card Number</label>
                                                <input type="number" id="payment-card-number" class="form-control" placeholder="2133 3244 4567 8921" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-2">
                                                <label class="form-label" for="payment-expiry">Expiry</label>
                                                <input type="number" id="payment-expiry" class="form-control" placeholder="MM / YY" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-2">
                                                <label class="form-label" for="payment-cvv">CVV / CVC</label>
                                                <input type="number" id="payment-cvv" class="form-control" placeholder="123" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <label class="form-label" for="payment-input-name">Input Name</label>
                                                <input type="text" id="payment-input-name" class="form-control" placeholder="Curtis Stone" />
                                            </div>
                                        </div>
                                        <div class="d-grid col-12">
                                            <button type="button" class="btn btn-primary">Make Payment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    / Payment Card -->
                </div>
            </div>
        </div>
    </div>


</template>



<script setup>
import Pagination from "../../../components/Pagination"
import Icon from '../../../components/Icon'
import Modal from '../../../components/Modal'
import InputFieldError from "../../../components/InputFieldError";
import TextEditor from "../../../components/TextEditor";
import ImageUploader from "../../../components/ImageUploader"
import {ref, watch} from "vue";
import debounce from "lodash/debounce";
import {router} from "@inertiajs/vue3";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/vue3";
import axios from 'axios';


let props = defineProps({
    info: Object,
    dates:"",
});


</script>

