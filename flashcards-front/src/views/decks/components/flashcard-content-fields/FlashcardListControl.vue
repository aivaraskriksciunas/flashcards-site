<script setup>
import TextField from '@/components/forms/TextField.vue';
import { Button } from '@/components/ui/button';
import { ref, watch } from 'vue';
import PlainIconButton from '@/components/ui/PlainIconButton.vue';
import { X, Plus } from 'lucide-vue-next'

const MAX_LIST_ITEMS = 5;
const list = ref([]);
const model = defineModel()

// Try to parse JSON from given initial value
try {
    const initialValue = JSON.parse( model.value );
    if ( Array.isArray( initialValue ) ) {
        list.value = initialValue
            .filter( val => typeof val == 'string' )
            .map( val => ({ value: val, key: Symbol() }))
    }
}
catch ( e ) {}

// Listen to changes to the list
watch( list, ( val, oldVal ) => {
    model.value = JSON.stringify(
        val.map( val => val.value )
    )
}, { deep: true })

const addItem = () => {
    if ( list.value.length >= MAX_LIST_ITEMS ) return;
    list.value.push({ value: '', key: Symbol() })
}

if ( list.value.length == 0 ) addItem()

</script>

<template>
    <div class="flex items-center" v-for="( item, index ) of list" :key="item.key">
        <TextField 
            class="flex-1 mr-1" 
            placeholder="Enter text here"
            name="" 
            :value="item.value" 
            @change="val => list[index].value = val"></TextField>
        <PlainIconButton variant="destructive" class='mb-2' @click="list.splice( index, 1 )">
            <X/>
        </PlainIconButton>
    </div>
    <Button v-if="list.length < MAX_LIST_ITEMS" variant="ghost" @click="addItem" size="sm">
        <Plus class='mr-1 text-muted-foreground' size='16'/>Add item
    </Button>
</template>