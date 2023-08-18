import { defineStore } from "pinia";
import { ref } from 'vue';

export const MESSAGE_TYPE = {
    SUCCESS: 0,
    ERROR: 1,
    INFO: 2,
}

export const useStatusMessageService = defineStore( 'status-messages', () => {

    const statusMessages = ref([]);
    let id = 0;

    const addStatusMessage = ( title, message, type, timeout ) => {
        if ( type == null ) type = MESSAGE_TYPE.ERROR;
        if ( timeout == null ) timeout = 10000;

        let messageObj = {
            id, title, message, type
        };

        statusMessages.value.push( messageObj );

        if ( timeout !== 0 ) {
            setTimeout( () => removeStatusMessage( messageObj.id ), timeout )
        }

        id += 1;
        return messageObj.id;
    }

    const removeStatusMessage = ( id ) => {
        statusMessages.value = statusMessages.value.filter( m => m.id != id )
    }

    return { statusMessages, addStatusMessage, removeStatusMessage }

})
