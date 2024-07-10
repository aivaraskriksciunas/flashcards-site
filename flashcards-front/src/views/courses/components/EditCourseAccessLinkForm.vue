<script setup>
import AjaxForm from '@/components/forms/AjaxForm.vue';
import TextField from '@/components/forms/TextField.vue';
import CheckboxField from '@/components/forms/CheckboxField.vue';
import DatePickerField from '@/components/forms/DatePickerField.vue';
import { HelpIcon } from '@/components/ui/help-icon';
import { computed, ref } from 'vue';
import HiddenField from '@/components/forms/HiddenField.vue';

const { course, accessLink } = defineProps({
    course: {
        type: Object,
        required: true,
    },
    accessLink: {
        type: Object,
    }
})

const emit = defineEmits([ 'submitted' ])

const requiresName = ref( false );
const requiresAccount = ref( false );

const accessLinkObj = ref({})
if ( accessLink != null ) {
    accessLinkObj.value = accessLink
    console.log( accessLink.type )
    requiresName.value = accessLink.type != 'anonymous';
    requiresAccount.value = accessLink.type != 'anonymous' && accessLink.type != 'require-name';
}
else {
    accessLinkObj.value = {
        'name': (new Intl.DateTimeFormat( undefined, {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        })).format( new Date() ),
    }
}

const accessLinkType = computed( () => {
    if ( requiresName.value == false ) {
        return 'anonymous';
    }
    else if ( requiresName.value && !requiresAccount.value ) {
        return 'require-name';
    }
    else if ( requiresName.value && requiresAccount.value ) {
        return 'require-account';
    }

    return 'anonymous';
})

const url = accessLink?.id ? `/api/courses/${course.id}/access-links/${accessLink.id}` : `/api/courses/${course.id}/access-links`;
const method = accessLink?.id ? 'PATCH' : 'POST'

</script>

<template>
    <AjaxForm :action="url" :method="method" @success="emit( 'submitted' )">
        <TextField name="name" :value="accessLinkObj.name" select-all-on-focus>
            Name
            <HelpIcon>
                Give the access link a name so you can identify it later.
            </HelpIcon>
        </TextField>
        <DatePickerField name="expires_at" :value="accessLinkObj.expires_at" min-date="today" show-clear>
            Expires at (optional)
        </DatePickerField>
        <HiddenField name="type" v-model="accessLinkType"/>
        <CheckboxField name='requires_name' v-model="requiresName">
            Collect names of students
            <HelpIcon>
                By checking this option you will be able to see who viewed your course. Students will be asked to enter their name.
            </HelpIcon>
        </CheckboxField>
        <CheckboxField name="requires_account" v-model='requiresAccount' v-if="requiresName">
            Only verified users
            <HelpIcon>
                Only visitors with an Aktulibre account will be able to view this course.
            </HelpIcon>
        </CheckboxField>
    </AjaxForm>
</template>