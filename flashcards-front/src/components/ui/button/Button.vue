<script setup>
import { Primitive } from "radix-vue";
import { buttonVariants } from ".";
import { cn } from "@/lib/utils";

defineProps({
  variant: { type: null, required: false },
  size: { type: null, required: false },
  as: { type: String, required: false, default: "button" },
  asChild: { type: Boolean, required: false },
  isLoading: { type: Boolean, default: false, required: false },
  type: { type: String, required: false, default: 'button' }
});
</script>

<template>
  <Primitive
    :as="as"
    :as-child="asChild"
    :type="type"
    :class="cn(buttonVariants({ variant, size }), $attrs.class ?? '')"
  >
    <slot />
  </Primitive>
</template>
