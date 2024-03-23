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

const userSettings = useUserSettingStore();

</script>

<template>
    <Select :model-value="userSettings.colorTheme">
        <SelectTrigger>
            <div class="flex items-center">
                <Palette size="16" class="mr-2" />
                <SelectValue></SelectValue>
            </div>
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem value="light" @click="userSettings.setColorTheme( 'light' )">
                    Light
                </SelectItem>
                <SelectItem value="dark" @click="userSettings.setColorTheme( 'dark' )">
                    Dark
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>