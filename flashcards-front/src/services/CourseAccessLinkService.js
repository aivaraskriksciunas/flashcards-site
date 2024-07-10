import axios from "axios"

export const deleteAccessLink = ( course_id, id ) => {
    return axios.delete( `/api/courses/${course_id}/access-links/${id}`)
}