import { ref, inject, onUnmounted } from "vue"
import { FormDiscovery, FormDeregister } from '../../../keys'

export function useFormElement( name ) {

    const data = ref( null )
    const error = ref( null )

    const elementModifier = {
        getValue: () => data.value,
        setValue: ( val ) => data.value = val,
        setError: ( err ) => error.value = err, 
    }

    // Attach itself to a form
    const register = inject( FormDiscovery )
    const deregister = inject( FormDeregister )
    if ( register != null ) {
        register( name, elementModifier )
    }

    if ( deregister != null ) {
        onUnmounted( () => {
            deregister( name )
        })
    }

    return { data, error }
}