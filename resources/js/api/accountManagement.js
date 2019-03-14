import qs from 'qs'
import http from '../libs/http'

const basicRoute = '/api/account_management'

export const getDataList = (params) => {
    return http.get(basicRoute, {
        params
    })
}

export const editAccountStatus = (id, data) => {
    return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}
