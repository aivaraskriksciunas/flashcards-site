<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <thead :class="cn('[&_tr]:border-b [&_tr]:border-border', props.class)">
    <slot />
  </thead>
</template>
