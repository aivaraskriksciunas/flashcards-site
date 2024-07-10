<script setup>
import { HelpCircle } from 'lucide-vue-next';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger
} from '@/components/ui/tooltip'

</script>

<template>
    <Tooltip>
        <TooltipTrigger>
            <HelpCircle size="18" class="inline-block h-full text-muted-foreground"/>
        </TooltipTrigger>
        <TooltipContent>
            <slot></slot>
        </TooltipContent>
    </Tooltip>
</template>