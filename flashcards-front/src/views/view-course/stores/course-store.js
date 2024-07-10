import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';

export const useCourseStore = defineStore('view-course-store', () => {
    const course = ref( null )
    const isLoading = ref( false );
    const sessionId = ref( null );
    const sessionLoaded = ref( false );
    const statusMessage = useStatusMessageService()

    const startSession = async ( access_link ) => {
        if ( course.value == null ) return false;

        try {
            let res = await axios.get( `/api/courses/view/${access_link}/session` )
            sessionId.value = res.data['id'];
        }
        catch ( e ) {
            return false;
        }
    }

    const reportProgress = async ( access_link, page ) => {
        if ( sessionLoaded.value === false ) {
            await startSession( access_link )
        }

        if ( course.value == null || page == null || sessionId.value == null ) return false;

        try {
            await axios.post( `/api/courses/view/${access_link}/progress/${sessionId.value}/${page.id}` )
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
        startSession,
    }
})
