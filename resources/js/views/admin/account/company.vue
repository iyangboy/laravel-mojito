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
                <el-button type="primary" @click="dialogAddAccountFormVisible = true" icon="el-icon-plus">
                    {{ $t('add') }}
                </el-button>
            </el-form-item>
        </el-form>
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
                    <el-button
                        size="mini"
                        @click="handleEditOperation(scope.$index, scope.row)">编辑
                    </el-button>
                    <el-button
                        size="mini"
                        @click="handleRecharge(scope.$index, scope.row)">充值
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

        <el-dialog title="试用期续费" :visible.sync="centerDialogVisible" width="30%" center>
            <div style="text-align: center;margin-bottom: 20px;">
                <el-button @click="choiceOperation(1)" :class="{ 'el-button--primary': isPrimaryTime }">补充时长</el-button>
                <el-button @click="choiceOperation(2)" :class="{ 'el-button--primary': isPrimaryFormal }">转为正式用户
                </el-button>
            </div>
            <div v-if="showDueTime">
                <div style="margin-bottom: 20px;text-align: center;">
                    到期时间：
                    <el-date-picker v-model="value1" type="date" placeholder="选择日期">
                    </el-date-picker>
                </div>
                <div slot="footer" class="dialog-footer" style="text-align: center;">
                    <el-button type="primary" @click="centerDialogVisible = false">确 定</el-button>
                    <el-button @click="centerDialogVisible = false">取 消</el-button>
                </div>
            </div>
            <div v-if="showFormal">
                <div :model="showEdit" style="text-align: center;">
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
                <div slot="footer" class="dialog-footer" style="text-align: center;">
                    <el-button type="primary" @click="handleEditAccountStatus">确 定</el-button>
                    <el-button @click="centerDialogVisible = false">取 消</el-button>
                </div>
            </div>
        </el-dialog>

        <el-dialog title="充值" :visible.sync="rechargeDialogVisible" width="30%" center>
            <el-form :model="addRechargeForm" :rules="addRechargeRules" ref="addRechargeForm">
                <el-form-item label="充值金额" prop="money" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model.number="addRechargeForm.money" @keyup.enter.native="handleClickMoney"
                                  placeholder="请输入金额"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="备注" prop="remarks" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addRechargeForm.remarks" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="rechargeDialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="RechargeSubmit">确 定</el-button>
            </div>
        </el-dialog>

        <el-dialog title="新建企业账号" :visible.sync="dialogAddAccountFormVisible" width="30%">
            <el-form :model="addAccountForm" :rules="addAccountRules" ref="addAccountForm">
                <el-form-item label="状态" prop="formal" :label-width="formLabelWidth">
                    <el-switch
                        v-model="addAccountForm.formal"
                        active-color="#13ce66"
                        inactive-color="#ff4949"
                        active-text="正式账号"
                        inactive-text="试用账号">
                    </el-switch>
                </el-form-item>
                <el-form-item label="试用期限" prop="trial_at" :label-width="formLabelWidth">
                    <el-date-picker
                        v-model="addAccountForm.trial_at"
                        type="date"
                        placeholder="选择日期">
                    </el-date-picker>
                </el-form-item>
                <el-form-item label="账号昵称" prop="name" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.name" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="账号邮箱" prop="email" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.email" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="企业名称" prop="company" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.company" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="联系方式" prop="phone" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.phone" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-row>

                </el-row>
                <div v-for="(account, index) in addAccountForm.accounts">
                    <el-row :gutter="20" style="border: 1px solid #eee; margin-bottom: 10px;">
                        <el-col :span="10">
                            <el-form-item
                                :label="'子账号' + (index +1)"
                                :key="account.id"
                                :prop="'accounts.' + index + '.name'"
                                :rules="[
                                { required: true, message: '请输入邮箱地址', trigger: 'blur' },
                                { type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] }
                                ]"
                            >
                                <el-col :span="23">
                                    <el-input v-model="account.name" placeholder="请输入账号邮箱"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item
                                :label="'密码' + (index +1)"
                                :key="account.id"
                                :prop="'accounts.' + index + '.password'"
                                :rules="{required:true, message:'密码不能为空', trigger: 'blur'}"
                            >
                                <el-col :span="23">
                                    <el-input type="password" v-model="account.password" placeholder="请输入"></el-input>
                                </el-col>
                            </el-form-item>
                        </el-col>
                        <el-col :span="4">
                            <el-button @click.prevent="removeSubsetAccount(account)" size="mini" style="float: right;">
                                <i class="el-icon-minus"></i>
                            </el-button>
                        </el-col>
                    </el-row>
                </div>
                <div style="text-align: center;">
                    <el-button @click="addSubsetAccount" size="mini"><i class="el-icon-plus"></i>新增子账号</el-button>
                </div>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogAddAccountFormVisible = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="handleAddAdminUser">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {getDataList, editAccountStatus} from '../../../api/accountManagement'
    import {tableDefaultData} from '../../../libs/tableDataHandle'

    export default {
        name: 'accountManagementIndex',
        components: {},
        data: () => ({
            ...tableDefaultData(),
            tableListData: [],
            showEdit: {},
            showOperation: {},
            centerDialogVisible: false,
            value1: '',
            showDueTime: true,
            showFormal: false,
            isPrimaryTime: true,
            isPrimaryFormal: false,
            dialogAddAccountFormVisible: false,
            rechargeDialogVisible: false,
            addRechargeForm: {
                money: '',
                remarks: '',
            },
            addRechargeRules: {
                money: [
                    {required: true, message: '金额不能为空'},
                    {type: 'number', message: '只能输入数字', trigger: 'blur'}
                ],
                remarks: [
                    {required: true, message: '备注不能为空'},
                    {min: 3, max: 255, message: '字符3~255'}
                ]
            },
            addAccountForm: {
                formal: false,
                name: '',
                email: '',
                company: '',
                phone: '',
                trial_at: '',
                accounts: [
                    {id: 0, name: '', password: ''}
                ],
            },
            addAccountRules: {
                name: [
                    {required: true, message: '昵称不能为空'},
                    {min: 3, max: 255, message: '字符3~255'}
                ],
                email: [
                    {required: true, message: '邮箱不能为空'},
                    {type: 'email', message: '请输入正确的邮箱'}
                ],
                company: [
                    {required: true, message: '密码不能为空'},
                    {min: 3, max: 255, message: '字符3~255'}
                ],
                phone: [
                    {required: true, message: '手机号不能为空'}
                ],
            },
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
            },
            // 操作
            handleEditOperation(index, row) {
                console.log(1);
                this.showEdit = row;
                this.centerDialogVisible = true
            },
            //
            handleEditOperationFormVisible() {

            },
            choiceOperation(show) {
                if (1 == show) {
                    this.showFormal = false;
                    this.isPrimaryFormal = false;
                    this.showDueTime = true;
                    this.isPrimaryTime = true;
                } else {
                    this.showDueTime = false;
                    this.isPrimaryTime = false;
                    this.showFormal = true;
                    this.isPrimaryFormal = true;
                }
            },
            handleAddAdminUser() {
                console.log(this.addAccountForm);
                this.$refs['addAccountForm'].validate((valid) => {
                    if (valid) {
                        console.log(valid);
                    } else {
                        return false;
                    }
                });
            },
            removeSubsetAccount(item) {
                let index = this.addAccountForm.accounts.indexOf(item);
                if (index !== -1) {
                    this.addAccountForm.accounts.splice(index, 1)
                }
            },
            addSubsetAccount() {
                this.addAccountForm.accounts.push({
                    id: '',
                    name: '',
                    password: '',
                    key: Date.now()
                });
            },
            //充值
            handleRecharge() {
                this.rechargeDialogVisible = true;
            },
            handleClickMoney() {
                console.log(this.addRechargeForm.money);
                this.addRechargeForm.money = this.addRechargeForm.money.replace(/[^\w]/g, '')
            },
            RechargeSubmit() {
                console.log(this.addRechargeForm);
                this.$refs['addRechargeForm'].validate((valid) => {
                    if (valid) {
                        console.log(valid);
                    } else {
                        return false;
                    }
                });
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
