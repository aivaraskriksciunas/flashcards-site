<script setup>
import { ref } from "vue";
import Card from "./Card.vue";
import PlainButton from "./PlainButton.vue";

const props = defineProps({
    isLoading: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits([ 'cancel', 'success' ])

const isOpen = ref( false );

const openModal = () => {
    isOpen.value = true;
}

const closeModal = () => {
    isOpen.value = false;
}

defineExpose({
    openModal,
    closeModal,
})

</script>

<template>
    <div v-if="isOpen" class="modal-backdrop" @click.self="closeModal">
        <Card class="modal">
            <div class="modal-content">
                <slot></slot>
            </div>

            <div v-if="isLoading">Loading...</div>
            <div v-else class="modal-actions">
                <slot name="actions">
                    <PlainButton class="mr-2" @click="closeModal">Close</PlainButton>
                </slot>
            </div>
        </Card>
    </div>
</template>

<style>
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba( 0, 0, 0, 0.6 );
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal {
    width: 100%;
    max-width: 500px;
    margin: 0 16px;
}

.modal-content {
    margin-bottom: 16px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
</style>
