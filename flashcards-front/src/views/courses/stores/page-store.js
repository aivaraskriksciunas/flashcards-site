import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';
import { useStatusMessageService, MESSAGE_TYPE } from '@/services/StatusMessageService';

export const COURSE_PAGE_TYPE = {
    paragraph: 'paragraph'
}

export const usePageStore = defineStore('page-store', () => {
    const page = ref( null )
    const isLoading = ref( false );
    const statusMessage = useStatusMessageService()

    const setPage = ( val ) => {
        page.value = val
    }

    const addBlankPage = ( pageType ) => {
        if ( page.value == null ) return;

        page.value.items.push({ 
            type: pageType,
            // Empty pages will not have an id, so timestamp will serve as a temporary key for the DOM
            _timestamp: (new Date()).getTime(),
        })
    }

    const movePageItem = ( from, to ) => {
        let items = page.value.items;
        if ( from >= items.length ) return;

        let item = items[from];
        items.splice( from, 1 );
        items.splice( to, 0, item );

        page.value.items = items;
    } 

    const refreshPage = () => {
        if ( page.value == null ) return;

        isLoading.value = true;
        axios.get( `/api/courses/${page.value.course_id}/course_pages/${page.value.id}` )
        .then( ( data ) => {
            page.value = data.data 
        })
        .catch( () => {
            statusMessage.addStatusMessage( "Could not get page information.", "There was an error while retrieving the page information. Please refresh the page and try again.", MESSAGE_TYPE.ERROR )
        })
        .finally( () => {
            isLoading.value = false;
        })
    }

    const deletePageItem = ( page_id ) => {
        if ( page.value == null ) return;

        isLoading.value = true;
        axios.delete( `/api/courses/${page.value.course_id}/course_pages/${page.value.id}/course_page_items/${page_id}` )
        .then( ( data ) => {
            page.value.items = page.value.items.filter( item => item.id !== page_id )
        })
        .catch( () => {
            statusMessage.addStatusMessage( "Could not delete page item.", "There was an error while deleting the page item. Please refresh the page and try again.", MESSAGE_TYPE.ERROR )
        })
        .finally( () => {
            isLoading.value = false;
        })
    }

    const persistPageItemOrder = () => {
        if ( page.value == null ) return;

        let oldOrder = page.value.items;
        axios.post( `/api/courses/${page.value.course_id}/course_pages/${page.value.id}/course_page_items/reorder`, {
            items: page.value.items.map( item => item.id ),
        } )
        .then( ( data ) => {
            page.value = data.data
        })
        .catch( () => {
            statusMessage.addStatusMessage( "Could not reorder the page items", "There was an error while reordering the elements. Please refresh the page and try again.", MESSAGE_TYPE.ERROR )
            page.value.items = oldOrder
        })
    }

    return { 
        page,
        setPage,
        movePageItem,
        isLoading,
        addBlankPage,
        refreshPage,
        deletePageItem,
        persistPageItemOrder,
    }
})
