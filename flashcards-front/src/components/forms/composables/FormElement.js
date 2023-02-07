import { ref, inject } from "vue"
import { FormDiscovery } from '../../../keys'

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
    if ( register != null ) {
        register( name, elementModifier )
    }

    return { data, error }
}