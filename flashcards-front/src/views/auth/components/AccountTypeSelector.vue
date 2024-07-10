<script setup>
import PlainButton from '@/components/ui/PlainButton.vue';
import { UserRound, Building2, School } from 'lucide-vue-next';

const emit = defineEmits([ 'selected' ])

</script>

<template>

    <PlainButton @click="emit( 'selected', 'student' )">
        <div class="flex account-type-btn">
            <div class="account-type-btn__icon">
                <UserRound/>
            </div>
            <div class="account-type-btn__text">
                Student
            </div>
        </div>
    </PlainButton>
    
    <PlainButton @click="emit( 'selected', 'orgadmin' )">
        <div class="flex account-type-btn">
            <div class="account-type-btn__icon">
                <Building2 />
            </div>
            <div class="account-type-btn__text">
                Organization
            </div>
        </div>
    </PlainButton>

</template>


<style scoped>

.account-type-btn__icon {
    @apply p-2 mr-2;
}

.account-type-btn__text {
    display: flex;
    align-items: center;
}

</style>