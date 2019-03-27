import Admin from '../views/admin/main/index'
import ParentView from '../components/ParentView/ParentView'

export default [
    {
        name: 'adminMain',
        path: '/admin',
        redirect: '/admin/dashboard',
        meta: {
            provider: 'admin',
            title: 'home',
        },
        component: Admin,
        children: [
            {
                name: 'adminDashboard',
                path: 'dashboard',
                meta: {
                    provider: 'admin',
                    title: 'dashboard',
                    cache: true,
                    notClosable: true
                },
                component: resolve => void (require(['../views/admin/dashboard/index.vue'], resolve))
            },
            {
                name: 'adminUserIndex',
                path: 'admin-user',
                meta: {
                    provider: 'admin',
                    title: 'adminUser',
                    cache: true,
                    permission: 'admin-user.index'
                },
                component: resolve => void (require(['../views/admin/user/index.vue'], resolve))
            },
            {
                path: 'role',
                meta: {
                    provider: 'admin',
                    title: 'role',
                    cache: true,
                },
                component: ParentView,
                children: [
                    {
                        name: 'rolePermission',
                        path: 'assign-permissions/:id/:guardName',
                        meta: {
                            provider: 'admin',
                            title: 'roleAssignPermission',
                            permission: 'role.assign-permissions'
                        },
                        component: resolve => void (require(['../views/admin/role/permission.vue'], resolve)),
                    },
                    {
                        name: 'roleIndex',
                        path: '',
                        meta: {
                            provider: 'admin',
                            title: 'role',
                            cache: true,
                            permission: 'role.index'
                        },
                        component: resolve => void (require(['../views/admin/role/index.vue'], resolve)),
                    },
                ]
            },
            {
                name: 'permissionIndex',
                path: 'permission',
                meta: {
                    provider: 'admin',
                    title: 'permission',
                    cache: true,
                    permission: 'permission.index'
                },
                component: resolve => void (require(['../views/admin/permission/index.vue'], resolve))
            },
            {
                name: 'permissionGroupIndex',
                path: 'permission-group',
                meta: {
                    provider: 'admin',
                    title: 'permissionGroup',
                    cache: true,
                    permission: 'permission-group.index'
                },
                component: resolve => void (require(['../views/admin/permission-group/index.vue'], resolve))
            },
            {
                name: 'menuIndex',
                path: 'menu',
                meta: {
                    provider: 'admin',
                    title: 'menu',
                    cache: true,
                    permission: 'menu.index'
                },
                component: resolve => void (require(['../views/admin/menu/index.vue'], resolve))
            },
            // 用户管理
            {
                name: 'accountManagementIndex',
                path: 'account_management',
                meta: {
                    provider: 'admin',
                    title: 'accountManagement',
                    cache: true,
                    permission: 'account_management.index'
                },
                //component: resolve => void (require(['../views/admin/account_management/index.vue'], resolve))
                component: resolve => void (require(['../views/admin/account/company.vue'], resolve))
            },
            // 用户管理(子集用户)
            {
                name: 'accountSubsetIndex',
                path: 'account_subset',
                meta: {
                    provider: 'admin',
                    title: 'accountSubset',
                    cache: true,
                    permission: 'account_subset.index'
                },
                component: resolve => void (require(['../views/admin/account/subset.vue'], resolve))
            },
            {
                name: 'companyAccountSubsetIndex',
                //path: 'account_subset',
                path: 'account_subset/:id/company',
                meta: {
                    provider: 'admin',
                    title: 'companyAccountSubset',
                    cache: true,
                    permission: 'account_subset.index'
                },
                component: resolve => void (require(['../views/admin/account/subset.vue'], resolve))
            },
            // 数据详情
            {
                name: 'statisticsIndex',
                path: 'statistics',
                meta: {
                    // provider: 'admin',
                    title: 'statistics',
                    cache: true,
                    permission: 'statistics.index'
                },
                component: resolve => void (require(['../views/admin/statistics/index.vue'], resolve))
            },
            // 账号信息
            {
                name: 'companyAccountIndex',
                path: 'company/account',
                meta: {
                    provider: 'admin',
                    title: 'statistics',
                    cache: true,
                    permission: 'statistics.index'
                },
                component: resolve => void (require(['../views/admin/statistics/index.vue'], resolve))
            }
        ]
    },
    {
        name: 'adminLogin',
        path: '/admin/login',
        component: resolve => void (require(['../views/admin/login/index.vue'], resolve))
    },
    {
        name: 'memberMain',
        path: '/member',
        redirect: '/member/dashboard',
        meta: {
            provider: 'member',
            title: 'home',
        },
        component: Admin,
        children: [
            {
                name: 'memberDashboard',
                path: 'dashboard',
                meta: {
                    provider: 'member',
                    title: 'dashboard',
                    cache: true,
                    notClosable: true
                },
                component: resolve => void (require(['../views/member/dashboard/index.vue'], resolve))
            },
            // 企业信息
            {
                name: 'CompanyIndex',
                path: 'company',
                meta: {
                    provider: 'member',
                    title: 'CompanyIndex',
                    cache: true,
                    permission: 'company.index'
                },
                component: resolve => void (require(['../views/member/company/company.vue'], resolve))
            },
            // 账号信息
            {
                name: 'CompanyAccountIndex',
                path: 'company_account',
                meta: {
                    provider: 'member',
                    title: 'CompanyAccountIndex',
                    cache: true,
                    permission: 'company.index'
                },
                component: resolve => void (require(['../views/member/company/account.vue'], resolve))
            },
        ]
    },
    {
        name: 'memberLogin',
        path: '/member/login',
        component: resolve => void (require(['../views/member/login/index.vue'], resolve))
    },
]
