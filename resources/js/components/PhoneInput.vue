<template>
    <div class="phone-input">
        <input ref="phoneInput" class="form-control" :value="modelValue"/>
        <p v-if="error">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps, defineEmits, watch } from 'vue';
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';
import axios from "axios";
const props = defineProps({
    modelValue: String,
    id: {
        type: String,
        default: 'phone',
    }
});

const emit = defineEmits(['update:modelValue', 'validationError', 'countryData']);

const phoneInput = ref(null);
const iti = ref(null);
const error = ref('');

onMounted(() => {
    iti.value = intlTelInput(phoneInput.value, {
        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js',
        initialCountry: 'bd',
        containerClass: 'w-100',
    });

    phoneInput.value.addEventListener('blur', () => {
        validatePhoneNumber();
    });

});

const validatePhoneNumber = async () => {
    if (phoneInput.value.value?.trim()) {
        if (iti.value.isValidNumber()) {
            try {
                const response = await axios.get('/check-phone-unique', {
                    params: {phone: iti.value.getNumber()}
                });

                if (response.data.unique) {
                    const countryData = iti.value.getSelectedCountryData();
                    emit('update:modelValue', iti.value.getNumber());
                    emit('countryData', countryData);
                    error.value = '';
                } else {
                    error.value = 'Phone number is already taken';
                    // emit('update:modelValue', null);
                    emit('validationError', error.value);
                }
            } catch (e) {
                console.log(e)
                error.value = 'Error checking phone number';
                // emit('update:modelValue', null);
                emit('validationError', error.value);
            }

        } else {
            error.value = 'Invalid Phone Number';
            // emit('update:modelValue', null);

            emit('validationError', error.value);
        }
    }
};

watch(() => props.modelValue, (newValue) => {
    phoneInput.value.value = newValue;
});
</script>

<style scoped>
p {
    color: red;
    margin-top: 0.5rem;
}
</style>
