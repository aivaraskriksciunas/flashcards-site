<script setup>
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Link } from 'lucide-vue-next'
import MemberSearchForm from '@/components/common/MemberSearchForm.vue';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { X } from 'lucide-vue-next'

const props = defineProps({
    course: {
        required: true,
        type: Object,
    }
})

const selectedUsers = ref([])

</script>

<template>
    <Dialog>
        <DialogTrigger>
            <Button><Link size="16" class="mr-2" />Invite</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Assign course</DialogTitle>
                <DialogDescription>
                    Choose members of your organization who should see this course.
                </DialogDescription>
            </DialogHeader>
            
            <div>
                <Badge v-for="selected of selectedUsers" 
                    @click="selectedUsers = selectedUsers.filter( u => u != selected )" 
                    class="cursor-pointer">
                    {{ selected.name }}
                    <X size="14" class="ml-1"/>
                </Badge>
            </div>

            <MemberSearchForm v-slot="{ member }">
                <Button v-if="selectedUsers.indexOf( member ) == -1" @click="() => selectedUsers.push( member )" variant="pill" size="sm">
                    Assign
                </Button>
                <div v-else class="italic">Selected</div>
            </MemberSearchForm>

            <DialogFooter>
                <Button>Save</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>