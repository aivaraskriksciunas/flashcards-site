<script setup>
import Header from '../../../components/common/Header.vue'
import { useUserStore } from '../../../stores/user';
import CreateMenu from '../shared/CreateMenu.vue';

const user = useUserStore().user;

</script>

<template>
    <Header>
        {{ user.organization.name }}
        <template v-slot:actions>
            <div class="flex">
                
                <CreateMenu/>
            </div>
        </template>
    </Header>
</template>