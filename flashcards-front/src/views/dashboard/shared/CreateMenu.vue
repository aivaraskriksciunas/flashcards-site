<script setup>
import Button from '@/components/ui/button/Button.vue';
import { Plus, Book, Layers3 } from 'lucide-vue-next';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger  } from '@/components/ui/dropdown-menu';

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <Button class='mr-2' icon="fas fa-plus">
                <Plus size="16" class="mr-2"/>
                Create
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent>
            <router-link :to="{ name: 'create-course' }">
                <DropdownMenuItem class="text-foreground">
                    <Book class="mr-2 text-muted-foreground" size="16"/>
                    Course
                </DropdownMenuItem>
            </router-link>
            <router-link :to="{ name: 'create-deck' }">
                <DropdownMenuItem class="text-foreground">
                    <Layers3 class="mr-2 text-muted-foreground" size="16" />
                    Deck
                </DropdownMenuItem>
            </router-link>
            
        </DropdownMenuContent>
    </DropdownMenu>
</template>