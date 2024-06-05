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

<div class="status-message-container">
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
</div>
</template>

<style scoped>
.status-message-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    max-width: 400px;
    width: 100%;
}

.status-message {
    margin-top: 16px 0;
    border-width: 1px;
    border: none;
    border-bottom-width: 4px;
    border-bottom-style: solid;
    padding: 1.4em 1.5em;
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