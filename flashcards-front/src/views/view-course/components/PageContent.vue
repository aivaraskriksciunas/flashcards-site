<script setup>
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextareaField from '@/components/forms/TextareaField.vue';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import RichTextContent from '@/components/ui/rich-text-content/RichTextContent.vue';
import { Pencil, X, Info } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps({
    page: {
        required: true,
        type: Object
    }
})

const notePopover = ref( null )
const notePopoverOpen = ref( false )
const notePopoverQuestion = ref( '' ) 

const onMouseUp = ( event ) => {
    notePopoverOpen.value = false;
    let selection = document.getSelection().toString()
    if ( !selection ) return;

    notePopoverQuestion.value = selection
    
    notePopoverOpen.value = true
    notePopover.value.style.top = event.layerY + 20 + 'px'
    notePopover.value.style.left = event.layerX + 20 + 'px'
}

onMounted( () => {
    document.addEventListener( 'mouseup', function( event ) {
        if ( !notePopover.value ) return;

        if ( !notePopover.value.contains( event.target ) ) {
            notePopoverOpen.value = false;
        }
    });
})

</script>

<template>
    <div v-for="item of props.page.items">
        <div >
            <RichTextContent :content="item.content" @mouseup.stop="onMouseUp"/>
        </div>
    </div>

    <div class="note-popover transition-all animate-in fade-in slide-in-from-top-4" 
        :class="{ 'hidden': !notePopoverOpen }" 
        ref="notePopover">
        <div class="note-popover-header">
            <Pencil class="inline mr-2" size="16" />
            New card
            <div class="ml-auto">
                <Popover>
                    <PopoverTrigger>
                        <Button variant="ghost" class="p2">
                            <Info size="16"/>
                        </Button>
                    </PopoverTrigger>
                    <PopoverContent>
                        Turn your highlighted text into a note card with a custom question and answer, and build your study deck as you go.
                    </PopoverContent>
                </Popover>
                <Button variant="ghost" class="p-2" @click="notePopoverOpen = false">
                    <X size="16"/>
                </Button>
            </div>
        </div>
        <div class="note-popover-content p-4">
            <AjaxForm action="/api/notes" clear-on-submit>
                <TextareaField name="question" v-model="notePopoverQuestion" class="text-sm" autogrow>Question</TextareaField>
                <TextareaField name="answer">Answer</TextareaField>

                <template #submit>
                    <Button type="submit" @click="notePopoverOpen = false">Save card</Button>
                </template>
            </AjaxForm>
        </div>
    </div>
</template>

<style scoped>
.note-popover {
    position: absolute;
    background-color: rgb( var( --background ) );
    border: 1px solid rgb( var( --border ) );
    border-radius: 5px;
    min-width: 180px;
    max-width: 25rem;
    box-shadow: 0 2px 8px rgb( var( --shadow ) );
}

.note-popover-header {
    padding: 8px 16px;
    border-bottom: 1px solid rgb( var( --border ) );
    display: flex;
    align-items: center;
}
</style>