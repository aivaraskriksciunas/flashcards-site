<script setup>
import { Button } from '@/components/ui/button'
import { ref, computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { X } from 'lucide-vue-next'
import SlimContainer from '@/components/ui/SlimContainer.vue';
import Card from '@/components/ui/Card.vue';
import Header from '@/components/common/Header.vue';
import { DataTable } from '@/components/ui/datatable';
import DataTableHead from '@/components/ui/datatable/DataTableHead.vue';
import DataTableCell from '@/components/ui/datatable/DataTableCell.vue';
import AjaxForm from '@/components/forms/AjaxForm.vue';
import HiddenField from '@/components/forms/HiddenField.vue';
import { useRoute, useRouter } from 'vue-router';
import { MESSAGE_TYPE, useStatusMessageService } from '@/services/StatusMessageService';

const route = useRoute()
const router = useRouter()
const selectedUsers = ref({})
const selectedIds = computed( () => {
    return Object.values( selectedUsers.value ).map( u => u.id )
})

const { addStatusMessage } = useStatusMessageService()

const onSubmitted = ( data ) => {
    addStatusMessage( `${data.length} new members assigned to course.`, '', MESSAGE_TYPE.SUCCESS )
    router.push({ name: 'course-summary', params: { id: route.params.id }})
}

</script>

<template>
    
    <SlimContainer>
        <Header :level="2">Invite members</Header>

        <Card>
            <div>
                <Badge v-for="selected of selectedUsers" 
                    @click="delete selectedUsers[selected.id]" 
                    class="cursor-pointer mr-1 mb-1">
                    {{ selected.name }}
                    <X size="14" class="ml-1"/>
                </Badge>
            </div>

            <DataTable url="/api/organizations/members">
                <template #header>
                    <DataTableHead sortable="name">Name</DataTableHead>
                    <DataTableHead>Email</DataTableHead>
                    <DataTableHead>Invite</DataTableHead>
                </template>
                <template #row="{ row }">
                    <DataTableCell>{{ row.name }}</DataTableCell>
                    <DataTableCell>{{ row.email }}</DataTableCell>
                    <DataTableCell>
                        <Button v-if="selectedUsers[row.id] === undefined" 
                            @click="() => selectedUsers[row.id] = row" 
                            variant="pill" size="sm">
                            Assign
                        </Button> 
                        <span v-else class="italic">Selected</span>
                    </DataTableCell>
                </template>
            </DataTable>
            
            <AjaxForm 
                method="POST" 
                :action="`/api/courses/${route.params.id}/assign`" 
                @success="onSubmitted"
                :submit-text="`Assign ${selectedIds.length} members`">
                <HiddenField v-for='( id, index ) of selectedIds' :name="`user_ids.${index}`" :value="id"/>
                
                <template #actions>
                    <Button variant="secondary" @click="router.push({ name: 'course-summary', params: { id: route.params.id } })" class="ml-2">Back</Button>
                </template>
            </AjaxForm>

        </Card>
    </SlimContainer>

</template>