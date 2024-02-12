import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';

export const useCourseStore = defineStore('course-store', () => {
    const course = ref( null )
    const isLoading = ref( false );
    const statusMessage = useStatusMessageService()

    const addCoursePage = ( page ) => {
        if ( course.value == null ) return;

        course.value.pages.push( page )
    }

    const movePage = ( from, to ) => {
        let pages = course.value.pages;
        if ( from >= pages.length ) return;

        let page = pages[from];
        pages.splice( from, 1 );
        pages.splice( to, 0, page );

        course.value.pages = pages;
    } 

    const refreshCourse = () => {
        if ( course.value == null ) return;

        isLoading.value = true;
        axios.get( `/api/courses/${course.value.id}` )
        .then( ( data ) => {
            course.value = data.data 
        })
        .catch( error => {
            statusMessage.addStatusMessage( 
                'Error getting course information.', 
                'There was an error getting updated course information from the server. We recommend refreshing the page to prevent futher data loss.', 
                MESSAGE_TYPE.ERROR 
            )
        })
        .finally(() => isLoading.value = false )
    }

    const persistPageOrder = () => {
        if ( course.value == null ) return;
        
        isLoading.value = true
        axios.post( 
            `/api/courses/${course.value.id}/course_pages/reorder`,
            {
                pages: course.value.pages.map( page => page.id )
            } 
        )
        .then( ( data ) => {
            course.value = data.data 
        })
        .catch( error => {
            statusMessage.addStatusMessage( 
                'Error reordering page courses.', 
                'We could not save your reordered pages. Please check your internet connection and try again.', 
                MESSAGE_TYPE.ERROR 
            )
        })
        .finally( () => isLoading.value = false )
    }

    return { 
        course, 
        isLoading, 
        addCoursePage, 
        refreshCourse,
        movePage,
        persistPageOrder,
    }
})
