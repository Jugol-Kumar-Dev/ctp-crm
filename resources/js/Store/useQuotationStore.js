import {defineStore} from "pinia";
import moment from "moment";
import {router} from "@inertiajs/vue3";


export const useQuotationStore = defineStore('quotation', {
    state:()=>({
        quotationId:moment(new Date()).format('YYYYMMD'),
        clientId:null,
        date:null,
        subject:null,
        users:null,
    }),


    actions:{
        setClientId(clientId){
            this.clientId = clientId;
        },
        setQutDate(date){
            this.date = date;
        },
        setSubject(subject){
            this.subject = subject;
        },
        setQuotId(qutId){
            this.quotationId = qutId;
        },
        setUsers(){
            axios.get('/admin/users/all-users').then((res) => this.users = res.data).catch((err) => '')
        }
    },

    getters:{
        getQuotId(){
            return this.quotationId;
        },
        getClientId(){
            return this.clientId;
        },
        getQutDate(){
            return this.date;
        },
        getSubject(){
            return this.subject;
        },
        getUsers(){
            return this.users;
        }

    }

})
