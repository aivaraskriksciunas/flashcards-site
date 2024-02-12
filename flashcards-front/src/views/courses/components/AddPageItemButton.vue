<script setup>

import { Button } from '@/components/ui/button';
import { Plus, Pilcrow } from 'lucide-vue-next';
import {
    DropdownMenu, 
    DropdownMenuItem, 
    DropdownMenuTrigger,
    DropdownMenuContent,
} from '@/components/ui/dropdown-menu';
import { usePageStore, COURSE_PAGE_TYPE } from '../stores/page-store';
import { storeToRefs } from 'pinia';

const store = usePageStore()
const { page } = storeToRefs( store )

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <Button>
                <Plus size="16" class="mr-2"/>
                Add Item
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuItem @click="store.addBlankPage( COURSE_PAGE_TYPE.paragraph )">
                <Pilcrow size="16" class="mr-2"/>
                Text
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>