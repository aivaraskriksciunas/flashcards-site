<script setup>
import { useRoute, useRouter } from 'vue-router';
import Header from '@/components/common/Header.vue'
import DataLoaderWrapper from '@/components/wrappers/DataLoaderWrapper.vue';
import CourseLayout from './components/CourseLayout.vue';
import { useCourseStore } from './stores/course-store';
import { storeToRefs } from 'pinia';
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet'
import { Button } from '@/components/ui/button';
import { Settings } from 'lucide-vue-next';
import { ref } from 'vue';
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import SelectField from '@/components/forms/SelectField.vue';
import { useUserStore } from '@/stores/user';
import CheckboxField from '@/components/forms/CheckboxField.vue';
import { MESSAGE_TYPE, useStatusMessageService } from '@/services/StatusMessageService';

const router = useRouter();
const route = useRoute();

const { course } = storeToRefs( useCourseStore() );
const { user } = useUserStore();
const messages = useStatusMessageService()

const onLoad = ( data ) => { course.value = data }

const settingsSheetOpen = ref( false )

let visibilityChoices = [
    { label: 'Private', value: 'private' },
    { label: 'Public', value: 'public' },
];

// Show more visibility choices to organization users
if ( user.organization ) {
    visibilityChoices = [
        { label: 'Private', value: 'private' },
        { label: 'Administrators only', value: 'orgadmin' },
        { label: 'Managers only', value: 'orgmanager' },
        { label: 'Everyone in organization', value: 'orgall' },
        { label: 'Public', value: 'public' },
    ];
}

const onSuccess = ( updatedCourse ) => {
    course.value = { ...course.value, ...updatedCourse }
    messages.addStatusMessage( 'Course settings updated', null, MESSAGE_TYPE.SUCCESS )
    settingsSheetOpen.value = false
}

</script>

<template>
    <DataLoaderWrapper :url="`/api/courses/${route.params.id}`" @load="onLoad">
        <Header>
            {{ course.title }}

            <template #actions>
                <Sheet v-model:open="settingsSheetOpen">
                    <SheetTrigger as-child>
                        <Button variant="outline">
                            <Settings size="16" class="mr-2"/>
                            Settings
                        </Button>
                    </SheetTrigger>
                    <SheetContent>
                        <AjaxForm :action="`/api/courses/${route.params.id}`" method="PATCH" @success="onSuccess">
                            <SheetHeader>
                                <SheetTitle>Course parameters</SheetTitle>
                            </SheetHeader>

                            <TextField name="title" :value="course.title">Rename</TextField>
                            <SelectField
                                :choices="visibilityChoices" name="visibility" :value="course.visibility">
                                Course visibility    
                            </SelectField>
                            <CheckboxField name="is_unlocked" :value="course.is_unlocked">
                                Is unlocked
                            </CheckboxField>
                            
                        </AjaxForm>
                    </SheetContent>
                </Sheet>
            </template>
        </Header>

        <div class="flex">
            <div class="w-64" id="courseLayoutContainer">
                <CourseLayout/>
            </div>

            <div class="w-full pl-4">
                <router-view></router-view>
            </div>
        </div>
    </DataLoaderWrapper>
</template>

<style scoped>
#courseLayoutContainer {
    border-right: 1px solid rgb( var( --muted ) );
}
</style>