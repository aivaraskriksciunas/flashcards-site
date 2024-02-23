import { defineStore } from "pinia"
import { ref } from "vue"

export const useMemberStore = defineStore( 'org-member-store', () => {

    const members = ref([]);
    const invitations = ref([]);

    const setMembers = ( data ) => {
        members.value = data
    }

    const setInvitations = ( data ) => {
        invitations.value = data
    }

    const addInvitation = ( invitation ) => {
        invitations.value = [ invitation, ...invitations.value ]
    }

    return {
        members,
        invitations,
        setMembers,
        setInvitations,
        addInvitation,
    }
})