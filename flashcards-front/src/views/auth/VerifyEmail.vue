<script setup>
import { useRoute } from 'vue-router';
import DataLoaderWrapper from '../../components/wrappers/DataLoaderWrapper.vue';

const route = useRoute();

const params = route.params;

</script>

<template>
<DataLoaderWrapper :url="`/api/verify/${params.verification_code}`">

    Your account has been verified. <router-link :to="{ name: 'home' }">Go to Aktulibre.</router-link>

    <template v-slot:error>
        Sorry, the provided code is not valid or has expired. <router-link :to="{ name: 'home' }">Go to Aktulibre.</router-link>
    </template>

</DataLoaderWrapper>
</template>