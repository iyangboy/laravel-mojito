<template>
    <div>
        <el-form :inline="true" :model="queryParams" size="mini">
            <el-form-item :label="$t('name')">
                <el-input v-model="queryParams.name"></el-input>
            </el-form-item>
            <el-form-item :label="$t('email')">
                <el-input v-model="queryParams.email"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="requestData" icon="el-icon-search">{{ $t('search') }}</el-button>
            </el-form-item>
        </el-form>
        <el-table
            :data="tableListData"
            v-loading="loading"
            border stripe
            class="init_table">
            <el-table-column
                prop="company.company"
                label="企业名称">
            </el-table-column>
            <el-table-column
                prop="name"
                label="名称">
            </el-table-column>
            <el-table-column
                prop="email"
                label="邮箱">
            </el-table-column>
            <el-table-column
                prop="password"
                label="密码">
            </el-table-column>
            <el-table-column
                align="center"
                :label="$t('actions')">
                <template slot-scope="scope">
                </template>
            </el-table-column>
        </el-table>
        <el-pagination class="mo-page"
                       @current-change="requestData"
                       :current-page.sync="pagination.currentPage"
                       :page-size="pagination.pageSize"
                       layout="total, prev, pager, next, jumper"
                       :total="pagination.total">
        </el-pagination>
    </div>
</template>

<script>
    import {getDataList,getDataCompanyList, editAccountStatus} from '../../../api/account/subset'
    import {tableDefaultData} from '../../../libs/tableDataHandle'

    export default {
        name: 'accountManagementIndex',
        components: {},
        data: () => ({
            ...tableDefaultData(),
            tableListData: [],
        }),
        methods: {
            requestData() {
                this.loading = true;
                if (this.$route.params.id){
                    getDataCompanyList(this.$route.params.id, {
                        ...this.queryParams,
                        page: this.pagination.currentPage
                    }).then(response => {
                        console.log(response.data);
                        this.tableListData = response.data.data;
                        let meta = response.data
                        this.pagination = {
                            currentPage: meta.current_page,
                            pageSize: meta.per_page,
                            total: meta.total,
                            from: meta.from,
                            lastPage: meta.last_page,
                            to: meta.to
                        }
                        this.loading = false
                    })
                } else {
                    getDataList({
                        ...this.queryParams,
                        page: this.pagination.currentPage
                    }).then(response => {
                        console.log(response.data);
                        this.tableListData = response.data.data;
                        let meta = response.data
                        this.pagination = {
                            currentPage: meta.current_page,
                            pageSize: meta.per_page,
                            total: meta.total,
                            from: meta.from,
                            lastPage: meta.last_page,
                            to: meta.to
                        }
                        this.loading = false
                    })
                }

            }
        },
        computed: {},
        created() {
            this.requestData()
        }
    }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
    .app_title {
        display: block;
        width: 100%;
        font-size: 24px;
        line-height: 60px;
        color: #41dae4;
        text-align: center;
    }

    .permission_toggleFold {
        vertical-align: middle;
        padding-right: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .permission_placeholder {
        content: ' ';
        display: inline-block;
        width: 16px;
        font-size: 16px;
    }

    .init_table {
        th {
            background-color: #edf6ff;
            text-align: center !important;
            color: #066cd4;
            font-weight: bold;

            .cell {
                padding: 0 !important;
            }
        }

        td, th {
            font-size: 12px;
            padding: 0 !important;
            height: 40px !important;
        }

        .el-table--border, .el-table--group {
            border: 1px solid #dde2ef;
        }

        td, th.is-leaf {
            border-bottom: 1px solid #dde2ef
        }

        .el-table--border td, .el-table--border th, .el-table__body-wrapper .el-table--border.is-scrolling-left ~ .el-table__fixed {
            border-right: 1px solid #dde2ef
        }

        .el-table--striped .el-table__body tr.el-table__row--striped td {
            background-color: #f7f9fa;
        }
    }

</style>
