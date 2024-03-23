<script setup>
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/button/Button.vue';
import { useRoute, useRouter } from 'vue-router';
import { ref } from 'vue';
import SlimContainer from '@/components/ui/SlimContainer.vue';
import { ChevronsUpDown, PlaySquare } from 'lucide-vue-next';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible'
import CourseSummary from './components/CourseSummary.vue';

const route = useRoute()
const router = useRouter()
const course = ref( null )
const onLoad = ( data ) => {
    course.value = data
}

const contentExpanded = ref( window.innerWidth >= 768 )

const currentPage = ref( -1 )
const nextPage = () => {

}

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
                        <router-link 
                            v-for="page of course.pages" 
                            :key="page.id" 
                            :to="{ name: 'view-course-page', params: { page_id: page.id } }">
                            <Button  variant="ghost" class="w-full ">
                                <div class="text-left w-full flex items-center">
                                    <PlaySquare size="16" color="rgb( var( --muted-foreground ) )" class="mr-2"/>
                                    {{ page.title }}
                                </div>
                            </Button>
                        </router-link>
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
                <div class="flex mt-2">
                    <Button class="w-full" @click="nextPage">Begin course</Button>
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