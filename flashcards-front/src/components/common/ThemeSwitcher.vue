<script setup>
import { 
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectItem,
    SelectGroup,
} from '@/components/ui/select';
import { Palette } from 'lucide-vue-next';
import { useUserSettingStore } from "../../stores/user-settings";
import { ref, watch } from 'vue';
import { storeToRefs } from 'pinia';

const userSettings = useUserSettingStore();
const theme = ref( userSettings.colorTheme )

watch( theme, ( newTheme ) => {
    userSettings.setColorTheme( newTheme )
} )

</script>

<template>
    <Select v-model="theme">
        <SelectTrigger>
            <div class="flex items-center">
                <Palette size="16" class="mr-2" />
                <SelectValue></SelectValue>
            </div>
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem value="light">
                    Light
                </SelectItem>
                <SelectItem value="dark">
                    Dark
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>