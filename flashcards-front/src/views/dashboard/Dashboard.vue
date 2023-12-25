<script setup>
import { useUserStore } from '../../stores/user';
import { defineAsyncComponent } from 'vue';

const OrganizationDashboard = defineAsyncComponent(() => 
    import( './dashboards/OrganizationDashboard.vue' )
)

const StudentDashboard = defineAsyncComponent(() => 
    import( './dashboards/StudentDashboard.vue' )
)

const { user } = useUserStore()

</script>

<template>
    <div v-if="user.account_type === 'orgadmin' && user.organization">
        <OrganizationDashboard></OrganizationDashboard>
    </div>

    <div v-else-if="user.account_type === 'student' || user.account_type === 'admin'">
        <StudentDashboard></StudentDashboard>
    </div>

</template>