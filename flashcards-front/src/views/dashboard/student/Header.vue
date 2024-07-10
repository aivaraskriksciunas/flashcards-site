<script setup>
import Header from '../../../components/common/Header.vue'
import { 
    DropdownMenu, 
    DropdownMenuTrigger,
    DropdownMenuItem,
    DropdownMenuContent,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu'
import Button from '@/components/ui/button/Button.vue';
import { Plus, Book, Layers3 } from 'lucide-vue-next';
import DropdownMenuLabel from '@/components/ui/dropdown-menu/DropdownMenuLabel.vue';

</script>

<template>
    <Header>
        <template v-slot:actions>
            <div class="flex">
                <router-link :to="{ name: 'create-deck' }" class="hidden sm:block">
                    <Button class='mr-2' icon="fas fa-plus">
                        <Plus size="16" class="mr-2"/>
                        Create deck
                    </Button>
                </router-link>

                <DropdownMenu>
                    <DropdownMenuTrigger>
                        <Button variant="outline" class="mr-2 hidden lg:block">Import deck</Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <router-link :to="{name: 'import-anki'}">
                            <DropdownMenuItem>Anki</DropdownMenuItem>
                        </router-link>
                        <router-link :to="{name: 'import-wordlist'}">
                            <DropdownMenuItem>Word list</DropdownMenuItem>
                        </router-link>
                    </DropdownMenuContent>
                </DropdownMenu>

                <DropdownMenu>
                    <DropdownMenuTrigger>
                        <Button variant="" class="bg-transparent border border-border text-foreground hover:text-accent-foreground rounded-full hover:bg-accent" size="icon">
                            <Plus size="16"/>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                        <router-link :to="{name: 'create-deck'}">
                            <DropdownMenuItem>
                                <Layers3 size="16" class="mr-2"/>
                                New deck
                            </DropdownMenuItem>
                        </router-link>
                        <router-link :to="{name: 'create-course'}">
                            <DropdownMenuItem>
                                <Book size="16" class="mr-2"/>
                                New course
                            </DropdownMenuItem>
                        </router-link>

                        <DropdownMenuSeparator/>

                        <DropdownMenuLabel>Import deck</DropdownMenuLabel>
                        <router-link :to="{name: 'import-wordlist'}">
                            <DropdownMenuItem>From word list</DropdownMenuItem>
                        </router-link>
                        <router-link :to="{name: 'import-anki'}">
                            <DropdownMenuItem>From Anki</DropdownMenuItem>
                        </router-link>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </template>
    </Header>
</template>