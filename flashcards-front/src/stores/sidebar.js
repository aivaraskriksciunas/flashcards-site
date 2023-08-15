import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useSidebarStore = defineStore( 'sidebar', () => {
  
    const sidebarVisible = ref( false );

    const isSidebarVisible = computed( () => sidebarVisible.value )

    const showSidebar = () => { sidebarVisible.value = true }
    const hideSidebar = () => { sidebarVisible.value = false }

    return { isSidebarVisible, showSidebar, hideSidebar }

})
