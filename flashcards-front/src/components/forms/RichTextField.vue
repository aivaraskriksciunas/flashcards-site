<script setup>
import { onMounted, ref } from 'vue'
import { useFormElement } from './composables/FormElement'
import FieldErrors from './_FieldErrors.vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

/**
 * Element Properties
 */
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        default: '',
    },
})

const emits = defineEmits([ 'change' ])
const { data, error } = useFormElement( props.name )

const editorContainer = ref( null )
let editor = null; // CKeditor instance

onMounted( () => {
    ClassicEditor.create( editorContainer.value, {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'underline', 'link', '|', 'numberedList', 'bulletedList', '|', 'table', 'undo', 'redo' ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph' },
                { model: 'heading1', view: 'h2', title: 'Heading 1' },
                { model: 'heading2', view: 'h3', title: 'Heading 2' },
            ]
        },
        initialData: props.value || '', 
    } )
    .then( obj => {
        editor = obj;

        editor.model.document.on( 'change', () => {
            data.value = editor.getData();
        })
    })

})

</script>

<template>
    <div class='form-group'>
        <label>
            <slot/>
        </label>

        <div class="editor-container rich-text-content mb-3">
            <div id="editorContainer" ref="editorContainer"></div>
        </div>

        <FieldErrors :errors="error"></FieldErrors>
    </div>
</template>

<style>

.editor-container {
    @apply mb-3;
    border: 1px solid rgb( var( --border ) );
    border-radius: 3px;
}

body {
    --ck-color-focus-border: rgb( var( --ring  ) );
    /* CK Toolbar */
    --ck-color-toolbar-background: rgb( var( --card ) );
    --ck-color-toolbar-border: rgb( var( --border ) ); 

    /* Editor */
    --ck-custom-foreground: rgb( var( --foreground ) );
    --ck-color-base-background: rgb( var( --background ) );
    --ck-color-base-border: rgb( var( --border ) );

    /* Buttons */
    --ck-color-button-default-hover-background: rgb( var( --accent ) );
    --ck-color-text: rgb( var( --foreground ) );
    --ck-color-button-default-active-background: rgb( var( --primary ) / 50% );

    --ck-color-button-on-background: rgb( var( --primry ) );
    --ck-color-button-on-hover-background: rgb( var( --accent ) );
    --ck-color-button-on-color: rgb( var( --primary ) );
    --ck-color-button-on-background: rgb( var( --shadow ) );
    --ck-color-button-on-active-background: rgb( var( --accent ) / 80% );

    --ck-color-button-action-background: rgb( var( --primary ) );

    --ck-color-button-save: rgb( var( --primary ) );
    --ck-color-button-cancel: rgb( var( --destructive ) );

    /* Dropdown */
    --ck-color-dropdown-panel-background: rgb( var( --card ) );
    --ck-color-dropdown-panel-border: rgb( var( --border ) );
    --ck-color-list-background: rgb( var( --card ) );
    --ck-color-list-button-hover-background: rgb( var( --accent ) );
    --ck-color-list-button-on-background: rgb( var( --primary ) );
    --ck-color-list-button-on-background-focus: rgb( var( --primary-hover ) );
    --ck-color-list-button-on-text: rgb( var( --primary-foreground ) );

    /* Balloon panel */
    --ck-color-panel-background: rgb( var( --card ) );
    --ck-color-panel-border: rgb( var( --border ) );

    /* Input */
    --ck-color-input-background: rgb( var( --input ) );
    --ck-color-input-border: rgb( var( --border ) );
    --ck-color-input-text: rgb( var( --input-foreground ) );
    --ck-color-input-disabled-background: rgb( var( --muted ) );
    --ck-color-input-disabled-border: rgb( var( --border ) );
    --ck-color-input-disabled-text: rgb( var( --muted-foreground ) );
    --ck-color-labeled-field-label-background: var( --ck-color-input-background );

    /* Other */
    --ck-powered-by-background: rgb( var( --background ) );
    --ck-powered-by-text-color: rgb( var( --foreground ) );
}

.ck-icon {
    color: rgb( var( --foreground ) );
}
</style>