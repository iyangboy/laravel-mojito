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
                <el-button v-if="isCompany" type="primary" @click="dialogAddAccountForm = true" icon="el-icon-plus">
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
                prop="company.company_name"
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
                prop="mobile"
                label="联系方式">
            </el-table-column>
            <el-table-column
                prop="password"
                label="密码">
            </el-table-column>
            <el-table-column
                align="center"
                :label="$t('actions')">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        @click="handleEdit(scope.$index, scope.row)">编辑
                    </el-button>
                    <el-button
                        size="mini"
                        @click="handleEditPasswordVisible(scope.$index, scope.row)">修改密码
                    </el-button>
                    <el-button
                        size="mini"
                        @click="handleDeleteVisible(scope.$index, scope.row)">删除
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

        <el-dialog title="添加子账号" :visible.sync="dialogAddAccountForm" width="30%">
            <el-form :model="addAccountForm" :rules="addAccountFormRules" ref="addAccountForm">
                <el-form-item label="账号邮箱" prop="email" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.email" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="密码" prop="password" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input type="password" v-model="addAccountForm.password" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="使用者" prop="name" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.name" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="联系方式" prop="mobile" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="addAccountForm.mobile" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogAddAccountForm = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="addAccount">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>

        <el-dialog title="编辑账号信息" :visible.sync="dialogEdit" width="30%">
            <el-form :model="editAccountForm" :rules="editAccountFormRules" ref="editAccountForm">
                <el-form-item label="账号邮箱" prop="email" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="editAccountForm.email" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="密码" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-button type="warning" plain @click="showNewPassword" size="mini">重置密码</el-button>
                    </el-col>
                </el-form-item>
                <el-form-item v-if="newPassword" label="新密码" prop="password" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input type="password" v-model="editAccountForm.password" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="使用者" prop="name" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="editAccountForm.name" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="联系方式" prop="mobile" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input v-model="editAccountForm.mobile" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogEdit = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="editAccount">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>

        <el-dialog title="修改密码" :visible.sync="dialogPassword" width="30%">
            <el-form :model="editPasswordForm" :rules="editPasswordFormRules" ref="editPasswordForm">
                <el-form-item label="原密码" prop="old_password" :label-width="formLabelWidth"
                              :rules="[
                              {required: true, message: '密码不能为空'},
                              {min: 6, max: 32, message: '字符6~32'},
                              {validator: validateOldPassword, trigger: 'blur'}
                              ]">
                    <el-col :span="12">
                        <el-input type="password" v-model="editPasswordForm.old_password" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="新密码" prop="password" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input type="password" v-model="editPasswordForm.password" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
                <el-form-item label="新密码确认" prop="passwords" :label-width="formLabelWidth">
                    <el-col :span="12">
                        <el-input type="password" v-model="editPasswordForm.passwords" placeholder="请输入"></el-input>
                    </el-col>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogPassword = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="editAccountPassword">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>

        <el-dialog title="删除" :visible.sync="dialogDelete" width="30%">
            <div>
                <h3>删除账号！</h3>
            </div>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogDelete = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="deleteAccountButton">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {
        getDataList,
        postAddAccount,
        getDataCompanyList,
        editAccountData,
        deleteAccount,
        getPasswordValidate
    } from '../../../api/account/subset'
    import {tableDefaultData} from '../../../libs/tableDataHandle'

    export default {
        //name: 'accountManagementIndex',
        name: 'companyAccountSubsetIndex',
        components: {},
        data() {
            let validatePass = (rule, value, callback) => {
                //console.log(value);
                if (value === '') {
                    callback(new Error('请输入密码'));
                } else {
                    if (this.editPasswordForm.passwords !== '') {
                        this.$refs.editPasswordForm.validateField('passwords');
                    }
                    callback();
                }
            };
            let validatePass2 = (rule, value, callback) => {
                //console.log(value);
                if (value === '') {
                    callback(new Error('请再次输入密码'));
                } else if (value !== this.editPasswordForm.password) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                ...tableDefaultData(),
                companyId: 0,
                tableListData: [],
                isCompany: false,
                // 新建
                dialogAddAccountForm: false,
                addAccountForm: {
                    email: '',
                    password: '',
                    name: '',
                    mobile: '',
                },
                addAccountFormRules: {
                    email: [
                        {required: true, message: '邮箱不能为空'},
                        {type: 'email', message: '请输入正确的邮箱'}
                    ],
                    password: [
                        {required: true, message: '密码不能为空'},
                        {min: 6, max: 32, message: '字符6~32'}
                    ],
                    name: [
                        {required: true, message: '使用者名称不能为空'},
                        {min: 3, max: 255, message: '字符3~255'}
                    ],
                    mobile: [
                        {required: true, message: '手机号不能为空'}
                    ],
                },
                // 编辑
                dialogEdit: false,
                newPassword: false,
                editAccountForm: {
                    email: '',
                    password: '',
                    name: '',
                    mobile: '',
                },
                editAccountFormRules: {
                    email: [
                        {required: true, message: '邮箱不能为空'},
                        {type: 'email', message: '请输入正确的邮箱'}
                    ],
                    password: [
                        {required: true, message: '密码不能为空'},
                        {min: 6, max: 32, message: '字符6~32'},
                    ],
                    name: [
                        {required: true, message: '使用者名称不能为空'},
                        {min: 3, max: 255, message: '字符3~255'}
                    ],
                    mobile: [
                        {required: true, message: '手机号不能为空'}
                    ],
                },
                // 修改密码
                dialogPassword: false,
                editPasswordForm: {
                    old_password: '',
                    password: '',
                    passwords: '',
                },
                editPasswordFormRules: {
                    password: [
                        {required: true, message: '密码不能为空'},
                        {min: 6, max: 32, message: '字符6~32'},
                        {validator: validatePass, trigger: 'blur'}
                    ],
                    passwords: [
                        {required: true, message: '密码不能为空'},
                        {min: 6, max: 32, message: '字符6~32'},
                        {validator: validatePass2, trigger: 'blur'}
                    ],
                },
                // 删除
                dialogDelete: false,
            }
        },
        methods: {
            requestData() {
                this.loading = true;
                this.tableListData = [];
                //console.log(123);
                //console.log(this.$route.params.id);
                if (this.companyId) {
                    this.isCompany = true;
                    getDataCompanyList(this.companyId, {
                        ...this.queryParams,
                        page: this.pagination.currentPage
                    }).then(response => {
                        //console.log(response.data);
                        this.tableListData = response.data.data;
                        let meta = response.data;
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
                        //console.log(response.data);
                        this.tableListData = response.data.data;
                        let meta = response.data;
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
            },
            // 添加子账户
            addAccount() {
                let data = {};
                data.company_id = this.companyId;
                data.email = this.addAccountForm.email;
                data.password = this.addAccountForm.password;
                data.name = this.addAccountForm.name;
                data.mobile = this.addAccountForm.mobile;
                //console.log(data);
                //return false;
                this.$refs['addAccountForm'].validate((valid) => {
                    if (valid) {
                        // console.log(1);
                        postAddAccount(data).then(response => {
                            // console.log(response);
                            if (response.data) {
                                this.$notify({
                                    title: '成功',
                                    message: '创建成功',
                                    type: 'success'
                                });
                            } else {
                                this.$notify.error({
                                    title: '错误',
                                    message: '操作有误'
                                });
                            }
                            this.addAccountForm = {};
                            this.dialogAddAccountForm = false;
                            this.requestData();
                        });
                    } else {
                        //console.log(2)
                    }
                });
            },
            // 编辑
            handleEdit(index, row) {
                console.log(row);
                this.nowRowData = {index, row};
                this.editAccountForm = {...row};
                this.editAccountForm.password = '';
                this.dialogEdit = true
            },
            showNewPassword() {
                this.editAccountForm.password = '';
                this.newPassword = !this.newPassword;
            },
            editAccount() {
                let data = {};
                data.email = this.editAccountForm.email;
                if (this.newPassword) {
                    data.password = this.editAccountForm.password;
                }
                data.name = this.editAccountForm.name;
                data.mobile = this.editAccountForm.mobile;
                console.log(data);
                this.$refs['editAccountForm'].validate((valid) => {
                    if (valid) {
                        console.log(1);
                        editAccountData(this.nowRowData.row.id, data).then(response => {
                            console.log(response);
                            let status = response.data;
                            if (status) {
                                this.$notify({
                                    title: '成功',
                                    message: '编辑成功',
                                    type: 'success'
                                });
                            } else {
                                this.$notify.error({
                                    title: '错误',
                                    message: '操作有误'
                                });
                            }
                            this.dialogAddAccountForm = false;
                            this.dialogEdit = false;
                            this.requestData();
                        })
                    } else {
                        console.log(2);
                        return false;
                    }
                });
            },
            // 修改密码
            handleEditPasswordVisible(index, row) {
                console.log(row);
                this.nowRowData = {index, row};
                this.editPasswordForm = {...row};
                this.editPasswordForm.password = '';
                this.dialogPassword = true
            },
            validateOldPassword(rule, value, callback) {
                let data = {};
                data.id = this.nowRowData.row.id;
                data.password = value;
                getPasswordValidate(data).then(response => {
                    console.log(response.data);
                    let rs = response.data;
                    if (!rs) {
                        callback(new Error('密码有误'));
                    } else {
                        callback();
                    }
                })
            },
            editAccountPassword() {
                //console.log(this.nowRowData.row.id);
                //console.log(this.editPasswordForm);
                let data = {};
                //data.old_password = this.editPasswordForm.old_password;
                data.password = this.editPasswordForm.password;
                console.log(data);
                this.$refs['editPasswordForm'].validate((valid) => {
                    if (valid) {
                        editAccountData(this.nowRowData.row.id, data).then(response => {
                            //console.log(response);
                            let status = response.data;
                            if (status) {
                                this.$notify({
                                    title: '成功',
                                    message: '密码修改成功',
                                    type: 'success'
                                });
                            } else {
                                this.$notify.error({
                                    title: '错误',
                                    message: '操作有误'
                                });
                            }
                            this.dialogPassword = false;
                            this.requestData();
                        })
                    } else {
                        // console.log(2);
                        return false;
                    }
                });
            },
            // 删除
            handleDeleteVisible(index, row) {
                console.log(row);
                this.nowRowData = {index, row};
                console.log(this.nowRowData);
                this.dialogDelete = true;
            },
            deleteAccountButton() {
                let id = this.nowRowData.row.id;
                //console.log(id);
                //console.log(this.$route.params.id);
                if (id) {
                    deleteAccount(id).then(response => {
                        //console.log(response);
                        if (response.data) {
                            this.$notify({
                                title: '成功',
                                message: '编辑成功',
                                type: 'success'
                            });
                        } else {
                            this.$notify.error({
                                title: '错误',
                                message: '操作有误'
                            });
                        }
                        this.dialogDelete = false;
                        this.requestData()
                    })
                }
            }
        },
        computed: {},
        watch: {
            $route(route) {
                if (route.name === 'accountSubsetIndex') {
                    this.requestData()
                }
            }
        },
        created() {
            console.log(this.$route);
            if (this.$route.query.company_id) {
                this.companyId = this.$route.query.company_id;
            } else if (this.$route.params.id) {
                this.companyId = this.$route.params.id;
            } else {
                this.companyId = 0;
            }
            console.log(this.companyId);
            this.requestData()
        },
        activated: function () {
            console.log(this.$route);
            if (this.$route.query.company_id) {
                this.companyId = this.$route.query.company_id;
            } else if (this.$route.params.id) {
                this.companyId = this.$route.params.id;
            } else {
                this.companyId = 0;
            }
            console.log(this.companyId);
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
