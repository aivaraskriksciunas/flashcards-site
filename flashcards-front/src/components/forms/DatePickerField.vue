<script setup>
import { ref, watch } from 'vue';
import { useFormElement } from "./composables/FormElement";
import FieldErrors from "./_FieldErrors.vue";
import { Popover, PopoverTrigger, PopoverContent } from "../ui/popover";
import { Calendar } from '@/components/ui/calendar';
import { CalendarIcon, X } from "lucide-vue-next";
import { Button } from "@/components/ui/button"
import {
  DateFormatter,
  getLocalTimeZone,
  CalendarDate,
  today,
  fromDate,
  toCalendarDate,
} from '@internationalized/date'

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
        default: null,
    },
    minDate: {
        type: String,
        default: '1980-01-01',
    },
    showClear: {
        type: Boolean,
        default: false,
    }
})

const df = new DateFormatter( undefined, {
    dateStyle: 'long',
})

const model = defineModel()
const { data, error } = useFormElement( props.name, model );

// Date object is CalendarDate used by the calendar component. 
const dateObject = ref( null )
if ( props.value ) {
    dateObject.value = toCalendarDate( fromDate( new Date( props.value ) ) )
}
// Convert date object to string which will be used as the value of this field
watch( dateObject, ( newDateObject ) => {
    if ( newDateObject == null ) {
        data.value = null
    }
    else {
        data.value = newDateObject.toString()
    }
}, { immediate: true } )


let minDate = new CalendarDate( '1980-01-01' )
if ( props.minDate === 'today' ) {
    minDate = today( getLocalTimeZone() )
}
else if ( props.minDate ) {
    minDate = new CalendarDate( props.minDate )
}

</script>

<template>
    <div class="form-group">
        <label>
            <slot></slot>
        </label>
        <div class="flex items-center">
            <Popover>
                <PopoverTrigger as-child>
                    <Button
                        variant="outline"
                        class="datepicker-control justify-start text-left font-normal flex-grow"
                    >
                        <CalendarIcon class="mr-2 h-4 w-4" />

                        <span v-if="!data">Pick a date</span>
                        <span v-else>
                            {{ df.format( dateObject.toDate( getLocalTimeZone() ) ) }}
                        </span>
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-auto p-0">
                    <Calendar v-model="dateObject" :min-value="minDate" />
                </PopoverContent>
            </Popover>

            <Button v-if="props.showClear" @click="dateObject = null" variant="ghost" class="ml-2" size="sm">
                <X class="text-muted-foreground" size="16"/>
            </Button>
        
        </div>
        
    </div>
    <FieldErrors :errors="error"></FieldErrors>
</template>

<style>
.datepicker-control {
    width: 100%;
}
</style>