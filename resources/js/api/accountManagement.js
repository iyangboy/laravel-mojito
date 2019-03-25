import qs from 'qs'
import http from '../libs/http'

const basicRoute = '/api/account_management'

export const getDataList = (params) => {
    return http.get(basicRoute, {
        params
    })
}

// 新建账号
export const addAccount = (data) => {
    return http.post(basicRoute, data)
}

// 新建账号
export const editAccountFormal = (id, data) => {
    return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}

export const editAccount = (id, data) => {
    return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}

export const editAccountStatus = (id, data) => {
    return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}
