<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { useRoute, useRouter } from 'vue-router';
import { ref, computed } from 'vue';
import SlimContainer from '@/components/ui/SlimContainer.vue';
import { ChevronsUpDown, PlaySquare, Lock } from 'lucide-vue-next';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible'
import CourseSummary from './components/CourseSummary.vue';
import { useCourseStore } from './stores/course-store';
import { storeToRefs } from 'pinia';

const route = useRoute()
const router = useRouter()
const store = useCourseStore();
const { course } = storeToRefs( store )

const onLoad = ( data ) => {
    course.value = data
}

const contentExpanded = ref( window.innerWidth >= 768 )

const goToPage = ( page ) => {
    if ( !page.is_unlocked ) return;
    router.push({ name: 'view-course-page', params: { page_id: page.id } })
}

const currentPageIndex = computed( () => {
    if ( route.params.page_id == null ) return -1;
    return course.value.pages.findIndex( p => p.id == route.params.page_id )
})

const nextPage = () => {
    let index = currentPageIndex.value;
    if ( index >= course.value.pages.length - 1 ) return;

    let nextPage = course.value.pages[index + 1]
    if ( !nextPage.is_unlocked ) return;
    router.push({ name: 'view-course-page', params: { page_id: nextPage.id } })
}

const nextPageUnlocked = computed( () => {
    let index = currentPageIndex.value;
    if ( index >= course.value.pages.length - 1 ) return false;
    
    return course.value.pages[index + 1].is_unlocked
})

const hasNextPage = computed( () => {
    return currentPageIndex.value < course.value.pages.length - 1
})

</script>

<template>

<Navbar/>

<div class="application-container">
    <DataLoaderWrapper :url="`/api/courses/${route.params.id}`" @load="onLoad">
        <div class="container flex">
            <div class="lg:w-2/12"></div>
            <div class="flex-grow">
                <h1 class="">{{ course.title }}</h1>
            </div>
        </div>
        
        <div class="container md:flex">
            <div class="md:w-3/12 lg:w-2/12 pr-4 mb-4">
                <Collapsible v-model:open="contentExpanded">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold">
                            Content
                        </h4>
                        <CollapsibleTrigger as-child>
                            <Button variant="ghost" size="sm" class="w-9 p-0">
                                <ChevronsUpDown class="h-4 w-4" />
                                <span class="sr-only">Expand content</span>
                            </Button>
                        </CollapsibleTrigger>
                    </div>
                    <CollapsibleContent>
                        <Button 
                            v-for="page of course.pages" 
                            :key="page.id" 
                            variant="ghost" 
                            class="flex items-center w-full justify-start"
                            :class="{ 'border border-border text-base text-primary': page.id == route.params.page_id }"
                            :disabled="!page.is_unlocked"
                            @click="() => goToPage( page )">
                            <PlaySquare v-if="page.is_unlocked" size="16" color="rgb( var( --muted-foreground ) )" class="flex-shrink-0 mr-2"/>
                            <Lock v-else size="16" color="rgb( var( --muted-foreground ) )" class="flex-shrink-0 mr-2"/>

                            <div class="text-left flex-shrink">
                                {{ page.title }}
                            </div>
                        </Button>
                    </CollapsibleContent>
                </Collapsible>
            </div>

            <div class="md:w-9/12 lg:w-10/12">
                <Card class="py-8">
                    <SlimContainer>
                        <router-view v-slot="{ Component }">
                            <component :is="Component" v-if="Component"></component>
                            <div v-else>
                                <CourseSummary :course="course"/>
                            </div>
                        </router-view>
                    </SlimContainer>
                </Card>
                <div class="flex mt-2" v-if="currentPageIndex == -1">
                    <Button class="w-full" 
                        @click="nextPage">
                        Begin course
                    </Button>
                </div>
                
                <div class="flex mt-2" v-if="currentPageIndex >= 0 && hasNextPage">
                    <Button variant="secondary">
                        Previous
                    </Button>
                    <div class="flex-grow"></div>
                    <Button 
                        size="lg" 
                        @click="nextPage" 
                        :disabled='!nextPageUnlocked'>
                        Next page
                    </Button>
                </div>

                <div class="flex mt-2" v-if="!hasNextPage">
                    <Button class="w-full" @click="router.push({ name: 'home' })">
                        Finish course
                    </Button>
                </div>
            </div>
        </div>
        
    </DataLoaderWrapper>
</div>

<Footer/>

</template>

<style>
.application-container {
    min-height: 100vh;
    background-color: rgb( var( --page-background ) );
}
</style>