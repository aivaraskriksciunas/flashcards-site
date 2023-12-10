<script setup>
import { useStatusMessageService, MESSAGE_TYPE } from '../../services/StatusMessageService.js';
import Card from '../ui/Card.vue';
import CloseIcon from '../icons/CloseIcon.vue';

const statusMessages = useStatusMessageService();

const getMessageStyleClass = ( message ) => {
    if ( message.type == MESSAGE_TYPE.ERROR )
        return 'status-message-error';
    if ( message.type == MESSAGE_TYPE.SUCCESS ) 
        return 'status-message-success';
    
    return 'status-message-info';
}

</script>

<template>

<TransitionGroup name="status-messages">
    <Card v-for="message of statusMessages.statusMessages" :key="message.id"
        class="status-message" :class="[ getMessageStyleClass( message ) ]">
        <div class="flex items-center">
            <div class="flex-1">
                <div class='title'>{{ message.title }}</div>
                <div class='message'>{{ message.message }}</div>
            </div>
            <div>
                <CloseIcon class="close-icon" @click="statusMessages.removeStatusMessage( message.id )"></CloseIcon>
            </div>
        </div>
        
    </Card>
</TransitionGroup>


</template>

<style scoped>
.status-message {
    margin: 8px 0;
    border-bottom-width: 4px;
    border-bottom-style: solid;
}

.status-message .title {
    font-weight: 600;
    font-size: 1.1em;
    margin-bottom: 2px;
}

.status-message .message {
    font-weight: 300;
}

.status-message-success {
    border-bottom-color: var( --color-primary );
}

.status-message-success .title {
    color: var( --color-primary );
}

.status-message-error {
    border-bottom-color: var( --color-danger );
}

.status-message-error .title {
    color: var( --color-danger );
}

.close-icon {
    height: 24px;
}


.status-messages-enter-active {
    transition: all 0.5s ease;
}

.status-messages-leave-active {
    transition: all 0.2s ease;
}

.status-messages-enter-from,
.status-messages-leave-to {
    opacity: 0;
    transform: translateX( 30px );
}
</style>