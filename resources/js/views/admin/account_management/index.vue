<template>
    <div>
        <el-table
            :data="tableListData"
            v-loading="loading"
            border stripe
            class="init_table">
            <el-table-column
                prop="permission_name"
                label="企业名称">
            </el-table-column>
            <el-table-column
                prop="sub_account_num"
                label="子账号数量">
            </el-table-column>
            <el-table-column
                prop="mobile"
                label="联系方式">
            </el-table-column>
            <el-table-column
                prop="count"
                label="机器翻译次数">
            </el-table-column>
            <el-table-column
                prop="type"
                label="账号类型">
            </el-table-column>
            <el-table-column
                align="center"
                :label="$t('actions')">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        @click="handleEditStatus(scope.$index, scope.row)">转正
                    </el-button>
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

        <el-dialog title="转为正式用户" :visible.sync="dialogEditFormVisible" width="30%" align='center'>
            <div :model="showEdit" ref="showEdit">
                <div>
                    <p><b>账号：</b><span>{{ showEdit.permission_name }}</span></p>
                </div>
                <div>
                    <p><b>企业名称：</b><span>{{ showEdit.permission_name }}</span></p>
                </div>
                <div>
                    <p><b>联系方式：</b><span>{{ showEdit.mobile }}</span></p>
                </div>
                <div>
                    <p><b>将转为正式企业用户，使用时间将不受限制，确定么？</b></p>
                </div>
            </div>
            <div slot="footer" class="dialog-footer" align='center'>
                <el-button type="primary" @click="handleEditAccountStatus">转 正</el-button>
                <el-button @click="dialogEditFormVisible = false">关 闭</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {getDataList, editAccountStatus} from '../../../api/accountManagement'
    import {tableDefaultData} from '../../../libs/tableDataHandle'
    import {hasPermission} from '../../../libs/permission'

    export default {
        name: 'accountManagementIndex',
        components: {},
        data: () => ({
            ...tableDefaultData(),
            tableListData: [],
            showEdit: {},
        }),
        methods: {
            requestData() {
                this.loading = true
                getDataList({...this.queryParams, page: this.pagination.currentPage}).then(response => {
                    console.log(response.data)
                    // this.tableListData = this.formatConversion([], response.data.data)
                    // responseDataFormat(response, this);
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
            },
            handleEditStatus(index, row) {
                //console.log(index)
                //console.log(row)
                //this.editForm = row;
                this.nowRowData = {index, row}
                this.showEdit = row;
                console.log(this.showEdit);
                this.dialogEditFormVisible = true
            },
            handleEditAccountStatus() {
                //console.log(this.nowRowData.row.id);
                this.editForm = {status: 1}
                editAccountStatus(this.nowRowData.row.id, this.editForm).then(response => {
                    //console.log(response);
                    let status = response.data;
                    if (status) {
                        this.$notify({
                            title: '成功',
                            message: '操作成功',
                            type: 'success'
                        });
                    } else {
                        this.$notify.error({
                            title: '错误',
                            message: '操作有误'
                        });
                    }
                    this.dialogEditFormVisible = false;
                    this.requestData()
                })
            }
        },
        computed: {},
        created() {
            console.log(123);
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
