import qs from 'qs'
import http from './../../libs/http'

const basicRoute = '/api/account_subset';

export const getDataList = (params) => {
    return http.get(basicRoute, {params})
}
export const getDataCompanyList = (company_id, params) => {
    return http.get(`${basicRoute}/${company_id}/company`, {params})
}

// 新建账号
export const postAddAccount = (data) => {
    return http.post(basicRoute, data)
}

export const editAccountData = (id, data) => {
    return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}

export const deleteAccount = id => {
    return http.delete(`${basicRoute}/${id}`)
}

export const getPasswordValidate = (params) => {
    return http.get(`${basicRoute}/password_validate`, {params})
}
