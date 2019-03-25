import qs from 'qs'
import http from './../../libs/http'

const basicRoute = '/api/account_subset';

export const getDataList = (params) => {
    return http.get(basicRoute, {params})
}
export const getDataCompanyList = (company_id, params) => {
    return http.get(`${basicRoute}/${company_id}/company`, {params})
}

export const editAccountStatus = (id, data) => {
    return http.patch(`${basicRoute}/${id}`, qs.stringify(data))
}
