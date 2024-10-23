import {defineStore} from "pinia";
import axios from 'axios'

export const usePolicyStore = defineStore('policyStore', {
    state: () => ({
        tramsAndCondition: null,
        paymentPolicy: null,
        paymentMethods: null,

        /*
        paymentPolicy: '** 60% advance payment with work order.' +
            '\n** 20% due payment within 3 days after delivery.' +
            '\n** 20% due payment within 3 days after final delivery.'+
            '\n** All payments will go to the “Creative Tech Park” account via account pay cheque or bank deposit or online transfer or merchant mobile banking account paid by client.'
        ,*/
        /*        servicePolicy: '** Client must be provided all information/ content/ images with work order before starting the project.' +
                    '\n** Creative Tech Park is not liable for any illegal/ copyrighted content uploaded to the website.' +
                    '\n** If the Client failed to provide the content within 30 days after the contract, the project will count as a complete project.'+
                    '\n** If the Client failed to provide revisions within 15 days after the Delivery Timeline, the project will consider as a fully delivered.'+
                    '\n** Free 2 month small updating. (basic updating without any design work)'
                ,*/


        /*        paymentMethods:'Pay Direct to Our Corporate Bank Account' +
                    '\nBank Name: EASTERN BANK LTD' +
                    '\nAccount Name: CREATIVE TECH PARK' +
                    '\nAccount No: 1241070056170' +

                    '\n\n' +
                    '\nBank Name: THE CITY BANK LTD' +
                    '\nAccount Name: CREATIVE TECH PARK' +
                    '\nAccount No: 1502467424001' +

                    '\n\n'+
                    '\nBank Name: DUTCH BANGLA BANK LTD' +
                    '\nAccount Name: CREATIVE TECH PARK' +
                    '\nAccount No: 246.110.0005044' +

                    '\n\n' +
                    '\nBank Name: PREMIER BANK LTD' +
                    '\nAccount Name: CREATIVE TECH PARK' +
                    '\nAccount No: 13611100000331' +

                    '\n\n' +
                    '\nBank Name: EASTERN BANK LTD' +
                    '\nAccount Name: CREATIVE TECH PARK' +
                    '\nAccount No: 1241070056170' +

                    '\n\n' +
                    'Pay Using Our Merchant Mobile Banking Account:' +
                    '\nBkash: 01639200002 (Payment)' +
                    '\n\nGo to Your bKash Mobile Menu by dialing *247#' +
                    '\nChoose "Payment"' +
                    '\nEnter Merchant bKash Account Number 01639200002' +
                    '\nEnter the amount (Invoice Amount)' +
                    '\nEnter a reference (Invoice No/ Your Name)' +
                    '\nEnter Counter Number 0' +
                    '\nNow enter your bKash Mobile Menu PIN to Confirm!' +
                    '\nDone! You will receive a confirmation message from bKash*'*/
    }),

    actions: {
        setServicePolicy() {
            axios.get('/admin/all-policy-settings').then((res) => {
                this.tramsAndCondition = res.data.trams_and_condition;
                this.paymentPolicy = res.data.payment_policy;
                this.paymentMethods = res.data.payment_methods;
            })
        }
    },


    getters: {
        getTramsAndCondition() {
            return this.tramsAndCondition
        },
        getPaymentPolicy() {
            return this.paymentPolicy
        },
        getPaymentMethods() {
            return this.paymentMethods
        }
    }


})
