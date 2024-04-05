<script setup>
import { storeToRefs } from 'pinia';
import { useUserStore } from '../../stores/user';
import { defineAsyncComponent, ref } from 'vue';
import DeckList from './shared/DeckList.vue';
import AssignedCourseList from './shared/AssignedCourseList.vue';
import NoteList from './shared/NoteList.vue';
import UserCourseList from './shared/UserCourseList.vue';
import { 
    Tabs, 
    TabsContent, 
    TabsList, 
    TabsTrigger 
} from '@/components/ui/tabs'

const { user } = storeToRefs( useUserStore() )
const dashboard = ref( 'student' )
if ( user.value.organization ) {
    dashboard.value = 'organization'
}

const Header = defineAsyncComponent(() => 
    import( `./${dashboard.value}/Header.vue`)
)

</script>

<template>
    
    <Header/>

    <div class="xl:w-3/4">
        <AssignedCourseList/>
        
        <Tabs default-value="decks">
            <TabsList>
                <TabsTrigger value="decks">
                    Decks
                </TabsTrigger>
                <TabsTrigger value="courses">
                    Courses
                </TabsTrigger>
            </TabsList>
            <TabsContent value="decks">
                <NoteList/>
                <DeckList></DeckList>
            </TabsContent>
            <TabsContent value="courses">
                <UserCourseList/>
            </TabsContent>
        </Tabs>
    </div>


</template>