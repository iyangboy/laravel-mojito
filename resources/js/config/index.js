export default {
    admin: {
        authorize: {
            clientId: 2,
            // clientSecret: '0amTuj8HL4BlPtUs8StQCNiKnNiPHdMg3qDPgOXa'
            clientSecret: 'BkFGfMt2jpl1W5VamY9ZZwrBozyo8TvLWu9FOBWK'
        },
        // provider 为 admin 时登录路由名
        loginRouteName: 'adminLogin',

        // provider 为 admin 初始页面路由
        dashboardName: 'adminDashboard',

        // provider 为 admin 初始页面完全路径
        dashboardFullPath: '/admin/dashboard',

        appName: {
            // 项目全名
            fullName: '语到管理',
            // 项目缩写名
            abbrName: '语到'
        },

        locale: 'zh' // en
    },

    // 支持多表登录时，guard 键值对
    guardNames: [
        {
            label: 'admin',
            value: 'admin'
        }
    ],

    apiUrl: '',

    //Unit is day
    tokenTTL: 1,

    //Unit is day
    refreshTokenTTL: 7,

    showAuthorGitHubUrl: true,
}
