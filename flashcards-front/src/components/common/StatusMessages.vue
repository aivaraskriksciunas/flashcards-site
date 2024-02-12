<script setup>
import { useStatusMessageService, MESSAGE_TYPE } from '../../services/StatusMessageService.js';
import Card from '../ui/Card.vue';
import { X } from 'lucide-vue-next';

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
                <X @click="statusMessages.removeStatusMessage( message.id )"/>
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
    border-bottom-color: rgb( var( --primary ) );
}

.status-message-success .title {
    color: rgb( var( --primary ) );
}

.status-message-error {
    border-bottom-color: rgb( var( --destructive ) );
}

.status-message-error .title {
    color: rgb( var( --destructive ) );
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