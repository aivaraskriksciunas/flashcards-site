<script setup>
import { useRouter } from "vue-router"

const router = useRouter()
const props = defineProps({
    to: {
        default: null
    }
})

const emit = defineEmits([ 'click' ])

const onClick = () => {
    if ( props.to ) {
        router.push( props.to )
    }
    else emit( 'click' )
}

</script>

<template>
    <div @click="onClick" class="button">
        <slot></slot>
    </div>
</template>

<style scoped>
.button {
    background-color: var( --color-primary );
    border-radius: 1000px;
    display: inline-block;
    padding: 8px 18px;
    color: white;
    user-select: none;
}

.button:hover {
    background-color: var( --color-primary-active );
    cursor: pointer; 
}
</style>