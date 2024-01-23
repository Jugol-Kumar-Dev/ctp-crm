<template>
    <Head title="Lead Management"/>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                <!-- Advanced Search -->
                <section id="advanced-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom d-flex justify-content-between">
                                    <h4 class="card-title">Lead Information's </h4>
                                    <button
                                        v-if="this.$page.props.auth.user.can.includes('leads.create') || this.$page.props.auth.user.role.includes('Administrator')"
                                        class="dt-button add-new btn btn-primary"
                                        @click="addDataModal"
                                    >
                                        Add Lead
                                    </button>
                                    <!--                                    <div>
                                                                            <CDropdown>
                                                                                <CDropdownToggle class="p-0">
                                                                                    <button class="btn bg-light-secondary d-flex align-items-center">
                                                                                        <vue-feather type="external-link" size="15"/>
                                                                                        <span class="px-1">Export</span>
                                                                                        <vue-feather type="chevron-down" size="15"/>
                                                                                    </button>
                                                                                </CDropdownToggle>
                                                                                <CDropdownMenu>
                                                                                    <CDropdownItem @click="exportPDF">
                                                                                        &lt;!&ndash;                                                    <vue-feather type="download" size="15"/>&ndash;&gt;
                                                                                        <span class="ms-1">PDF</span>
                                                                                    </CDropdownItem>
                                    &lt;!&ndash;                                                <CDropdownItem target="_blank">
                                                                                        &lt;!&ndash;                                                    <vue-feather type="download" size="15"/>&ndash;&gt;
                                                                                        <span class="ms-1">EXCEL</span>
                                                                                    </CDropdownItem>&ndash;&gt;
                                                                                </CDropdownMenu>
                                                                            </CDropdown>
                                                                        </div>-->
                                </div>
                                <div class="card-datatable table-responsive pt-0 px-2">
                                    <div class="d-flex align-items-center justify-content-between border-bottom">
                                        <div class="select-search-area d-flex align-items-center">
                                            <select class="form-select" v-model="perPage">
                                                <option :value="undefined">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                                <option value="500">500</option>
                                            </select>
                                            <div class="ml-2">
                                                <select v-model="searchByStatus"
                                                        class="select2 form-select select w-100">
                                                    <option selected disabled :value="undefined">Filter By Quotation
                                                        Status
                                                    </option>
                                                    <option :value="null">All</option>
                                                    <option v-for="item in status" :value="item.name">{{ item.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <Datepicker v-model="dateRange"
                                                        :monthChangeOnScroll="false"
                                                        range multi-calendars
                                                        :enable-time-picker="false"
                                                        :format="'d-MM-Y'"
                                                        placeholder="Select Date Range" autoApply
                                                        @update:model-value="handleDate" ></Datepicker>

                                            <a class="btn btn-sm btn-icon btn-primary" v-if="isReset"
                                               href="/admin/leads">
                                                <vue-feather type="x-circle"></vue-feather>
                                            </a>

                                        </div>
                                        <div
                                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                            <div class="select-search-area">
                                                <label>Search
                                                    <input v-model="search"
                                                           type="search"
                                                           class="form-control"
                                                           placeholder="Search Now"
                                                           aria-controls="DataTables_Table_0">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bulk-opration" v-if="checkedUsers.ids.length">
                                        <ul class="list-group">
                                            <li class="list-item" @click="updateBulkStatus">Update Bulk Status</li>
                                            <li class="list-item" @click="updateBulkAgents">Assign Bulk Agents</li>
                                        </ul>
                                    </div>

                                    <table class="user-list-table table table-striped">
                                        <thead class="table-light">
                                        <tr class=null>
                                            <th class="sorting">
                                                <input type="checkbox" @change="selectUsers($event)" value="true"
                                                       class="checkbox-padding form-check-input">
                                            </th>
                                            <th class="sorting">Client</th>
                                            <th class="sorting">Assigned</th>
                                            <th class="sorting">Status</th>
                                            <th class="sorting">Created At</th>
                                            <th class="sorting">Created By</th>
                                            <th class="sorting">Last Updated By</th>
                                            <th class="sorting">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="user in clients.data" :key="user.id">
                                            <td>
                                                <input type="checkbox" :value="user.id" v-model="checkedUsers.ids"
                                                       class="checkbox-padding form-check-input select_all_users">
                                            </td>


                                            <td v-if=" this.$page.props.auth.user.role.includes('Administrator') ||
                                            this.$page.props.auth.user.can.includes('leads.edit') ||
                                            this.$page.props.auth.user.can.includes('leads.delete') ||
                                            this.$page.props.auth.user.can.includes('leads.show')" class="cursor-pointer" @click="singleShowlead(user)">
                                                <div class="d-flex align-items-center gap-1 user-info">
                                                    <div class="icon">
                                                        <vue-feather type="more-vertical"/>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div class="user_name text-truncate text-body">
                                                            <span class="fw-bolder text-capitalize">{{ user.name ?? '---' }}</span>
                                                        </div>
                                                        <small class="emp_post text-muted">{{ user.email ?? '---'
                                                            }}</small>
                                                        <small>{{ user.phone }} <span
                                                            v-if="user.secondary_phone">/ {{ user.secondary_phone }}</span></small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td v-else>
                                                <div class="d-flex flex-column">
                                                    <div class="user_name text-truncate text-body">
                                                        <span class="fw-bolder text-capitalize">{{ user.name ?? '---' }}</span>
                                                    </div>
                                                    <small class="emp_post text-muted">{{ user.email ?? '---'
                                                        }}</small>
                                                    <small>{{ user.phone }} <span
                                                        v-if="user.secondary_phone">/ {{ user.secondary_phone }}</span></small>
                                                </div>
                                            </td>




                                            <td>
                                                <span v-for="user in user.assigned">{{ user.name }}, </span>
                                            </td>
                                            <td class="d-flex flex-column" style="padding:40px 0;">
                                                <span class="badge"
                                                      style="width: max-content"
                                                      :class="{
                                                'badge-light-primary' : user.status === 'Proposal Sent',
                                                'badge-light-info' : user.status === 'Contacted',
                                                'badge-light-warning' : user.status === 'Quote Sent',
                                                'badge-light-secondary' : user.status === 'Qualified',
                                                'badge-light-danger' : user.status === 'Disqualified',
                                                'badge-light-purple' : user.status === 'New Lead',
                                                'badge-light-indego' : user.status === 'Follow Up',
                                            }">{{ user.status }}
                                            </span>
                                                <span v-if="user.followUp">
                                                    {{ moment(user.followUp).format('ll') }}
                                                </span>
                                            </td>
                                            <td>{{ user.created_at }}</td>
                                            <td>{{ user.createdBy?.name ?? '---' }}</td>
                                            <td>{{ user.updatedBy?.name ?? '---' }}</td>
                                            <td>
                                                <CDropdown v-if="this.$page.props.auth.user.can.includes('leads.edit') ||
                                                this.$page.props.auth.user.can.includes('leads.show') ||
                                                this.$page.props.auth.user.can.includes('leads.delete') ||
                                                this.$page.props.auth.user.role == 'Administrator'">
                                                    <CDropdownToggle>
                                                        <vue-feather type="more-vertical"/>
                                                    </CDropdownToggle>
                                                    <CDropdownMenu>
                                                        <CDropdownItem @click="editClient(user.show_url)"
                                                                       v-if="this.$page.props.auth.user.can.includes('leads.edit') || this.$page.props.auth.user.role == 'Administrator'">
                                                            <Icon title="pencil"/>
                                                            <span class="ms-1">Edit</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem :href="`/admin/show-lead/${user.id}`"
                                                                       v-if="this.$page.props.auth.user.can.includes('leads.show') || this.$page.props.auth.user.role.includes('Administrator')">
                                                            <Icon title="eye"/>
                                                            <span class="ms-1">Show</span>
                                                        </CDropdownItem>
                                                        <CDropdownItem @click="deleteItemModal(user.id)" type="button"
                                                                       v-if="this.$page.props.auth.user.can.includes('leads.delete') || this.$page.props.auth.user.role == 'Administrator' ">
                                                            <Icon title="trash"/>
                                                            <span class="ms-1">Delete</span>
                                                        </CDropdownItem>
                                                    </CDropdownMenu>
                                                </CDropdown>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <Pagination :links="clients.links" :from="clients.from" :to="clients.to"
                                                :total="clients.total"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Advanced Search -->
                <!--/ Multilingual -->
            </div>
        </div>
    </div>


    <Modal
        v-if="this.$page.props.auth.user.can.includes('leads.create') || this.$page.props.auth.user.role.includes('Administrator')"
        id="addItemModal" :title="clientStatus ?  'Add New Lead' : 'Convert To New Client'" v-vb-is:modal
        :size="clientStatus ? 'sm' : 'lg'">
        <form @submit.prevent="createClientForm">
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Name:</label>
                        <div class=null>
                            <input v-model="createForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>

                    <div class="col-md" :class="{ 'd-none' : clientStatus }">
                        <label>Company: </label>
                        <input v-model="createForm.company" type="text" placeholder="Enter Company Name"
                               class="form-control">
                        <span v-if="errors.company" class="error text-sm text-danger">{{ errors.company }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Email:</label>
                        <div class=null>
                            <input v-model="createForm.email" type="email" placeholder="eg.example@creativetechpark.com"
                                   class="form-control">
                            <span v-if="errors.email" class="error text-sm text-danger">{{ errors.email }}</span>
                        </div>
                    </div>
                    <div class="col-md" :class="{ 'd-none' : clientStatus }">
                        <label>Secondary Email: </label>
                        <input v-model="createForm.secondary_email" type="email" placeholder="second.eg@ctpbd.com"
                               class="form-control">
                        <span v-if="errors.secondary_email"
                              class="error text-sm text-danger">{{ errors.secondary_email }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-md">
                        <label>Phone: <span class="text-danger">*</span></label>
                        <input v-model="createForm.phone" type="text" placeholder="+88017********" class="form-control">
                        <span v-if="errors.phone" class="error text-sm text-danger">{{ errors.phone }}</span>
                    </div>
                    <div class="col-md" :class="{ 'd-none' : clientStatus }">
                        <label>Secondary Phone: </label>
                        <input v-model="createForm.secondary_phone" type="text" placeholder="+88017********"
                               class="form-control">
                        <span v-if="errors.secondary_phone"
                              class="error text-sm text-danger">{{ errors.secondary_phone }}</span>
                    </div>
                </div>
                <div class="row mb-1" :class="{ 'd-none' : clientStatus }">
                    <div class="col-md">
                        <label>Address: </label>
                        <textarea v-model="createForm.address" type="text" placeholder="Enter Full Address" rows="5"
                                  class="form-control"></textarea>
                        <span v-if="errors.name" class="error text-sm text-danger">{{ errors.address }}</span>
                    </div>
                    <div class="col-md">
                        <label>Nots: </label>
                        <textarea v-model="createForm.note" type="text" placeholder="Enter note messages" rows="5"
                                  class="form-control"></textarea>
                        <span v-if="errors.note" class="error text-sm text-danger">{{ errors.note }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div :class="clientStatus ? 'col-md-12' : 'col-md'">
                        <label>Lead Status <span class="text-danger">*</span></label>
                        <v-select v-model="createForm.status"
                                  @update:modelValue="changeStatus"
                                  label="name"
                                  class="form-control select-padding"
                                  :options="status"
                                  placeholder="Select Lead Status">
                        </v-select>
                        <span v-if="errors.status" class="error text-sm text-danger">{{ errors.status }}</span>

                    </div>

                    <div class="mt-1" :class="clientStatus ? 'col-md-12' : 'col-md'">
                        <label>Assign Agent </label>
                        <v-select
                            multiple
                            v-model="createForm.agents"
                            :options="users"
                            placeholder="Select Assigned Employee"
                            class="form-control select-padding"
                            :reduce="user => user.id"
                            label="name">
                            <template v-slot:option="option">
                                <li class="d-flex align-items-start py-1">
                                    <div class="avatar me-75">
                                        <img :src="`${option.photo}`" alt="" width="38" height="38">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="me-1 d-flex flex-column">
                                            <strong class="mb-25">{{ option.name }}</strong>
                                            <span>{{ option.email }}</span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </v-select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Save
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

    <Modal id="updateBulkStatus" title="Bulk Status" v-vb-is:modal size="sm">
        <form @submit.prevent="updateBulkStatusSubmit">
            <div class="modal-body">
                <div class="row mb-1">
                    <div :class="clientStatus ? 'col-md-12' : 'col-md'">
                        <label>Lead Status <span class="text-danger">*</span></label>
                        <v-select v-model="checkedUsers.status"
                                  label="name"
                                  @update:modelValue="changeStatus"
                                  class="form-control select-padding"
                                  :options="status"
                                  :reduce="client => client.name"
                                  placeholder="Select Lead Status">
                        </v-select>
                        <span v-if="errors.status" class="error text-sm text-danger">{{ errors.status }}</span>
                    </div>
                </div>

                <div class="row mb-1" :class="{'d-none' : !followUp}">
                    <div class="col-md">
                        <label>Follow Up Date:
                            <Required/>
                        </label>
                        <div class="single-datepiker">
                            <Datepicker v-model="checkedUsers.followDate" :monthChangeOnScroll="false"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <span v-if="errors.followDate" class="error text-sm text-danger">{{
                                    errors.followDate
                                }}</span>
                        </div>
                    </div>
                </div>

                <div class="row mb-1" :class="{'d-none' : !followUp}">
                    <div class="col-md">
                        <label>Follow Up Message:</label>
                        <textarea class="form-control" v-model="checkedUsers.followMessage" rows="5"
                                  placeholder="Follow up message..."></textarea>
                        <span v-if="errors.followDate" class="error text-sm text-danger">{{ errors.followDate }}</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Update Bulk Status
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

    <Modal id="updateBulkAgents" title="Bulk Agent Assign" v-vb-is:modal size="sm">
        <form @submit.prevent="updateBulkAssign">
            <div class="modal-body">
                <div class="row mb-1">
                    <div :class="clientStatus ? 'col-md-12' : 'col-md'">
                        <label>Assign Agents <span class="text-danger">*</span></label>
                        <v-select
                            multiple
                            v-model="checkedUsers.agents"
                            :options="users"
                            placeholder="Select Assigned Employee"
                            class="form-control select-padding"
                            :reduce="user => user.id"
                            label="name">
                            <template v-slot:option="option">
                                <li class="d-flex align-items-start py-1">
                                    <div class="avatar me-75">
                                        <img :src="`${option.photo}`" alt="" width="38" height="38">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="me-1 d-flex flex-column">
                                            <strong class="mb-25">{{ option.name }}</strong>
                                            <span>{{ option.email }}</span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </v-select>
                        <span v-if="errors.status" class="error text-sm text-danger">{{ errors.status }}</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Update Bulk Status
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

    <Modal id="showSingleLead" v-vb-is:modal size="md">
        <div class="d-flex justify-content-between flex-column p-2">
            <div class="d-flex flex-column ml-1">
                <div class="d-flex justify-content-between">
                    <div class="w-50">
                        <h4 class="mb-0 text-capitalize"> {{ singleLeadShow.name }} </h4>
                        <p class="card-text m-0">{{ singleLeadShow.email }}</p>
                        <p class="card-text m-0">{{ singleLeadShow.phone }}</p>
                        <p class="badge badge-light-purple text-capitalize w-max"> {{ singleLeadShow.status }} </p>
                    </div>
                    <div class="w-50">
                        <p>Created By: {{ singleLeadShow?.createdBy?.name }}</p>
                        <p>Created Date: {{ singleLeadShow?.created_at}}</p>
                        <hr>
                        <div class="d-flex flex-column" v-if="singleLeadShow?.followUp">
                            <strong>Follow Up Date: {{ moment(singleLeadShow?.followUp)?.format('ll') }}</strong>
                            <p>Message:
                                <small>{{ singleLeadShow?.followUpMessage }}</small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column">
                    <string class="">Assign Agents: </string>
                    <div class="d-flex align-items-center" style="gap:5px;">
                        <p class="badge badge-light-success text-capitalize w-max" v-for="item in singleLeadShow.assigned"> {{ item.name }} </p>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <a :href="`/admin/show-lead/${singleLeadShow.id}`" v-if="this.$page.props.auth.user.can.includes('leads.show') ||
                    this.$page.props.auth.user.role.includes('Administrator')"
                       class="btn btn-sm btn-success btn-icon d-flex align-items-center">
                        <vue-feather type="eye" size="18"/>
                        <span>Show</span>
                    </a>
                    <button class="btn btn-sm btn-primary d-flex align-items-center"
                            v-if=" this.$page.props.auth.user.role.includes('Administrator') ||
                                            this.$page.props.auth.user.can.includes('leads.edit')"
                            @click="editClient(singleLeadShow.show_url)">
                        <vue-feather type="edit" size="18"/>
                        <span>Edit</span>
                    </button>
                    <button class="btn btn-sm btn-danger btn-icon d-flex align-items-center"
                            v-if=" this.$page.props.auth.user.role.includes('Administrator') ||
                                            this.$page.props.auth.user.can.includes('leads.delete')"
                            @click="deleteItemModal(singleLeadShow.id)">
                        <vue-feather type="trash" size="18"/>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </Modal>

    <Modal id="editClient" :title="clientStatus ?  'Edit This Lead' : 'Convert To New Client'" v-vb-is:modal
           :size="clientStatus ? 'sm' : 'lg'">
        <form @submit.prevent="updateClientForm(editData.id)">
            <div class="modal-body">
                <div class="row mb-1" :class="{'d-none' : followUp}">
                    <div class="col-md">
                        <label>Name:
                            <Required/>
                        </label>
                        <div class=null>
                            <input v-model="updateForm.name" type="text" placeholder="Name" class="form-control">
                            <span v-if="errors.name" class="error text-sm text-danger">{{ errors.name }}</span>
                        </div>
                    </div>
                    <div class="col-md" :class="{ 'd-none' : clientStatus }">
                        <label>Company: </label>
                        <input v-model="updateForm.company" type="text" placeholder="Enter Company Name"
                               class="form-control">
                        <span v-if="errors.company" class="error text-sm text-danger">{{ errors.company }}</span>
                    </div>
                </div>
                <div class="row mb-1" :class="{'d-none' : followUp}">
                    <div class="col-md">
                        <label>Email: <span class="text-danger">*</span></label>
                        <div class=null>
                            <input v-model="updateForm.email" type="email" placeholder="eg.example@creativetechpark.com"
                                   class="form-control">
                            <span v-if="errors.email" class="error text-sm text-danger">{{ errors.email }}</span>
                        </div>
                    </div>
                    <div class="col-md" :class="{ 'd-none' : clientStatus }">
                        <label>Secondary Email: </label>
                        <input v-model="updateForm.secondary_email" type="email" placeholder="second.eg@ctpbd.com"
                               class="form-control">
                        <span v-if="errors.secondary_email"
                              class="error text-sm text-danger">{{ errors.secondary_email }}</span>
                    </div>
                </div>
                <div class="row mb-1" :class="{'d-none' : followUp}">
                    <div class="col-md">
                        <label>Phone: <span class="text-danger">*</span></label>
                        <input v-model="updateForm.phone" type="text" placeholder="+88017********" class="form-control">
                        <span v-if="errors.phone" class="error text-sm text-danger">{{ errors.phone }}</span>
                    </div>
                    <div class="col-md" :class="{ 'd-none' : clientStatus }">
                        <label>Secondary Phone: </label>
                        <input v-model="updateForm.secondary_phone" type="text" placeholder="+88017********"
                               class="form-control">
                        <span v-if="errors.secondary_phone"
                              class="error text-sm text-danger">{{ errors.secondary_phone }}</span>
                    </div>
                </div>

                <div class="row mb-1" :class="{ 'd-none' : clientStatus }">
                    <div class="col-md">
                        <label>Address: </label>
                        <textarea v-model="updateForm.address" type="text" placeholder="Enter Full Address" rows="5"
                                  class="form-control"></textarea>
                        <span v-if="errors.name" class="error text-sm text-danger">{{ errors.address }}</span>
                    </div>
                    <div class="col-md">
                        <label>Nots: </label>
                        <textarea v-model="updateForm.note" type="text" placeholder="Enter note messages" rows="5"
                                  class="form-control"></textarea>
                        <span v-if="errors.note" class="error text-sm text-danger">{{ errors.note }}</span>
                    </div>
                </div>

                <div class="row mb-1" :class="{'d-none' : !followUp}">
                    <div class="col-md">
                        <label>Follow Up Date:
                            <Required/>
                        </label>
                        <div class="single-datepiker">
                            <Datepicker v-model="updateForm.followDate" :monthChangeOnScroll="false"
                                        :format="'d-MM-Y'"
                                        placeholder="Select Date" autoApply></Datepicker>
                            <span v-if="errors.followDate" class="error text-sm text-danger">{{
                                    errors.followDate
                                }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-1" :class="{'d-none' : !followUp}">
                    <div class="col-md">
                        <textarea class="form-control" v-model="updateForm.followMessage" rows="5"
                                  placeholder="Follow up message..."></textarea>
                        <span v-if="errors.followMessage" class="error text-sm text-danger">{{ errors.followMessage }}</span>
                    </div>
                </div>
                <div class="row mb-1">
                    <div :class="clientStatus ? 'col-md-12' : 'col-md'">
                        <label>Lead Status <span class="text-danger">*</span></label>
                        <v-select v-model="updateForm.status"
                                  @update:modelValue="changeStatus"
                                  label="name"
                                  class="form-control select-padding"
                                  :options="status"
                                  :reduce="item => item.name"
                                  placeholder="Select Lead Status">
                        </v-select>
                        <span v-if="errors.status" class="error text-sm text-danger">{{ errors.status }}</span>

                    </div>

                    <div class="mt-1" :class="clientStatus ? 'col-md-12' : 'col-md'">
                        <label>Assign Agent </label>
                        <v-select
                            multiple
                            v-model="updateForm.agents"
                            :options="users"
                            placeholder="Select Assigned Employee"
                            class="form-control select-padding"
                            :reduce="user => user.id"
                            label="name">
                            <template v-slot:option="option">
                                <li class="d-flex align-items-start py-1">
                                    <div class="avatar me-75">
                                        <img :src="`${option.photo}`" alt="" width="38" height="38">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="me-1 d-flex flex-column">
                                            <strong class="mb-25">{{ option.name }}</strong>
                                            <span>{{ option.email }}</span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </v-select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button :disabled="updateForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Save
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

</template>


<script setup>
import Pagination from "../../../components/Pagination"
import Icon from '../../../components/Icon'
import Modal from '../../../components/Modal'
import {ref, watch, onMounted, computed} from "vue";
import debounce from "lodash/debounce";
import {Inertia} from "@inertiajs/inertia";
import Swal from 'sweetalert2'
import {useForm} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import DropdownItems from "../../../components/modules/DropdownItems"
import moment from "moment";

import {CDropdown, CDropdownToggle, CDropdownMenu, CDropdownItem} from '@coreui/vue'
import {useDate} from "../../../composables/useDate";

const range = useDate();

let props = defineProps({
    clients: Object,
    users: Object,
    filters: Object,
    notification: Object,
    errors: Object,
    main_url: null,
});


let editData = ref([]);


let createForm = useForm({
    name: null,
    email: null,
    secondary_email: null,
    phone: null,
    secondary_phone: null,
    company: null,
    address: null,
    note: null,
    status: null,
    agents: [],
    processing: Boolean,
})

let updateForm = useForm({
    name: null,
    email: null,
    secondary_email: null,
    phone: null,
    secondary_phone: null,
    company: null,
    address: null,
    note: null,
    status: null,
    agents: null,
    followDate: null,
    followMessage:null,
})

const clientStatus = ref(true);
const followUp = ref(false);
const changeStatus = (event) => {
    clientStatus.value = event !== 'Converted to Customer';
    followUp.value = event === 'Follow Up';
}

const updateStatus = (id, status) => {
    clientStatus.value = event.name !== 'Converted to Customer';
    updateForm.status = status;
    document.getElementById('chaneStatusModal').$vb.modal.show()
}


let status = ref([{"name": 'New Lead'}, {"name": 'Contacted'}, {"name": 'Qualified'}, {"name": 'Disqualified'}, {"name": 'Follow Up'}])

let deleteItemModal = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete('leads/' + id, {
                preserveState: true, replace: true, onSuccess: page => {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                },
                onError: errors => {
                    Swal.fire(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    )
                }
            })
        }
    })
};

let addDataModal = () => {
    status.value = [
        {"name": 'New Lead'}, {"name": 'Contacted'}, {"name": 'Qualified'}, {"name": 'Disqualified'}
    ];

    createForm.reset();
    createForm.processing = false;
    clientStatus.value = true;
    document.getElementById('addItemModal').$vb.modal.show()
}
let createClientForm = () => {
    Inertia.post('clients', createForm, {
        preserveState: true,
        onStart: () => {
            createForm.processing = true
        },
        onFinish: () => {
            createForm.processing = false
        },
        onSuccess: () => {
            document.getElementById('addItemModal').$vb.modal.hide()
            createForm.reset()
            Swal.fire(
                'Saved!',
                'Your file has been Saved.',
                'success'
            )
        },
    })
}

let updateClientForm = (id) => {
    Inertia.put('clients/' + id, updateForm, {
        preserveState: true,
        onStart: () => {
            createForm.processing = true
        },
        onFinish: () => {
            createForm.processing = false
        },
        onSuccess: () => {
            document.getElementById('editClient').$vb.modal.hide()
            createForm.reset()
            Swal.fire(
                'Saved!',
                'Your file has been Updated.',
                'success'
            )
        },
    })
}

let editClient = (url) => {

    status.value = [
        {"name": 'New Lead'}, {"name": 'Contacted'}, {"name": 'Qualified'}, {"name": 'Disqualified'}, {"name": 'Follow Up'}, {"name": 'Converted to Customer'}
    ]


    axios.get(url + "?edit=true").then(res => {
        console.log(res.data)
        editData.value = res.data;
        updateForm.name = res.data.name;
        updateForm.email = res.data.email;
        updateForm.secondary_email = res.data.secondary_email;
        updateForm.phone = res.data.phone;
        updateForm.secondary_phone = res.data.secondary_phone;
        updateForm.company = res.data.company;
        updateForm.address = res.data.address;
        updateForm.note = res.data.note;
        updateForm.status = res.data.status;
        updateForm.agents = res.data.users;
        clientStatus.value = res.data.status !== 'Convarted To Customer'


        followUp.value = res.data.status === 'Follow Up' ? true : false;

        updateForm.followDate = res.data.follow_up;
        updateForm.followMessage = res.data.follow_up_message;


        document.getElementById('editClient').$vb.modal.show();
    }).catch(err => {
        console.log(err);
    });
}


const url = location.search;
const exportPDF = () => {
    if (url) {
        window.location.href = window.location.href + "&export_pdf=true";
    } else {
        window.location.href = window.location.href + "?export_pdf=true";
    }
}


const dateRange = ref(props.filters.dateRange)
const isCustom = ref(false);
const changeDateRange = (event) => {
    if (event === 'custom') {
        isCustom.value = true;
        dateRange.value = '';
    }
};
const handleDate = (event) => isCustom.value = event !== null;


const searchByStatus = ref(props.filters.byStatus)
let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage, searchByStatus, dateRange], debounce(function ([val, val2, val3, val4]) {
    Inertia.get(props.main_url, {search: val, perPage: val2, byStatus: val3, dateRange: val4}, {
        preserveState: true,
        replace: true
    });
}, 300));


const checkedUsers = useForm({
    ids: [],
    status: null,
    followDate: null,
    followMessage: null,
    agents: [],
});
const selectUsers = (event) => {
    if (event.target.checked) {
        checkedUsers.ids = props.clients.data.map(row => row.id);
    } else {
        checkedUsers.ids = [];
    }
}

const updateBulkStatus = () => {
    document.getElementById('updateBulkStatus').$vb.modal.show()
}

const updateBulkStatusSubmit = () => {
    checkedUsers.post('clients/bulk-status/update', {
        preserveState: true,
        onStart: () => {
            createForm.processing = true
        },
        onFinish: () => {
            createForm.processing = false
        },
        onSuccess: () => {
            document.getElementById('updateBulkStatus').$vb.modal.hide()
            checkedUsers.reset()
            Swal.fire(
                'Updated!',
                'Multiple Row Updated',
                'success'
            )
        }
    });

}


const updateBulkAgents = () => {
    document.getElementById('updateBulkAgents').$vb.modal.show()
}
const updateBulkAssign = () => {
    checkedUsers.post('clients/bulk-assigned/update', {
        preserveState: true,
        onStart: () => {
            createForm.processing = true
        },
        onFinish: () => {
            createForm.processing = false
        },
        onSuccess: () => {
            document.getElementsByClassName('form-check-input').checked = false;
            document.getElementById('updateBulkAgents').$vb.modal.hide()
            checkedUsers.reset()
            Swal.fire(
                'Updated!',
                'Multiple Row Updated',
                'success'
            )
        }
    });
}


const singleLeadShow = ref({});
const singleShowlead = (user) => {
    singleLeadShow.value = null;
    singleLeadShow.value = user;
    document.getElementById('showSingleLead').$vb.modal.show()
}


const isReset = computed(() => {
    return !!props.filters?.perPage || props.filters?.byStatus || props.filters?.dateRange;
})

//
// const dateRange = ref([]);
// const formattedDate = computed(() => {
//     if (dateRange.value.length === 2) {
//         const startDate = new Date(dateRange.value[0]);
//         const endDate = new Date(dateRange.value[1]);
//
//         const formattedStartDate = formatDate(startDate);
//         const formattedEndDate = formatDate(endDate);
//
//         return `${formattedStartDate} - ${formattedEndDate}`;
//     }
//
//     return '';
// });
//
// const handleDate = () => {
//     // Handle date changes if needed
// };
//
// const formatDate = (date) => {
//     const year = date.getFullYear();
//     const month = (date.getMonth() + 1).toString().padStart(2, '0');
//     const day = date.getDate().toString().padStart(2, '0');
//     return `${year}-${month}-${day}`;
// };
//


</script>
<style>
.dp__input_wrap svg {
    margin-left: 11px;
}

.dp__input_icon_pad {
    padding: 8px 35px !important;
    border-radius: 5px !important;
}

.checkbox-padding {
    padding: 0.8rem !important
}

.w-max{
    width:max-content;
}
</style>
