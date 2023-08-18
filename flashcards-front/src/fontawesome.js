
/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faThumbsUp, faThumbsDown, faEdit, faUser, faXmarkCircle, faCheckCircle } from '@fortawesome/free-regular-svg-icons'
import { faPlus, faTrash, faXmark, faChevronDown, faPaintRoller } from '@fortawesome/free-solid-svg-icons'
 
/* add icons to the library */
library.add( faThumbsUp, faThumbsDown, faEdit, faUser, faXmarkCircle, faXmark, faCheckCircle, faPlus, faTrash, faChevronDown, faPaintRoller )

export default FontAwesomeIcon