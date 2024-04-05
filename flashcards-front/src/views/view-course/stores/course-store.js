import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';

export const useCourseStore = defineStore('view-course-store', () => {
    const course = ref( null )
    const isLoading = ref( false );
    const statusMessage = useStatusMessageService()

    const reportProgress = async ( page ) => {
        if ( course.value == null || page == null ) return false;

        try {
            await axios.post( `/api/courses/${course.value.id}/course_pages/${page.id}/progress` )
        }
        catch ( e ) {
            return false;
        }

        unlockNextPage( page )
    }

    const unlockNextPage = ( currentPage ) => {
        if ( course.value == null ) return false;

        let index = course.value.pages.findIndex( p => p.id == currentPage.id )
        
        if ( index == -1 ) return false;
        if ( index == course.value.pages.length - 1 ) return true;

        course.value.pages[index + 1].is_unlocked = true;
    }

    return { 
        course, 
        isLoading, 
        reportProgress,
    }
})
