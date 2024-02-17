<script setup>
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { 
    Type,
    List,
    Sigma,
} from 'lucide-vue-next';

const model = defineModel()

</script>

<template>
    <ToggleGroup v-model="model" type="single" size="sm">
        <ToggleGroupItem value="text" aria-label="Toggle text">
            <Type class="text-muted-foreground" size="16"/>
        </ToggleGroupItem>
        <ToggleGroupItem value="list" aria-label="Toggle list">
            <List class="text-muted-foreground" size="16"/>
        </ToggleGroupItem>
        <ToggleGroupItem value="math" aria-label="Toggle formula">
            <Sigma class="text-muted-foreground" size="16"/>
        </ToggleGroupItem>
    </ToggleGroup>
</template>