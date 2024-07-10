<script setup>
import Card from '@/components/ui/Card.vue';
import { DataTable, DataTableCell, DataTableHead } from '@/components/ui/datatable';
import { 
    Dialog, 
    DialogTrigger, 
    DialogContent, 
    DialogHeader, 
    DialogTitle, 
    DialogDescription, 
DialogFooter,
DialogClose
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import Header from '@/components/common/Header.vue';
import { Link, Hourglass, ClipboardCheck, Pencil, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import EditCourseAccessLinkForm from './EditCourseAccessLinkForm.vue';
import truncate from 'lodash/truncate'
import { MESSAGE_TYPE, useStatusMessageService } from '@/services/StatusMessageService';
import { deleteAccessLink } from '@/services/CourseAccessLinkService';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';

const { course } = defineProps({
    course: {
        type: Object,
        required: true,
    }
})

const createModalOpen = ref( false )
const refreshKey = ref( 1 )
const messageService = useStatusMessageService()

const linkCopied = ref( false )

const getFullLink = ( link ) => {
    return window.location.origin + "/course/" + link;
}

const copyLink = ( link ) => {
    let url = getFullLink( link )
    navigator.clipboard.writeText( url )
    messageService.addStatusMessage( 'Link copied to clipboard', '', MESSAGE_TYPE.SUCCESS, 2000 )
    linkCopied.value = link

    setTimeout( () => linkCopied.value = false, 2000 )
}

const editModalOpen = ref( false )
const editingLink = ref( null )
const editLink = ( linkObj ) => {
    editingLink.value = linkObj 
    editModalOpen.value = true 
}

const deleteModalOpen = ref( false )
const deleteLinkLoading = ref( false )
const deleteLink = async ( linkId ) => {
    deleteLinkLoading.value = true 

    try {
        await deleteAccessLink( course.id, linkId )
    }
    catch ( e ) {
        messageService.addStatusMessage( 'Error deleting link', 'Could not delete your access link. Please try again later.', MESSAGE_TYPE.ERROR )
    }

    deleteLinkLoading.value = false 
    deleteModalOpen.value = false 
    refreshKey.value += 1
}

</script>

<template>

    <Card>
        <Header level="2">
            Access links

            <template #actions>
                <Dialog v-model:open="createModalOpen">
                    <DialogTrigger>
                        <Button size="sm" variant="outline">
                            <Link class="mr-2" size="16"/>New access link
                        </Button>
                    </DialogTrigger>

                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Create access link</DialogTitle>
                            <DialogDescription>
                                Manage who can view your course by creating an access link. 
                            </DialogDescription>
                        </DialogHeader>

                        <EditCourseAccessLinkForm :course="course" @submitted="createModalOpen = false; refreshKey++"/>
                    </DialogContent>
                </Dialog>
            </template>    
        </Header>

        <DataTable :url="`/api/courses/${course.id}/access-links`" :key="refreshKey">
            <template #header>
                <DataTableHead>Name</DataTableHead>
            </template>

            <template #row="{ row }">
                <DataTableCell>
                    <div>{{ truncate( row.name, { length: 40 } ) }}</div>
                    <div v-if="row.expires_at" class="expiration-date text-muted-foreground">
                        <Hourglass size="12" class="mr-1" />
                        {{ row.expires_at_human }}
                    </div>
                </DataTableCell>
                <DataTableCell class="text-right">
                    <Button variant="ghost" size="sm" @click="() => editLink( row )" class="mr-1">
                        <Pencil size="16"/>
                    </Button>
                    <Button variant="outline" size="sm" @click="() => copyLink( row.link )" :disabled="linkCopied == row.link" class="mr-1">
                        <Link v-if="linkCopied != row.link" size="16"/>
                        <ClipboardCheck v-else size="16"/>
                    </Button>
                    <Button variant="ghost" size="sm" @click="deleteModalOpen = true; editingLink = row">
                        <Trash2 class="text-destructive" size="16"/>
                    </Button>
                </DataTableCell>
            </template>
        </DataTable>

        <Dialog v-model:open="editModalOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Edit access link</DialogTitle>
                    <DialogDescription>
                        <div class="access-link-url-container">
                            <div class="access-link-url">
                                {{ getFullLink( editingLink.link )}}
                            </div>
                            <Button variant="ghost" @click="() => copyLink( editingLink.link )" :disabled="linkCopied == editingLink.link">
                                <span v-if="linkCopied == editingLink.link" class="flex items-center">
                                    <ClipboardCheck size="16" class="mr-1"/> Copied
                                </span>
                                <span v-else>Copy</span>
                            </Button>
                        </div>
                    </DialogDescription>
                </DialogHeader>

                <EditCourseAccessLinkForm :course="course" :access-link="editingLink" @submitted="editModalOpen = false; refreshKey++"/>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="deleteModalOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Are you sure you want to delete this link?</DialogTitle>
                    <DialogDescription>
                        People will no longer be able to access your course with this link. This action is non reversible. 
                    </DialogDescription>
                </DialogHeader>

                <div class="access-link-url-container">
                    <div class="access-link-url">
                        {{ getFullLink( editingLink.link )}}
                    </div>
                </div>

                <DialogFooter v-if="deleteLinkLoading">
                    <LoadingIndicator/>
                </DialogFooter>
                <DialogFooter v-else>
                    <DialogClose>
                        <Button variant="ghost">Cancel</Button>
                    </DialogClose>
                    <Button variant="destructive" @click="() => deleteLink( editingLink.id )">Yes, delete</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Card>
</template>

<style scoped>
.expiration-date {
    font-size: 0.95em;
    display: flex;
    align-items: center;
    margin-top: 2px;
    font-weight: 300;
}

.access-link-url-container {
    border: 1px solid rgb( var( --border ) );
    border-radius: 3px;
    padding: 8px 12px;
    display: flex;
}

.access-link-url {
    flex-grow: 1;
}
</style>