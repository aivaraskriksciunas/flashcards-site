<script setup>
import Header from '@/components/common/Header.vue';
import MemberList from './components/MemberList.vue';
import Card from '@/components/ui/Card.vue';
import InvitationForm from './components/InvitationForm.vue';
import InvitationList from './components/InvitationList.vue';

</script>

<template>

    <div class="md:flex">
        <div class="md:w-1/2 p-4">
            <Header :level="2">Members</Header>
            <Card class="mb-4">
                <MemberList/>
            </Card>

            <Header :level="2">Pending invitations</Header>
            <Card class="mb-4">
                <InvitationList/>
            </Card>
        </div>
        <div class="md:w-1/2 p-4">
            <Header :level="2">Invite member</Header>
            <Card>
                <InvitationForm/>
            </Card>
        </div>
    </div>
    
</template>